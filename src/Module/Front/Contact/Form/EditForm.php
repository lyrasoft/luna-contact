<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\Contact\Module\Front\Contact\Form;

use Lyrasoft\Luna\Field\CaptchaField;
use Unicorn\Enum\BasicState;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Field\EmailField;
use Windwalker\Form\Field\HiddenField;
use Windwalker\Form\Field\ListField;
use Windwalker\Form\Field\TextareaField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\Field\UrlField;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;

/**
 * The EditForm class.
 */
class EditForm implements FieldDefinitionInterface
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
                    ->required(true)
                    ->label($this->trans('contact.field.email'));

                $form->add('phone', TextField::class)
                    ->label($this->trans('contact.field.phone'));

                $form->add('url', UrlField::class)
                    ->label($this->trans('contact.field.url'));

                $form->add('content', TextareaField::class)
                    ->label($this->trans('contact.field.content'))
                    ->required(true)
                    ->rows(10);

                $form->add('captcha', CaptchaField::class)
                    ->jsVerify(true)
                    ->autoValidate(true)
                    ->required(true);

                $form->add('id', HiddenField::class);

                $form->add('type', HiddenField::class)
                    ->label($this->trans('unicorn.field.type'));
            }
        );
    }
}
