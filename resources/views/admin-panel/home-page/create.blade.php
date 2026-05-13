@extends('adminlte::page')

@php
    $route = 'home-page';
    $pageName = 'menu.home-page';
@endphp

@section('title', __("adminlte::$pageName") .' - '. __('adminlte::adminlte.createNewPost'))

@section('content_header')
    <div class="row">
        <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a
                    href="{{ localizedRoute("$route.index") }}">{{ __("adminlte::$pageName") }}</a></li>
            <li class="breadcrumb-item active">{{ __('adminlte::adminlte.createNewPost') }}</li>
        </ol>
    </div>
@stop


@section('content')
    {{-- Placeholder, date only and append icon --}}
    @include('admin-panel.modals')

    @if (session('error'))
    <div class="container">
        <x-adminlte-card title="{{ __('adminlte::adminlte.error!') }}" theme="danger" theme-mode="outline" icon="fas fa-lg fa-exclamation-circle" body-class="{{ app()->getLocale() =='ar'? 'text-right' : 'text-left' }}"
            header-class="text-uppercase rounded-bottom border-info text-left" removable >
            <i >{{ session('error') }}</i>
        </x-adminlte-card>
    </div>
    @endif

    <form action="{{ localizedRoute("$route.store") }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card card-default">
            <div class="card-body">
                <div class="row">
                    <!-- title Column -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <x-adminlte.form.input id="title" name="title"
                                label="{{ __('adminlte::adminlte.title(AR)') }}" label-class="text-olive" enable-old-support
                                required />
                        </div>

                        <div class="form-group">
                            <x-adminlte.form.input id="title_en" name="title_en" label-class="text-olive"
                                label="{{ __('adminlte::adminlte.title(EN)') }}" enable-old-support />
                        </div>
                    </div>

                    <!-- link Column -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <x-adminlte.form.input id="link" name="link" label-class="text-olive"
                                label="{{ __('adminlte::adminlte.link') }}" enable-old-support />
                        </div>
                    </div>
                </div>

                <div class="row">
                    @php
                        $config = [
                            'height' => 150,
                            'toolbar' => [
                                ['style', ['bold', 'italic', 'underline', 'clear']],
                                ['font', ['strikethrough', 'superscript', 'subscript']],
                                ['fontsize', ['fontsize']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']],
                                ['height', ['height']],
                                ['view', ['fullscreen', 'codeview', 'help']],
                            ],
                        ];
                    @endphp
                    <div class="col-12 col-md-6">
                        <x-adminlte-text-editor name="content_ar" label="{{ __('adminlte::adminlte.contentAR') }}"
                            label-class="text-olive" :config="$config" required />
                    </div>
                    <div class="col-12 col-md-6">
                        <x-adminlte-text-editor name="content_en" label="{{ __('adminlte::adminlte.contentEN') }}"
                            label-class="text-olive" :config="$config" required />
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                {{-- Attachments images upload --}}
                <div class="row">
                    <!-- File Upload -->
                    @php
                        $config = [
                            'allowedFileTypes' => ['image'],
                            'browseOnZoneClick' => true,
                            'theme' => 'fa5',
                            'overwriteInitial' => true,
                            'initialPreviewAsData' => true,
                            'uploadUrl' => '#',
                            'uploadAsync' => false,
                            'deleteUrl' => '#',
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'fileActionSettings' => [
                                'showRemove' => true,
                                'showZoom' => true,
                                'showUpload' => false,
                                'showRotate'=> false,
                            ],
                            'showCancel' => false,
                            // 'maxFileCount' => 5,
                        ];
                    @endphp
                    <div class="form-group">
                        <x-adminlte-input-file-krajee name="files"
                            label="{{ __('adminlte::adminlte.attachmentsUpload') . ' (' . __('adminlte::adminlte.image') . ')' }}"
                            data-msg-placeholder="Choose a text, office or pdf file..." label-class="text-olive"
                            :config="$config" />
                    </div>
                </div>
                <div class="text-center">
                    <button class="btn btn-success p-2 col-12 col-md-6 " type="submit">
                        {{ __('adminlte::adminlte.save') }}
                    </button>
                </div>
            </div>
        </div>
    </form>

@stop

@section('plugins.toastr', true)
{{-- @section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true) --}}
@section('plugins.KrajeeFileinput', true)
@section('plugins.Summernote', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true)

@section('adminlte_js')
@stop
