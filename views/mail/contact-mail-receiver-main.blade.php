<?php

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var $vm        object          The view model object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

declare(strict_types=1);

use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

?>

@extends('mail.mail-layout')

@section('content')
    <style>
        table th {
            text-align: left;
        }
    </style>

    <p>
        Hi Admin:
    </p>

    <p>
        @lang('contact.mail.receive.intro')
    </p>

    <div>
        @include('contact.contact-table-main')
    </div>

    <div style="margin-top: 30px">
        <a class="btn btn-primary"
            href="{{ $nav->to('admin::contact_list')->var('type', $item->getType())->full() . '#contact-' . $item->getId() }}">
            @lang('contact.mail.button.manage')
        </a>
    </div>
@stop
