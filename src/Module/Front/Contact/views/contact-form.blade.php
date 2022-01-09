<?php

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        ContactView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

declare(strict_types=1);

use Lyrasoft\Contact\Module\Front\Contact\ContactView;
use Lyrasoft\Contact\Entity\Contact;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Form\Form;

/**
 * @var Form      $form
 * @var Contact $item
 */
?>

@extends('global.body')

@section('content')
    <div class="container my-5">
        <form name="admin-form" id="admin-form"
            uni-form-validate='{"scroll": true}'
            action="{{ $nav->to('contact') }}"
            method="POST" enctype="multipart/form-data">

            <header class="mb-4">
                <h2>@lang('contact.us.title')</h2>
            </header>

            <x-card class="">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <x-field :field="$form['name']"></x-field>
                    </div>

                    <div class="col-md-6">
                        <x-field :field="$form['email']"></x-field>
                    </div>
                </div>
                <div class="row mb-3">
                    @if ($form->hasField('phone'))
                    <div class="col-md-6">
                        <x-field :field="$form['phone']"></x-field>
                    </div>
                    @endif

                    @if ($form->hasField('url'))
                    <div class="col-md-6">
                        <x-field :field="$form['url']"></x-field>
                    </div>
                        @endif
                </div>
                @if ($form->hasField('title'))
                    <div class="mb-3">
                        <x-field :field="$form['title']"></x-field>
                    </div>
                @endif
                <div class="mb-3">
                    <x-field :field="$form['content']"></x-field>
                </div>
                @if ($form->hasField('captcha'))
                <div class="mb-3">
                    <x-field :field="$form['captcha']"></x-field>
                </div>
                @endif
            </x-card>

            @if ($form->countFields('details'))
                <div class="mt-4">
                    <x-fieldset name="details" :title="$lang('contact.fieldset.details')"
                        :form="$form"
                        class="mb-4"
                        is="card"
                    >
                    </x-fieldset>
                </div>
            @endif

            <div class="my-4 d-grid gap-2">
                <button type="submit" class="btn btn-primary" data-dos>
                    @lang('contact.button.submit')
                </button>
            </div>

            <div class="d-none">
{{--                @include('@csrf')--}}
            </div>
        </form>
    </div>
@stop
