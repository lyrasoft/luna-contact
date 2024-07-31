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

/**
 * @var \Lyrasoft\Contact\Entity\Contact $item
 */
?>

<table class="table table-striped">

    {{-- ID --}}
    <tr>
        <th style="width: 30%;">
            @lang('unicorn.field.id')
        </th>
        <td>
            #{{ $item->getId() }}
        </td>
    </tr>

    {{-- Time --}}
    <tr>
        <th>
            @lang('unicorn.field.created')
        </th>
        <td>
            {{ $chronos->toLocalFormat($item->getCreated()) }}
        </td>
    </tr>

    {{-- Title --}}
    @if (method_exists($item, 'getTitle'))
        <tr>
            <th >
                @lang('unicorn.field.title')
            </th>
            <td>
                {{ $item->getTitle() }}
            </td>
        </tr>
    @endif

    {{-- Name --}}
    @if (method_exists($item, 'getName'))
        <tr>
            <th >
                @lang('contact.field.name')
            </th>
            <td>
                {{ $item->getName() }}
            </td>
        </tr>
    @endif

    {{-- Email --}}
    @if (method_exists($item, 'getEmail'))
        <tr>
            <th >
                @lang('contact.field.email')
            </th>
            <td>
                <a href="mailto://{{ $item->getEmail() }}">
                    {{ $item->getEmail() ?: '-' }}
                </a>
            </td>
        </tr>
    @endif

    {{-- Phone --}}
    @if (method_exists($item, 'getPhone'))
        <tr>
            <th >
                @lang('contact.field.phone')
            </th>
            <td>
                <a href="tel://{{ $item->getPhone() }}">
                    {{ $item->getPhone() ?: '-' }}
                </a>
            </td>
        </tr>
    @endif

    {{-- URL --}}
    @if (method_exists($item, 'getUrl'))
        <tr>
            <th >
                @lang('contact.field.url')
            </th>
            <td>
                @if (trim($item->getUrl()))
                    <a href="{{ $item->getUrl() }}" target="_blank">
                        {{ $item->getUrl() }}
                    </a>
                @else
                    -
                @endif
            </td>
        </tr>
    @endif

    {{-- Params --}}
    @if (method_exists($item, 'getParams'))
        @if ($item->getParams()['ip'] ?? '')
            <tr>
                <th >
                    IP
                </th>
                <td>
                    {{ $item->getParams()['ip'] ?? '' }}
                </td>
            </tr>
        @endif
    @endif

    {{-- Content --}}
    @if (method_exists($item, 'getContent'))
        <tr>
            <th >
                @lang('contact.field.content')
            </th>
            <td>
                {!! html_escape($item->getContent() ?: '-', true) !!}
            </td>
        </tr>
    @endif

    @foreach ($item->getDetails() as $key => $value)
        <tr>
            <th>
                @if ($lang->has('contact.field.' . $key))
                    @lang('contact.field.' . $key)
                @else
                    {{ $key }}
                @endif
            </th>
            <td>{{ $value }}</td>
        </tr>
    @endforeach
</table>
