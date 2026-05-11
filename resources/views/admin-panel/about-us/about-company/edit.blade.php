@extends('adminlte::page')
@push('css')
    <style>
        /* Make modal text responsive with fluid sizing */
        .responsive-text {
            font-size: 14px;
            /* Default size for mobile */
            font-size: clamp(14px, 2.5vw, 18px);
            /* Fluid size for desktop */
            line-height: 1.5;
        }

        @media (min-width: 768px) {
            .responsive-text {
                font-size: 16px;
            }
        }

        /* Custom CSS for RTL to make the select2 arrow responsive */
        [dir="rtl"] .select2-container .select2-selection--single {
            padding-right: 0 !important;
            padding-left: 1.5rem !important;
        }

        [dir="rtl"] .select2-container .select2-selection__arrow {
            left: 1px !important;
            right: auto !important;
        }
    </style>
@endpush

@section('title', __('adminlte::adminlte.aboutCompany') . '-' . __('adminlte::adminlte.editNews'))

@section('content_header')
    <div class="row">
        <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a
                    href="{{ localizedRoute('about-company.index') }}">{{ __('adminlte::adminlte.aboutCompany') }}</a></li>
            <li class="breadcrumb-item active">{{ __('adminlte::adminlte.editNews') }}</li>
        </ol>
    </div>
@stop


@section('content')
    {{-- Placeholder, date only and append icon --}}
    @include('admin-panel.modals')

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


    <form action="{{ localizedRoute('about-company.update', ['about_company' => $post->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card card-default">
            <div class="card-body">
                <div class="row">
                    <!-- title Column -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <x-adminlte.form.input id="title" name="title" value="{{ $post->postDetailOne->title }}"
                                label="{{ __('adminlte::adminlte.title(AR)') }}" label-class="text-olive" enable-old-support
                                required />
                        </div>
                        <div class="form-group">
                            <x-adminlte.form.input id="title_en" name="title_en" label-class="text-olive"
                                value="{{ $post->postDetailOne->title_en }}"
                                label="{{ __('adminlte::adminlte.title(EN)') }}" enable-old-support />
                        </div>

                    </div>

                    <!-- slug Column -->
                    <div class="col-12 col-md-6">

                        @if ($post->order != 0)
                            <x-adminlte-select2 igroup-size="lg" name="icon" label="{{ __('adminlte::adminlte.icon') }}"
                                id="icon-select" label-class="text-olive" enable-old-support required>
                                @foreach ($icons as $icon)
                                    <option value="{{ $icon['value'] }}"
                                        {{ $post->postDetailOne->color == $icon['value'] ? 'selected' : '' }}
                                        data-icon="{{ e(svg($icon['value'], 'text-success', ['width' => '30', 'height' => '30'])) }}">
                                        {{ $icon['label'] }}
                                    </option>
                                @endforeach
                            </x-adminlte-select2>
                            @push('js')
                                <script>
                                    $(document).ready(function() {
                                        $('#icon-select').select2({
                                            templateResult: function(state) {
                                                if (!state.id) return state.text; // placeholder
                                                var icon = $(state.element).data('icon');
                                                return $('<span>' + icon + ' ' + state.text + '</span>');
                                            },
                                            templateSelection: function(state) {
                                                if (!state.id) return state.text;
                                                var icon = $(state.element).data('icon');
                                                return $('<span>' + icon + ' ' + state.text + '</span>');
                                            },
                                            escapeMarkup: function(markup) {
                                                return markup;
                                            }
                                        });
                                    });
                                </script>
                            @endpush
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    {{-- content --}}
                    @if ($post->order == 0)
                        @php
                            $config = [
                                'height' => 200,
                                'minHeight' => 100,
                                'maxHeight' => null,
                                'dialogsInBody' => true,
                                'disableResizeEditor' => false,
                                'toolbar' => [
                                    ['style', ['bold', 'italic', 'underline', 'clear']],
                                    ['fontsize', ['fontsize']],
                                    ['color', ['color']],
                                    ['para', ['ul', 'ol', 'paragraph']],
                                    ['height', ['height']],
                                    ['insert', ['link']],
                                    ['view', ['fullscreen', 'codeview']],
                                ],
                                'colorList' => [
                                    // Corrected from 'c' to 'colorList'
                                    [
                                        '#fff',
                                        '#228B22',
                                        '#3cb371',
                                        '#2e8b57',
                                        '#006400',
                                        '#008000',
                                        '#32cd32',
                                        '#90ee90',
                                    ],
                                ],
                            ];
                        @endphp
                        <div class="form-group col-12 col-md-6">
                            <x-adminlte-text-editor name="content_ar" label="{{ __('adminlte::adminlte.contentAR') }}"
                                label-class="text-olive" igroup-size="sm" placeholder="اكتب النص هنا ..." :config="$config"
                                enable-old-support>
                                {{ $post->postDetailOne->content }}
                            </x-adminlte-text-editor>
                        </div>
                        {{-- content EN --}}

                        <div class="form-group col-12 col-md-6" style="text-align: left; direction: ltr;  text-align: left;" >
                            <x-adminlte-text-editor name="content_en" label="{{ __('adminlte::adminlte.contentEN') }}"
                                value="{{ $post->postDetailOne->content_en }}" label-class="text-olive" igroup-size="sm"
                                placeholder="Write some text..." :config="$config" enable-old-support>
                                {{ $post->postDetailOne->content_en }}
                            </x-adminlte-text-editor>
                        </div>
                    @else
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <x-adminlte.form.input id="title" name="content_ar"
                                    value="{{ $post->postDetailOne->content }}"
                                    label="{{ __('adminlte::adminlte.contentAR') }}" label-class="text-olive"
                                    enable-old-support required />
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <x-adminlte.form.input id="content_en" name="content_en" label-class="text-olive"
                                    value="{{ $post->postDetailOne->content_en }}"
                                    label="{{ __('adminlte::adminlte.contentEN') }}" enable-old-support />
                            </div>
                        </div>
                    @endif
                </div>
                {{-- Attachments images upload --}}
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
@section('plugins.KrajeeFileinput', true)
@section('plugins.Summernote', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true)

@section('adminlte_js')
    <script>
        $(document).ready(function() {
            function formatIcon(state) {
                if (!state.id) {
                    return state.text;
                }
                var $state = $(
                    '<span class="d-flex align-items-center">' +
                    state.element.dataset.icon + // SVG from data-icon
                    '<span class="ml-2">' + state.text + '</span>' +
                    '</span>'
                );
                return $state;
            }

            $('#icon-select').select2({
                templateResult: formatIcon,
                templateSelection: formatIcon,
                escapeMarkup: function(m) {
                    return m;
                } // allow HTML
            });
        });
    </script>
@stop
