<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\Contact\Module\Front\Contact;

use Lyrasoft\Contact\Module\Front\Contact\Form\EditForm;
use Lyrasoft\Contact\Repository\ContactRepository;
use Lyrasoft\Contact\Service\ContactService;
use Lyrasoft\Luna\Access\AccessService;
use Lyrasoft\Luna\Entity\User;
use Lyrasoft\Luna\Repository\UserRepository;
use Lyrasoft\Luna\User\UserService;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Unicorn\Repository\Event\PrepareSaveEvent;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Router\Navigator;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\Event\AfterSaveEvent;

/**
 * The ContactController class.
 */
#[Controller()]
class ContactController
{
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        #[Autowire] ContactRepository $repository,
        #[Autowire] UserRepository $userRepository,
        UserService $userService,
        ContactService $contactService
    ): mixed {
        $form = $app->make(EditForm::class);

        $controller->setMuted(true);

        $controller->prepareSave(
            function (PrepareSaveEvent $event) {
                $data = &$event->getData();

                $data['type'] = 'main';
            }
        );

        $controller->afterSave(
            function (AfterSaveEvent $event) use ($repository, $userService, $userRepository, $contactService) {
                $data = $event->getData();
                
                $entity = $repository->getItem($data['id']);

                // Admin receiver mail
                // ------------------------
                $message = $contactService->createReceiverMailMessage($entity);

                /** @var User[] $users */
                $users = $userRepository->getListSelector()
                    ->where('user.receive_mail', 1)
                    ->where('user.enabled', 1)
                    ->getIterator(User::class);

                foreach ($users as $user) {
                    // Only who have admin access that can receive mail.
                    if ($userService->can(AccessService::ADMIN_ACCESS_ACTION, $user)) {
                        $message->bcc($user->getEmail());
                    }
                }

                $message->send();
                // ------------------------
                // End Admin mail

                // Thanks mail
                // ------------------------
                $message = $contactService->createThanksMailMessage($entity);

                $message->send();
                // ------------------------
                // End Thanks mail
            }
        );

        $uri = $app->call([$controller, 'save'], compact('repository', 'form'));

        return $nav->self()->layout('complete');
    }
}
