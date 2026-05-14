@extends('adminlte::page')
@section('title', __('adminlte::adminlte.visionAndMessage') . __('adminlte::adminlte.createNewPost'))

@section('content_header')
    <div class="row">
        <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a
                    href="{{ localizedRoute('management-board.index') }}">{{ __('adminlte::adminlte.visionAndMessage') }}</a></li>
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

    <form action="{{ localizedRoute('management-board.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card card-default">
            <div class="card-body">
                <div class="row">
                    <!-- title Column -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <x-adminlte.form.input id="title" name="title"
                                label="{{ __('adminlte::adminlte.title(AR)') }}" label-class="text-olive" placeholder="{{ __('adminlte::landingpage.fullName') }} .."  enable-old-support
                                required />
                        </div>
                        <div class="form-group">
                            <x-adminlte.form.input id="title_en" name="title_en" label-class="text-olive" style="text-align: left; direction: ltr;"
                                label="{{ __('adminlte::adminlte.title(EN)') }}" placeholder="{{ __('adminlte::landingpage.fullName') }} .." enable-old-support />
                        </div>
                    </div>

                    <!-- job Column -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <x-adminlte-input name="content_ar" label="{{ __('adminlte::adminlte.contentAR') }}"
                                placeholder="{{ __('adminlte::adminlte.job') }} .." label-class="text-olive" enable-old-support>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <div class="form-group">
                            <x-adminlte-input name="content_en" label="{{ __('adminlte::adminlte.contentEN') }}"
                                placeholder="{{ __('adminlte::adminlte.job') }} .." label-class="text-olive" enable-old-support>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>    
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
                            'overwriteInitial' => false,
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
                        ];
                    @endphp
                    <div class="form-group">
                        <x-adminlte-input-file-krajee name="files"
                            label="{{ __('adminlte::adminlte.attachmentsUpload') }}"
                            data-msg-placeholder="قم بارفاق صورة" label-class="text-olive"
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

    <script>
        // $.fn.fileinputBsVersion = "3.3.7"; // if not set, this will be auto-derived
        // $(document).ready(function() {
        // $("#datetimepicker1").datetimepicker({
        //     format: 'YYYY/MM/DD',
        //     defaultDate: "{{ Carbon\Carbon::now()->format('Y/m/d') }}",
        // });
        // $('.select2').select2()

        //Initialize Select2 Elements
        //     $('.select2bs4').select2({
        //         theme: 'bootstrap4'
        //     })
        // });
        // initialize plugin with defaults
        //  $("#input-id").fileinput();

        // with file input
        // $(document).ready(function() {
        //     $("#input-id").fileinput({
        //         theme: 'fa5',
        //         uploadUrl: "/site/test-upload",
        //         enableResumableUpload: true,
        //         initialPreviewAsData: true,
        //         maxFileCount: 10,
        //         allowedFileTypes: ["image"],
        //         // allowedFileExtensions: ["jpg", "gif", "png"],
        //         elErrorContainer: "#errorBlock",
        //         deleteUrl: '/site/file-delete',
        //         removeFromPreviewOnError: true,
        //         overwriteInitial: true,
        //         // previewFileIcon: '<i class="fas fa-file"></i>', 
        //         fileActionSettings: {
        //             showZoom: function(config) {
        //                 if (config.type === 'pdf' || config.type === 'image') {
        //                     return true;
        //                 }
        //                 return false;
        //             },
        //             showRotate: function(config) {
        //                 return false;
        //             },
        //         }
        //     })
        // });

        // text editor
        // $(document).ready(function() {
        //     $('#texteditor').summernote({
        //         toolbar: [
        //             ['style', ['bold', 'italic', 'underline', 'clear']],
        //             ['font', ['strikethrough', 'superscript', 'subscript']],
        //             ['fontsize', ['fontsize']],
        //             ['color', ['color']],
        //             ['para', ['ul', 'ol', 'paragraph']],
        //             ['height', ['height']]
        //         ],
        //         c: [
        //             ['#E9ECEF', '#CED4DA', '#ADB5BD', '#6C757D', '#495057'],
        //         ],
        //         colors: [
        //             [
        //                 // 'oklch(0.985 0.002 247.839)', // gray-50
        //                 '#fff', // gray-200
        //                 'oklch(0.872 0.01 258.338)', // gray-300
        //                 'oklch(0.707 0.022 261.325)', // gray-400
        //                 'oklch(0.551 0.027 264.364)', // gray-500
        //                 'oklch(0.446 0.03 256.802)', // gray-600
        //                 'oklch(0.373 0.034 259.733)', // gray-700
        //                 'oklch(0.278 0.033 256.848)', // gray-800
        //                 '#000' // gray-900
        //             ],
        //             // Reds
        //             [
        //                 'oklch(0.885 0.062 18.334)', // red-200
        //                 'oklch(0.808 0.114 19.571)', // red-300
        //                 'oklch(0.704 0.191 22.216)', // red-400
        //                 'oklch(0.637 0.237 25.331)', // red-500
        //                 'oklch(0.577 0.245 27.325)', // red-600
        //                 'oklch(0.505 0.213 27.518)', // red-700
        //                 'oklch(0.444 0.177 26.899)', // red-800
        //                 'oklch(0.396 0.141 25.723)', // red-900
        //             ],
        //             // // Greens
        //             // [
        //             //     'oklch(0.925 0.084 155.995)', // green-200
        //             //     'oklch(0.871 0.15 154.449)',  // green-300
        //             //     'oklch(0.792 0.209 151.711)', // green-400
        //             //     'oklch(0.723 0.219 149.579)', // green-500
        //             //     'oklch(0.627 0.194 149.214)', // green-600
        //             //     'oklch(0.527 0.154 150.069)', // green-700
        //             //     'oklch(0.448 0.119 151.328)', // green-800
        //             //     'oklch(0.393 0.095 152.535)', // green-900
        //             // ],
        //             // Emeralds
        //             [
        //                 'oklch(0.905 0.093 164.15)', // emerald-200
        //                 'oklch(0.845 0.143 164.978)', // emerald-300
        //                 'oklch(0.765 0.177 163.223)', // emerald-400
        //                 'oklch(0.696 0.17 162.48)', // emerald-500
        //                 'oklch(0.596 0.145 163.225)', // emerald-600
        //                 'oklch(0.508 0.118 165.612)', // emerald-700
        //                 'oklch(0.432 0.095 166.913)', // emerald-800
        //                 'oklch(0.378 0.077 168.94)', // emerald-900
        //             ],
        //             // Teals
        //             [
        //                 'oklch(0.91 0.096 180.426)', // teal-200
        //                 'oklch(0.855 0.138 181.071)', // teal-300
        //                 'oklch(0.777 0.152 181.912)', // teal-400
        //                 'oklch(0.704 0.14 182.503)', // teal-500
        //                 'oklch(0.6 0.118 184.704)', // teal-600
        //                 'oklch(0.511 0.096 186.391)', // teal-700
        //                 'oklch(0.437 0.078 188.216)', // teal-800
        //                 'oklch(0.386 0.063 188.416)', // teal-900
        //             ],
        //             // Skys
        //             [
        //                 'oklch(0.901 0.058 230.902)', // sky-200
        //                 'oklch(0.828 0.111 230.318)', // sky-300
        //                 'oklch(0.746 0.16 232.661)', // sky-400
        //                 'oklch(0.685 0.169 237.323)', // sky-500
        //                 'oklch(0.588 0.158 241.966)', // sky-600
        //                 'oklch(0.5 0.134 242.749)', // sky-700
        //                 'oklch(0.443 0.11 240.79)', // sky-800
        //                 'oklch(0.391 0.09 240.876)', // sky-900
        //             ],
        //             // Blues
        //             [
        //                 'oklch(0.882 0.059 254.128)', // blue-200
        //                 'oklch(0.809 0.105 251.813)', // blue-300
        //                 'oklch(0.707 0.165 254.624)', // blue-400
        //                 'oklch(0.623 0.214 259.815)', // blue-500
        //                 'oklch(0.546 0.245 262.881)', // blue-600
        //                 'oklch(0.488 0.243 264.376)', // blue-700
        //                 'oklch(0.424 0.199 265.638)', // blue-800
        //                 'oklch(0.379 0.146 265.522)', // blue-900
        //             ]
        //         ]
        //     });
        // });
    </script>


@stop
