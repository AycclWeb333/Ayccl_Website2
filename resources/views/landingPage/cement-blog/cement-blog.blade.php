@extends('layouts.app')
@section('css')
@endsection
@section('content')

    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

     <div class="w-[95%] mx-auto mt-20">
    @isset($cementBlogs)
        <div class="space-y-30">
            @foreach ($cementBlogs as $index => $post)
                @php
                    $isEven = $index % 2 === 0;
                @endphp

                <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-16 my-10 shadow-2xl p-6 lg:p-10 py-20 rounded-4xl {{ $isEven ? 'lg:flex-row-reverse' : 'lg:flex-row' }}" data-aos="fade-up" data-aos-duration="400">

                    @if (isset($post->mediaOne->filepath))
                        <div class="w-full lg:w-1/2 flex justify-center items-center">
                            <div class="relative inline-block group">

                                <div class="relative z-10 overflow-hidden shadow-lg" style="box-shadow: -20px -18px 4px 1px #2d843d; border-radius: 0px;">
                                    <img src="{{ asset($post->mediaOne->filepath) }}"
                                         alt="{{ $post->mediaOne->alt }}"
                                         class="w-full h-80 sm:h-[400px] object-cover block" />
                                </div>
                            </div>
                        </div>


                    @endif

                    <div class="w-full lg:w-1/2 lg:text-start space-y-6">
                        <h2 class="card-title text-2xl xl:text-5xl text-green-900 text-center">
                            {{ $post->postDetailOne->title }}
                        </h2>
                        <div class="text-md xl:text-xl space-y-4 text-gray-700">
                            {!! Str::limit(strip_tags($post->postDetailOne->content), 300) !!}
                        </div>
                        <div class="flex justify-center lg:justify-start">
                            <a href="{{ localizedRoute('cementBlog.show', ['id' => $post->id]) }}" 
                               class="bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-6 rounded-full transition duration-300">
                                {{ __('adminlte::adminlte.showmore') }}
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endisset
</div>

@endsection
@section('jsafter')
@endsection
