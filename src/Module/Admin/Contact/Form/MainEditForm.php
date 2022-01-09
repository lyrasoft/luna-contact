<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\Contact\Module\Admin\Contact\Form;

use Lyrasoft\Contact\Enum\ContactState;
use Windwalker\Core\Language\TranslatorTrait;
use Lyrasoft\Luna\Field\UserModalField;
use Windwalker\Form\Field\EmailField;
use Windwalker\Form\Field\NumberField;
use Unicorn\Field\CalendarField;
use Windwalker\Form\Field\TextareaField;
use Windwalker\Form\Field\HiddenField;
use Unicorn\Enum\BasicState;
use Windwalker\Form\Field\ListField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\Field\UrlField;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;

/**
 * The EditForm class.
 */
class MainEditForm implements FieldDefinitionInterface
{
    use TranslatorTrait;

    /**
     * Define the form fields.
     *
     * @param  Form  $form  The Windwalker form object.
     *
     * @return  void
     */
    public function define(Form $form): void
    {
        $form->fieldset(
            'basic',
            function (Form $form) {
                $form->add('title', TextField::class)
                    ->label($this->trans('unicorn.field.title'))
                    ->required(true)
                    ->addFilter('trim');

                $form->add('name', TextField::class)
                    ->label($this->trans('contact.field.name'))
                    ->required(true)
                    ->addFilter('trim');

                $form->add('email', EmailField::class)
                    ->label($this->trans('contact.field.email'));

                $form->add('phone', TextField::class)
                    ->label($this->trans('contact.field.phone'));

                $form->add('url', UrlField::class)
                    ->label($this->trans('contact.field.url'));

                $form->add('content', TextareaField::class)
                    ->label($this->trans('contact.field.content'))
                    ->rows(10);

                $form->add('id', HiddenField::class);

                $form->add('type', HiddenField::class)
                    ->label($this->trans('unicorn.field.type'));
            }
        );

        $form->fieldset(
            'meta',
            function (Form $form) {
                $form->add('state', ListField::class)
                    ->label($this->trans('unicorn.field.state'))
                    ->registerOptions(ContactState::getTransItems($this->lang));

                $form->add('assignee_id', UserModalField::class)
                    ->label($this->trans('contact.field.assignee'));

                $form->add('created', CalendarField::class)
                    ->label($this->trans('unicorn.field.created'))
                    ->disabled(true);

                $form->add('modified', CalendarField::class)
                    ->label($this->trans('unicorn.field.modified'))
                    ->disabled(true);

                $form->add('created_by', UserModalField::class)
                    ->label($this->trans('unicorn.field.author'))
                    ->disabled(true);

                $form->add('modified_by', UserModalField::class)
                    ->label($this->trans('unicorn.field.modified_by'))
                    ->disabled(true);
            }
        );

        $form->wrap(
            'details',
            'details',
            function (Form $form) {
                // $form->add('address', TextField::class)
                //     ->label('Address');
            }
        );
    }
}
