@extends('adminlte::page')

@php
    $route = 'external-links';
    $id = 'external_link';
    $pageName = 'menu.externalLinks';
@endphp

@section('title', __("adminlte::$pageName") . ' - ' . __('adminlte::adminlte.editNews'))
@section('content_header')
    <div class="row">
        <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a
                    href="{{ localizedRoute("$route.index") }}">{{ __("adminlte::$pageName") }}</a></li>
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
    <form action="{{ localizedRoute("$route.update", ["$id" => $post->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card card-default">
            <div class="card-body">
                <div class="row">
                    <!-- title Column -->
                    <div class="col-12 col-md-6">
                        <div class="form-group" style="direction: rtl;" >
                            <x-adminlte.form.input id="title" name="title" value="{{ $post->postDetailOne->title }}"
                                label="{{ __('adminlte::adminlte.title(AR)') }}" label-class="text-olive" enable-old-support
                                required />
                        </div>
                            
                        <input type="hidden" name="category_id" value="{{ $post->category_id }}">

                        <!-- DateTime Picker -->
                        {{-- @php
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
                        </x-adminlte-input-date> --}}
                        <div class="form-group " style="direction: ltr;" >
                            <x-adminlte.form.input id="title_en" name="title_en" label-class="text-olive" value="{{ $post->postDetailOne->title_en }}"
                                label="{{ __('adminlte::adminlte.title(EN)') }}" enable-old-support />
                        </div>
                    </div>


                    <!-- Left Column -->
                    <div class="col-12 col-md-6">
                        
                                                    
                        <div class="form-group"  style="direction: ltr;" >
                            <x-adminlte-textarea id="value" name="value" label-class="text-olive" igroup-size="md" rows=6
                                label="{{ __('adminlte::adminlte.value') }}" enable-old-support >
                                {{ $post->mediaOne->link??$post->postDetailOne->content }}
                                
                            @if ($post->category_id == 13)
                                <x-slot name="bottomSlot">
                                    <a href="#" data-toggle="modal"
                                        data-target="#modalMin">{{ __('adminlte::adminlte.howtoGetIt') }}</a>
                                </x-slot>         
                            @endif    
                            
                            </x-adminlte.form.textarea>

                        </div>
                        <x-adminlte-modal tabindex="-1" id="modalMin" title="{{ __('adminlte::adminlte.howtoGetIt') }}" theme="olive"
                        icon="fas fa-question" size='lg'>
                        {{-- <p class="bg-secondary text-white container p-3 my-3 border responsive-text"
                            style="direction: ltr; text-align: left;">
                            <span class="bg-dark text-white container m-2"> URL : </span>
                            <span class="responsive-text">
                                https://www.ayccl.com/ar/News/36/افتتاح-وتدشين-المسار-الجديد-لطريق-عقبة-عبدالله-غريب
                            </span>
                        </p> --}}
                        @php
                        $text = "";
                        $images = null;
                        $link = null;
                            if($post->order == 1){
                                $text = __('adminlte::adminlte.getMap');
                                $images = ['images/settings/share-button.png','images/settings/src-embeded-link.png'];
                            }
                            elseif($post->order == 2){
                                $text = __('adminlte::adminlte.getFaceBookPlugin');
                                $images = ['images/settings/facebook-plugin.png','images/settings/facebook-plugin2.png'];
                                $link = "https://developers.facebook.com/docs/plugins/page-plugin";        
                            }
                            elseif($post->order == 3){
                                $text = __('adminlte::adminlte.getYouTubeId');
                                $images = ['images/settings/get-channel-id.png'];        
                            }
                            elseif($post->order == 4){
                                $text = __('adminlte::adminlte.getInstaPost');
                            }
                            elseif($post->order == 5){
                                $text = __('adminlte::adminlte.getTwitterPost');
                            }

                        @endphp
                        <p class="responsive-text ">
                            {!! nl2br($text) !!}
                            @isset($link)
                                <a href="{{ $link }}">{{ $link }}</a>
                            @endisset    
                        </p>
                        <div class="mb-3">
                            @isset($images)
                                @foreach ($images as $image )
                                    <img class="img-fluid d-block mb-3" src="{{ asset($image ) }}" >
                                @endforeach
                            @endisset
                            {{-- <img class="img-fluid d-block" src="{{ asset($images[1]) }}"> --}}
                        </div>
                        
                    </x-adminlte-modal>



                        {{-- <div class="form-group">
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
                        </div> --}}


                        {{-- 
                        <div class="form-group">
                            <x-adminlte-select2 name="category_id" :config="['minimumResultsForSearch' => 'Infinity']"
                                label="{{ __('adminlte::adminlte.postType') }}" label-class="text-olive">
                                @foreach ($categories as $category)
                                <option {{ $post->postDetailOne->category_id==$category->id?'selected':'' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </x-adminlte-select2>
                        </div> --}}

                        {{-- <x-adminlte-modal tabindex="-1" id="modalMin" title="{{ __('adminlte::adminlte.slug') }}" theme="olive"
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
                        </x-adminlte-modal> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                {{-- Attachments images upload --}}
                    {{-- Attachments images upload --}}
                @if ($post->category_id != 13)
                <div class="row">
                    
                    <!-- File Upload -->
                    @php
                    $initialPreview = [];
                    $initialPreviewConfig = [];
                    $deleteUrl = [];
                    foreach ($post->media as $media) {
                        // Build the URL to the image using Laravel's asset() helper
                        // 'storage/' is the correct path prefix for files on the public disk.
                        $previewUrl = asset($media->filepath);
                        // $deleteUrl = route('media.destroy', ['media' => $media->id]);
                        // dd($previewUrl);
                        // Add the image URL to the preview array
                        $initialPreview[] = $previewUrl . '';
                        // Add the configuration for this specific image
                        // dd($deleteUrl);
                        $initialPreviewConfig[] = [
                            'caption' => basename($media->filepath), // The filename for display
                            'size' => Storage::disk('images')->exists($media->filepath) ? Storage::disk('images')->size($media->filepath) : 0, // File size in bytes
                        ];
                        }
                        $config = [
                            'allowedFileTypes' => ['image'],
                            'browseOnZoneClick' => true,
                            'theme' => 'fa5',
                            'overwriteInitial' => true,
                            'initialPreviewAsData' => true,
                            'initialPreview' => $initialPreview, // -- Here is the initial value
                            'initialPreviewConfig' => $initialPreviewConfig,
                            'uploadUrl' => '#',
                            'uploadAsync' => false,
                            'showRemove' => false,
                            'showUpload' => false,
                            'showClose' => false,
                            'initialPreviewShowDelete' => false,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showZoom' => true,
                                'showUpload' => false,
                                'showDrag' => false,
                                'showRotate' => false,
                            ],
                            'showCancel' => false,
                            // 'maxFileCount' => 5,
                        ];
                @endphp
                <div class="form-group col-12 col-md-6">
                    <x-adminlte-input-file-krajee name="files"
                        label="{{ __('adminlte::adminlte.attachmentsUpload') . ' (' . __('adminlte::adminlte.image') . ')' }}"
                        data-msg-placeholder="Choose a text, office or pdf file..." label-class="text-olive"
                        :config="$config" />
                </div>
                </div>
                @endif
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
