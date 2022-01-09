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
        <div class="alert alert-primary">
            @lang('contact.message.thanks.for.submit')
        </div>
        <div class="my-3">
            <a href="{{ $nav->to('home') }}" class="btn btn-primary btn-lg">
                @lang('contact.button.back.to.home')
            </a>
        </div>
    </div>
@stop
