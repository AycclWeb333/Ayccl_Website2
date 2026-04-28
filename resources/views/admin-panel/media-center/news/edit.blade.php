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

@section('title',  __('adminlte::adminlte.newsAndActivities') . '-' . __('adminlte::adminlte.editNews') )

@section('content_header')
    <div class="row">
        <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a
                    href="{{ localizedRoute('news.index') }}">{{ __('adminlte::adminlte.newsAndActivities') }}</a></li>
            <li class="breadcrumb-item active">{{ __('adminlte::adminlte.editNews') }}</li>
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
    <form action="{{ localizedRoute('news.update', ['news'=>$post->id]) }}" method="POST" enctype="multipart/form-data">
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
                            <x-adminlte.form.input id="title_en" name="title_en" label-class="text-olive" value="{{ $post->postDetailOne->title_en }}"
                                label="{{ __('adminlte::adminlte.title(EN)') }}" enable-old-support />
                        </div>

                        <!-- DateTime Picker -->
                        @php
                            $config = [
                                'format' => 'L',
                                'defaultDate' => Carbon\Carbon::now()->format('m/d/Y'), // Corrected format for default date
                            ];
                        @endphp
                        <x-adminlte-input-date name="date" :config="$config" value="{{ $post->date }}"
                            placeholder="{{ __('adminlte::landingpage.choosedate') }}" label-class="text-olive"
                            label="{{ __('adminlte::adminlte.date') }}" required enable-old-support>
                            <x-slot name="prependSlot">
                                <div class="input-group-text bg-gradient-success">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input-date>

                    </div>

                    <!-- slug Column -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <x-adminlte-input name="slug" label="{{ __('adminlte::adminlte.slug(AR)') }}"
                            value="{{ $post->postDetailOne->slug }}"
                                placeholder="postal code" label-class="text-olive" enable-old-support>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-link"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="bottomSlot">
                                    <a href="#" data-toggle="modal"
                                        data-target="#modalMin">{{ __('adminlte::adminlte.whatItMeans') }}</a>
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <div class="form-group">
                            <x-adminlte-input name="slug_en" label="{{ __('adminlte::adminlte.slug(EN)') }}"
                            value="{{ $post->postDetailOne->slug_en }}"
                                placeholder="postal code" label-class="text-olive" enable-old-support>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-link"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="bottomSlot">
                                    <a href="#" data-toggle="modal"
                                        data-target="#modalMin">{{ __('adminlte::adminlte.whatItMeans') }}</a>
                                </x-slot>
                            </x-adminlte-input>
                        </div>



                        <div class="form-group">
                            <x-adminlte-select2 name="category_id" :config="['minimumResultsForSearch' => 'Infinity']"
                                label="{{ __('adminlte::adminlte.postType') }}" label-class="text-olive">

                                <option {{ $post->category_id==7? 'selected':'' }} value="7">{{ __('adminlte::landingpage.news') }}</option>
                                <option {{ $post->category_id==2? 'selected':'' }} value="2">{{ __('adminlte::landingpage.activities') }}</option>
                            </x-adminlte-select2>
                        </div>

                        <x-adminlte-modal tabindex="-1" id="modalMin" title="{{ __('adminlte::adminlte.slug') }}" theme="olive"
                            icon="fas fa-question" size='lg'>
                            <p class="bg-secondary text-white container p-3 my-3 border responsive-text"
                                style="direction: ltr; text-align: left;">
                                <span class="bg-dark text-white container m-2"> URL : </span>
                                <span class="responsive-text">
                                    https://www.ayccl.com/ar/News/{{ $post->id }}/{{ $post->postDetailOne->slug }}
                                </span>
                            </p>
                            <p class="responsive-text">
                                {{ __('adminlte::adminlte.slug') }}:
                                <span class="text-danger">{{ $post->postDetailOne->slug }}</span>
                            </p>
                            <p class="responsive-text">
                                {!! nl2br(__('adminlte::adminlte.slugInfo')) !!}
                            </p>
                        </x-adminlte-modal>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    {{-- content --}}
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
                                ['#fff', '#228B22', '#3cb371', '#2e8b57', '#006400', '#008000', '#32cd32', '#90ee90'],
                            ],
                        ];
                    @endphp
                    <div class="form-group col-12 col-md-6">
                        <x-adminlte-text-editor name="content_ar" label="{{ __('adminlte::adminlte.contentAR') }}"
                            label-class="text-olive" igroup-size="sm" placeholder="اكتب النص هنا ..." :config="$config"
                            enable-old-support >
                            {{ $post->postDetailOne->content }}
                        </x-adminlte-text-editor>
                    </div>
                    {{-- content EN --}}

                    <div class="form-group col-12 col-md-6">
                        <x-adminlte-text-editor name="content_en" label="{{ __('adminlte::adminlte.contentEN') }}"                            
                        value="{{ $post->postDetailOne->content_en }}"
                            label-class="text-olive" igroup-size="sm" placeholder="Write some text..." :config="$config"
                            enable-old-support >
                            {{ $post->postDetailOne->content_en }}
                        </x-adminlte-text-editor>
                    </div>
                </div>
                {{-- Attachments images upload --}}
                <div class="row">
                    <!-- File Upload -->
                    @php
                        $initialPreview = [];
                        $initialPreviewConfig = [];
                        $deleteUrl = [];
                        foreach ($post->media as $media) {
                            if ($media->filepath) {
                                // Build the URL to the image using Laravel's asset() helper
                                // 'storage/' is the correct path prefix for files on the public disk.
                                $previewUrl = asset($media->filepath);
                                // $deleteUrl = route('media.destroy', ['media' => $media->id]);
                                // dd($previewUrl);
                                // Add the image URL to the preview array
                                $initialPreview[] = $previewUrl . '';
                                $deleteUrl = localizedRoute('media.destroy', ['id' => $media->id]);
                                // Add the configuration for this specific image
                                // dd($deleteUrl);
                                $initialPreviewConfig[] = [
                                    'caption' => basename($media->filepath), // The filename for display
                                    'size' => Storage::disk('images')->exists($media->filepath) ? Storage::disk('images')->size($media->filepath) : 0, // File size in bytes
                                    'key' => $media->id, // A unique key for deletion
                                    'url' => localizedRoute('media.destroy', ['id' => $media->id]), // The URL to send the delete request to
                                    'extra' => [
                                        '_token' => csrf_token(),
                                        '_method' => 'DELETE',
                                        'field' => 'filepath'
                                    ],
                                ];
                            }
                        }
                        $config = [
                            'allowedFileTypes' => ['image'],
                            'browseOnZoneClick' => true,
                            'theme' => 'fa5',
                            'overwriteInitial' => false,
                            'initialPreviewAsData' => true,
                            'initialPreview' => $initialPreview, // -- Here is the initial value
                            'initialPreviewConfig' => $initialPreviewConfig,
                            'uploadUrl' => '#',
                            'uploadAsync' => false,
                            'deleteUrl' => localizedRoute('media.destroy', ['id' => 0]),
                            'initialPreviewShowDelete' => true,
                            'showRemove' => false,
                            'showUpload' => false,
                            'showClose' => false,
                            'fileActionSettings' => [
                                'showRemove' => true,
                                'showZoom' => true,
                                'showUpload' => false,
                                'showDrag'=> false,
                                'showRotate'=> false,
                            ],
                            'showCancel' => false,
                            'maxFileCount' => 10,
                        ];
                    @endphp
                    <div class="form-group">
                        {{-- <input type="file" name="files[]"  > --}}
                        <x-adminlte-input-file-krajee name="files" 
                            label="{{ __('adminlte::adminlte.attachmentsUpload') }}"
                            data-msg-placeholder="Choose a text, office or pdf file..." label-class="text-olive"
                            :config="$config" multiple >
                        </x-adminlte-input-file-krajee>
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
@section('plugins.KrajeeFileinput', true)
@section('plugins.Summernote', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true)

@section('adminlte_js')
@stop
