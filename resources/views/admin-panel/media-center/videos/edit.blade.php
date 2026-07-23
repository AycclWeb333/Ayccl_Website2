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

@section('title', __('adminlte::menu.videos') .' - '. __('adminlte::adminlte.editNews'))

@section('content_header')
    <div class="row">
        <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a
                    href="{{ localizedRoute('videos.index') }}">{{ __('adminlte::menu.videos') }}</a></li>
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
    <form action="{{ localizedRoute('videos.update', ['video'=>$post->id]) }}" method="POST" enctype="multipart/form-data">
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
                         {{-- select Category --}}
                         <div class="form-group">
                            <x-adminlte-select2 name="category_id" :config="['minimumResultsForSearch' => 'Infinity']"
                                label="{{ __('adminlte::adminlte.category') }}" label-class="text-olive">
                                @foreach ($categories as $category)
                                    <option {{ $category->id == $post->category_id ? 'selected' : '' }} value="{{ $category->id }}">
                                        {{ $category->name }}</option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>


                        <!-- video Link Column -->
                        <div class="form-group">
                                <x-adminlte-input  onchange="document.getElementById('get-thumbnail').click();" id="video-url" name="videoLink" data-toggle="tooltip" title="{{ __('adminlte::adminlte.videoThumbnail') }}"
                                    label="{{ __('adminlte::adminlte.videoLink') }}"
                                    value="https://www.youtube.com/watch?v={{ $post->mediaOne->filepath }}" style="direction: ltr"
                                    label-class="text-olive" enable-old-support>
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text">
                                            <i class="fas fa-link"></i>
                                        </div>
                                    </x-slot>
                                    <x-slot name="prependSlot">
                                        <x-adminlte-button id="get-thumbnail" theme="outline-danger" icon="fas fa-play" 
                                        data-toggle="tooltip" title="{{ __('adminlte::adminlte.videoThumbnailShow') }}"/>
                                    </x-slot>
                                </x-adminlte-input>
                            
                            <div id="message-box" class="message-box"></div>

                            <div id="thumbnail-container" class="d-flex justify-content-center align-items-center">
                                <img id="thumbnail-image" src="{{ $post->mediaOne->thumbnailpath }}" height="100" width="150">
                            </div>
                        </div>
                        </div>
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

                    <div class="form-group col-12 col-md-6" style="text-align: left; direction: ltr;  text-align: left;" >
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
                    <div class="text-center col-12">
                        <button class="btn btn-success p-2 col-12 col-md-6 " type="submit">
                            {{ __('adminlte::adminlte.save') }}
                        </button>
                    </div>
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
    document.getElementById('get-thumbnail').addEventListener('click', function() {
        const urlInput = document.getElementById('video-url');
        const url = urlInput.value;
        const messageBox = document.getElementById('message-box');
        const thumbnailContainer = document.getElementById('thumbnail-container');
        const thumbnailImage = document.getElementById('thumbnail-image');

        // Hide previous messages and images
        messageBox.style.display = 'none';
        thumbnailContainer.style.display = 'none';

        // Function to show a message
        function showMessage(message) {
            messageBox.textContent = message;
            messageBox.style.display = 'block';
        }

        // Function to validate URL and extract video ID
        function getVideoId(url) {
            if (!url) return null;
            url = url.trim();
            const regExp = /^.*(?:youtu\.be\/|v\/|u\/\w\/|embed\/|shorts\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);
            if (match && match[1].length === 11) {
                return match[1];
            }
            if (url.length === 11) {
                return url;
            }
            return null;
        }

        try {
            const videoId = getVideoId(url);

            if (videoId) {
                const thumbnailUrl = `https://img.youtube.com/vi/${videoId}/hqdefault.jpg`;
                thumbnailImage.src = thumbnailUrl;
                thumbnailImage.alt = 'YouTube Video Thumbnail';
                thumbnailContainer.style.display = 'block';
            } else {
                const thumbnailUrl = `https://img.youtube.com/vi//hqdefault.jpg`;
                thumbnailImage.src = thumbnailUrl;
                showMessage('Invalid YouTube URL.');
            }
        } catch (error) {
            const thumbnailUrl = `https://img.youtube.com/vi//hqdefault.jpg`;
            thumbnailImage.src = thumbnailUrl;
            showMessage('Please enter a complete YouTube video URL.');
        }
    });
</script>
@stop
