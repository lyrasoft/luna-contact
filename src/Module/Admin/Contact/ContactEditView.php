<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\Contact\Module\Admin\Contact;

use Lyrasoft\Contact\Module\Admin\Contact\Form\MainEditForm;
use Lyrasoft\Contact\Repository\ContactRepository;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Form\FormFactory;
use Windwalker\Core\Html\HtmlFrame;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Exception\RouteNotFoundException;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;

/**
 * The ContactEditView class.
 */
#[ViewModel(
    layout: [
        'default' => 'contact-edit',
        'preview' => 'contact-preview',
    ],
    js: 'contact-edit.js'
)]
class ContactEditView implements ViewModelInterface
{
    use TranslatorTrait;

    public function __construct(
        protected ORM $orm,
        protected FormFactory $formFactory,
        protected Navigator $nav,
        #[Autowire] protected ContactRepository $repository
    ) {
    }

    /**
     * Prepare
     *
     * @param  AppContext  $app
     * @param  View        $view
     *
     * @return  mixed
     */
    public function prepare(AppContext $app, View $view): mixed
    {
        $id = $app->input('id');
        $type = $app->input('type');

        $item = $this->repository->getItem($id);

        if ($view->getLayout() === 'contact-preview') {
            if (!$item) {
                throw new RouteNotFoundException();
            }

            $this->prepareMetadata($app, $view);
            return compact('id', 'item', 'type');
        }

        $form = $this->formFactory
            ->create(
                $this->repository->getFormClass('admin', 'edit', $type),
                type: $type
            )
            ->setNamespace('item')
            ->fill(
                $this->repository->getState()->getAndForget('edit.data')
                    ?: $this->orm->extractEntity($item)
            )
            ->fill(
                ['details' => $item->getDetails()]
            );

        $this->prepareMetadata($app, $view);

        return compact('form', 'id', 'item', 'type');
    }

    /**
     * Prepare Metadata and HTML Frame.
     *
     * @param  AppContext  $app
     * @param  View        $view
     *
     * @return  void
     */
    protected function prepareMetadata(AppContext $app, View $view): void
    {
        $type = $app->input('type');

        $view->getHtmlFrame()
            ->setTitle(
                $this->trans('unicorn.title.edit', title: $this->trans('contact.' . $type . '.title'))
            );
    }
}
