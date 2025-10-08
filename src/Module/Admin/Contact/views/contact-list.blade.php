<?php

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        ContactListView The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

declare(strict_types=1);

use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Lyrasoft\Contact\Module\Admin\Contact\ContactListView;

/**
 * @var \Lyrasoft\Contact\Entity\Contact $entity
 */

$workflow = $app->service(\Lyrasoft\Contact\Workflow\ContactStateWorkflow::class);
?>

@extends('admin.global.body-list')

@section('toolbar-buttons')
    @include('list-toolbar')
@stop

@section('content')
    <form id="admin-form" action="" x-data="{ grid: $store.grid }"
        x-ref="gridForm"
        data-ordering="{{ $ordering }}"
        method="post">

        <x-filter-bar :form="$form" :open="$showFilters"></x-filter-bar>

        @if (count($items))
        {{-- RESPONSIVE TABLE DESC --}}
        <div class="d-block d-lg-none mb-3">
            @lang('unicorn.grid.responsive.table.desc')
        </div>

        <div class="grid-table table-lg-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    {{-- Toggle --}}
                    <th style="width: 1%">
                        <x-toggle-all></x-toggle-all>
                    </th>

                    {{-- State --}}
                    <th style="width: 5%" class="text-nowrap">
                        <x-sort field="contact.state">
                            @lang('unicorn.field.state')
                        </x-sort>
                    </th>

                    {{-- Edit --}}
                    <th style="width: 1%" class="text-nowrap">
                        @lang('contact.action.edit')
                    </th>

                    {{-- Title --}}
                    <th class="text-nowrap">
                        <x-sort field="contact.title">
                            @lang('unicorn.field.title')
                        </x-sort>
                    </th>

                    {{-- Name --}}
                    <th class="text-nowrap">
                        <x-sort field="contact.name">
                            @lang('contact.field.name')
                        </x-sort>
                    </th>

                    {{-- Created --}}
                    <th class="text-nowrap">
                        <x-sort field="contact.created">
                            @lang('unicorn.field.created')
                        </x-sort>
                    </th>

                    {{-- Assignee --}}
                    <th class="text-nowrap">
                        <x-sort field="contact.assignee_id">
                            @lang('contact.field.assignee')
                        </x-sort>
                    </th>

                    {{-- Delete --}}
                    <th style="width: 1%" class="text-nowrap">
                        @lang('unicorn.field.delete')
                    </th>

                    {{-- ID --}}
                    <th style="width: 1%" class="text-nowrap text-end">
                        <x-sort field="contact.id">
                            @lang('unicorn.field.id')
                        </x-sort>
                    </th>
                </tr>
                </thead>

                <tbody>
                @foreach($items as $i => $item)
                    <?php
                        $entity = $vm->prepareItem($item);
                    ?>
                    <tr>
                        {{-- Checkbox --}}
                        <td>
                            <x-row-checkbox :row="$i" :id="$entity->id
"></x-row-checkbox>
                        </td>

                        {{-- State --}}
                        <td>
                            <x-state-dropdown color-on="text"
                                style="width: 100%"
                                button-style="width: 100%"
                                color-on="button"
                                use-states
                                :workflow="$workflow"
                                :id="$entity->id
"
                                :value="$item->state"
                            />
                        </td>

                        {{-- Edit --}}
                        <td class="text-center">
                            <a class="btn btn-sm btn-outline-primary"
                                href="{{ $nav->to('contact_edit')->id($entity->id
) }}"
                                title="@lang('contact.action.edit')">
                                <i class="fa fa-pen-to-square"></i>
                            </a>
                        </td>

                        {{-- Title --}}
                        <td>
                            <div class="mb-1">
                                <a href="{{ $nav->to('contact_edit')->id($entity->id
)->layout('preview') }}"
                                    uni-modal-link="#preview-modal"
                                    data-resize="1"
                                >
                                    <span class="fa fa-eye"></span>

                                    @if (isset($item->title))
                                    {{ $item->title }}
                                    @else
                                    #{{ $item->id }}
                                    @endif
                                </a>
                            </div>
                            <div class="small text-muted">
                                {{ \Windwalker\str($item->content)->truncate(150, '...') }}
                            </div>
                        </td>

                        {{-- Name --}}
                        <td class="text-nowrap">
                            <div class="small">
                                {{ $item->name }}
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <div class="small" data-bs-toggle="tooltip"
                                title="{{ $chronos->toLocalFormat($item->created) }}">
                                {{ $chronos->toLocalFormat($item->created, 'Y-m-d') }}
                            </div>
                        </td>

                        {{-- Assignee --}}
                        <td class="text-nowrap">
                            @if ($item->assignee_id)
                                <div class="d-flex align-items-center">
                                    @if (isset($item->assignee->avatar))
                                        <div class="me-2">
                                            <img src="{{ $item->assignee->avatar }}" alt="Avatar"
                                                style="width: 24px; height: 24px;"
                                                class="rounded-circle"
                                            >
                                        </div>
                                    @endif
                                    <div class="small">
                                        <a href="{{ $nav->to('user_edit')->id($item->assignee->id) }}"
                                            class="text-muted">
                                            {{ $item->assignee->name }}
                                        </a>
                                    </div>
                                </div>
                            @else
                                -
                            @endif
                        </td>

                        {{-- Delete --}}
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                @click="grid.deleteItem('{{ $entity->id
 }}')"
                                data-dos
                            >
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>

                        {{-- ID --}}
                        <td class="text-end">
                            {{ $entity->id
 }}
                        </td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="20">
                        {!! $pagination->render() !!}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        @else
            <div class="grid-no-items card bg-light" style="padding: 125px 0;">
                <div class="card-body text-center">
                    <h3 class="text-secondary">@lang('unicorn.grid.no.items')</h3>
                </div>
            </div>
        @endif

        <div class="d-none">
            <input name="_method" type="hidden" value="PUT" />
            @include('@csrf')
        </div>

        <x-batch-modal :form="$form" namespace="batch" :copy="false"></x-batch-modal>

        <uni-iframe-modal id="preview-modal" size="modal-lg"
            data-route="{{ $nav->to('contact_edit')->id('{id}')->layout('preview') }}"
        ></uni-iframe-modal>
    </form>

@stop
