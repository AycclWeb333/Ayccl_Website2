@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <style>
        .owl-nav {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            /* let clicks pass except on buttons */
        }

        .owl-nav button {
            pointer-events: auto;
            /* make buttons clickable again */
        }
    </style>
@endsection
@section('jsbefore')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
@endsection
@section('content')
    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

    <div class="w-[95%] md:w-[90%] lg:w-[95%] mx-auto my-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-8 lg:gap-4">
            @foreach ($posts as $post)
                <x-image-slides :post="$post">
                    @if(isset($post->mediaOne->id))
                        <a href="{{ route('media.download', ['locale' => app()->getLocale(), 'id' => $post->mediaOne->id]) }}"
                            class="text-white font-bold btn bg-brand-green hover:bg-brand-green-dark border-none flex-1 min-w-[120px]">
                            <i class="fas fa-download mr-1"></i> {{ __('adminlte::adminlte.attachments') }}
                        </a>
                    @endif
                </x-image-slides>
            @endforeach
        </div>
    </div>
@endsection
