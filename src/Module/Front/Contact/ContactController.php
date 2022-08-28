<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\Contact\Module\Front\Contact;

use Lyrasoft\Contact\Entity\Contact;
use Lyrasoft\Contact\Event\ContactAfterSendEvent;
use Lyrasoft\Contact\Event\ContactBeforeSendEvent;
use Lyrasoft\Contact\Module\Front\Contact\Form\EditForm;
use Lyrasoft\Contact\Repository\ContactRepository;
use Lyrasoft\Contact\Service\ContactService;
use Lyrasoft\Luna\Access\AccessService;
use Lyrasoft\Luna\Entity\User;
use Lyrasoft\Luna\Entity\UserRoleMap;
use Lyrasoft\Luna\Repository\UserRepository;
use Lyrasoft\Luna\User\UserService;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Unicorn\Repository\Event\PrepareSaveEvent;
use Unicorn\Selector\ListSelector;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Router\Navigator;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\Event\AfterSaveEvent;
use Windwalker\Query\Query;

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
            function (AfterSaveEvent $event) use ($app, $repository, $userService, $userRepository, $contactService) {
                $data = $event->getData();

                /** @var Contact $entity */
                $entity = $repository->getItem($data['id']);
                $type   = $entity->getType();

                // Admin receiver mail
                // ------------------------
                $message = $contactService->createReceiverMailMessage($entity);
                
                $roles = $app->config('contact.receivers.' . $type . '.roles') ?? ['superuser', 'manager', 'admin'];

                /** @var User[] $users */
                $users = $userRepository->getListSelector()
                    ->where('user.receive_mail', 1)
                    ->where('user.enabled', 1)
                    ->where('user.verified', 1)
                    ->modifyQuery(
                        fn(Query $query) => $query->where(
                            $query->expr(
                                'EXISTS()',
                                $query->createSubQuery()
                                    ->select('*')
                                    ->from(UserRoleMap::class)
                                    ->whereRaw('user_id = user.id')
                                    ->whereRaw('role_id IN(%r)', implode(',', $query->quote($roles)))
                            )
                        )
                    )
                    ->debug()
                    ->all(User::class);

                $sendEvent = $app->emit(
                    ContactBeforeSendEvent::class,
                    compact(
                        'message',
                        'entity',
                        'users',
                        'type'
                    )
                );

                $users = $sendEvent->getUsers();
                $message = $sendEvent->getMessage();
                $entity = $sendEvent->getEntity();

                foreach ($users as $user) {
                    $message->bcc($user->getEmail());
                }

                $message->send();
                // ------------------------
                // End Admin mail

                $app->emit(
                    ContactAfterSendEvent::class,
                    compact(
                        'message',
                        'entity',
                        'users',
                        'type'
                    )
                );

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
