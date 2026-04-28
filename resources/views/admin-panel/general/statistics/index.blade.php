@extends('adminlte::page')
@section('title', __('adminlte::adminlte.statistics'))

@section('content_header')
    <h1>{{ __('adminlte::adminlte.statistics') }}</h1>
@stop

@php
    $route = 'statistics';
    $id = 'statistic';
@endphp

@section('content')
    @include('admin-panel.modals')

    <div class="container mx-0 mb-5">
        <a href="{{ route("$route.create", ['locale' => app()->getLocale()]) }}">
            <x-adminlte-button class="btn-lg mb-10" label="{{ __('adminlte::adminlte.createNewPost') }}"
                theme="outline-success" icon="fas fa-plus-square" />
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="container">
            <x-adminlte-card title="{{ __('adminlte::adminlte.error!') }}" theme="danger" theme-mode="outline"
                icon="fas fa-lg fa-exclamation-circle"
                body-class="{{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}"
                header-class="text-uppercase rounded-bottom border-info text-left" removable>
                <i>{{ session('error') }}</i>
            </x-adminlte-card>
        </div>
    @endif

    @php
        $heads = [
            ['label' => 'ID', 'width' => 5, 'classes' => 'border border-white bg-olive'],
            ['label' => __('adminlte::adminlte.number'), 'classes' => 'border border-white bg-olive'],
            ['label' => __('adminlte::adminlte.unit'), 'classes' => 'border border-white bg-olive'],
            ['label' => __('adminlte::adminlte.description'), 'classes' => 'border border-white bg-olive'],
            ['label' => __('adminlte::adminlte.order'), 'classes' => 'border border-white bg-olive'],
            [
                'label' => __('adminlte::adminlte.procedures'),
                'classes' => 'border border-white bg-olive',
                'no-export' => true,
                'width' => 15,
            ],
        ];

        $data = [];
        foreach ($statistics as $stat) {
            $btnEdit =
                '<a href="' .
                route("$route.edit", ['statistic' => $stat->id, 'locale' => app()->getLocale()]) .
                '" class="btn btn-warning " data-toggle="tooltip" title="' .
                __('adminlte::adminlte.edit') .
                '"><i class="fas fa-edit"></i></a>';

            $btnDelete =
                '<span style="display: inline-block; " data-toggle="tooltip" title="' .
                __('adminlte::adminlte.delete') .
                ' "><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalCustom" data-id="' .
                $stat->id .
                '"><i class="fas fa-trash"></i></button></span>';

            $btnActivation =
                '<span data-toggle="tooltip" title="' .
                __('adminlte::adminlte.activate') .
                ' "><a href="#" class="btn btn-success " data-toggle="modal" data-target="#modalActivate" data-id="' .
                $stat->id .
                '"><i class="fas fa-eye"></i></a></span>';

            $data[] = [
                $stat->id,
                $stat->number,
                app()->getLocale() == 'ar' ? $stat->unit_ar : $stat->unit_en,
                app()->getLocale() == 'ar' ? $stat->description_ar : $stat->description_en,
                $stat->order,
                $btnEdit . ' ' . ($stat->active ? $btnDelete : $btnActivation . ' ' . $btnDelete),
            ];
        }

        $config = [
            'data' => $data,
            'order' => [[4, 'asc']],
            'columns' => [null, null, null, null, null, ['orderable' => false]],
            'escape' => false,
            'scrollX' => true,
            'dom' => 'fBrtp',
            'paging' => true,
            'language' => [
                'search' => __('adminlte::adminlte.search'),
                'paginate' => [
                    'next' => '&raquo;',
                    'previous' => '&laquo;',
                    'last' => '&raquo;&raquo;',
                    'first' => '&laquo;&laquo;',
                ],
            ],
        ];
    @endphp

    <x-adminlte-datatable id="table-statistics" :heads="$heads" head-theme="dark" :config="$config" striped hoverable
        bordered compressed with-buttons />

    {{-- Modals for Delete and Activation --}}
    <x-adminlte-modal tabindex="-1" id="modalCustom" title="{{ __('adminlte::adminlte.caution!') }}" theme="danger"
        icon="fas fa-exclamation-triangle" v-centered>
        <div>{{ __('adminlte::adminlte.deleteCaution') }}</div>
        <div style="font-size: 14px;"><br>{{ __('adminlte::adminlte.deactivateCaution') }}</div>
        <x-slot name="footerSlot">
            <form id="formDelete" action="" method="POST">
                @csrf
                @method('DELETE')
                <x-adminlte-button class="mr-auto" type="submit" theme="outline-danger"
                    label="{{ __('adminlte::adminlte.delete') }}" />
            </form>
            <form id="formDeactivate" action="" method="POST" style="display:inline;">
                @csrf
                @method('PUT')
                <x-adminlte-button class="mr-auto" type="submit" theme="outline-success"
                    label="{{ __('adminlte::adminlte.deActivate') }}" />
            </form>
            <x-adminlte-button theme="secondary" label="{{ __('adminlte::adminlte.cancel') }}" data-dismiss="modal" />
        </x-slot>
    </x-adminlte-modal>

    <x-adminlte-modal tabindex="-1" id="modalActivate" title="{{ __('adminlte::adminlte.activate') }}" theme="success"
        icon="fas fa-question" v-centered>
        <div>{{ __('adminlte::adminlte.activeCaution') }}</div>
        <x-slot name="footerSlot">
            <form id="formActivate" action="" method="POST">
                @csrf
                @method('PUT')
                <x-adminlte-button class="mr-auto" type="submit" theme="outline-success"
                    label="{{ __('adminlte::adminlte.activate') }}" />
            </form>
            <x-adminlte-button theme="secondary" label="{{ __('adminlte::adminlte.cancel') }}" data-dismiss="modal" />
        </x-slot>
    </x-adminlte-modal>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('adminlte_js')
    <script>
        $(document).ready(function() {
            $('#modalCustom').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var statId = button.data('id');

                var deleteAction =
                    '{{ route($route . '.destroy', ['locale' => app()->getLocale(), $id => '__ID__']) }}';
                deleteAction = deleteAction.replace('__ID__', statId);
                $('#formDelete').attr('action', deleteAction);

                var deactivateAction = '{{ route($route . '.toggleActive', ['locale' => app()->getLocale(), 'id' => '__ID__']) }}';
                deactivateAction = deactivateAction.replace('__ID__', statId);
                $('#formDeactivate').attr('action', deactivateAction);
            });

            $('#modalActivate').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var statId = button.data('id');
                var action = '{{ route($route . '.toggleActive', ['locale' => app()->getLocale(), 'id' => '__ID__']) }}';
                action = action.replace('__ID__', statId);
                $('#formActivate').attr('action', action);
            });
        });
    </script>
@stop
