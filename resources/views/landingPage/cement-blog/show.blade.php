@extends('layouts.app')
@section('css')
@endsection
@section('content')

    <x-hero title="{{ $post->postDetailOne->title }}" description="" img="{{ asset($page->background) }}" />

    <div class="w-[90%] md:w-[80%] mx-auto mt-12 mb-24">
        <div class="bg-white shadow-2xl rounded-4xl overflow-hidden" data-aos="fade-up">
            
            @if($post->media->count() > 0)
                <div class="w-full">
                    {{-- If multiple images, we could use a slider, but for now show the first one prominently --}}
                    <img src="{{ asset($post->media->first()->filepath) }}" 
                         alt="{{ $post->media->first()->alt }}" 
                         class="w-full h-auto max-h-[600px] object-contain bg-gray-50" />
                </div>
            @endif

            <div class="p-8 md:p-16 space-y-8">
                <h1 class="text-3xl md:text-5xl font-bold text-green-900 leading-tight">
                    {{ $post->postDetailOne->title }}
                </h1>

                <div class="prose prose-lg max-w-none text-gray-700">
                    {!! $post->postDetailOne->content !!}
                </div>

                @if($post->media->count() > 1)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-12 pt-12 border-t border-gray-100">
                        @foreach($post->media->skip(1) as $media)
                            <div class="aspect-square overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300">
                                <img src="{{ asset($media->filepath) }}" 
                                     alt="{{ $media->alt }}" 
                                     class="w-full h-full object-cover" />
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-12 pt-8 border-t border-gray-100 flex justify-between items-center">
                    <a href="{{ localizedRoute('cementBlog') }}" class="text-green-700 hover:text-green-900 font-bold flex items-center gap-2 transition-colors">
                        <i class="fas fa-arrow-right"></i>
                        {{ __('adminlte::adminlte.back') ?? 'Back to Blog' }}
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('jsafter')
@endsection
