@extends('layouts.app')
@section('css')
@endsection
@section('content')

<x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

    {{-- hrer content --}}
<div class="w-[95%] mx-auto mt-20">
    @isset($posts)
        <div class="space-y-30">
            @foreach ($posts as $index => $post)
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
                            {!! $post->postDetailOne->content !!}
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endisset
</div>

@endsection
@section('jsafter')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Find all images in the page
        const images = document.querySelectorAll('img');
        let loadedCount = 0;

        function refreshAOS() {
            if (typeof AOS !== 'undefined') {
                AOS.refresh();
            }
        }

        if (images.length === 0) {
            refreshAOS();
        } else {
            images.forEach(img => {
                if (img.complete) {
                    loadedCount++;
                    if (loadedCount === images.length) refreshAOS();
                } else {
                    img.addEventListener('load', () => {
                        loadedCount++;
                        if (loadedCount === images.length) refreshAOS();
                    });
                    img.addEventListener('error', () => {
                        loadedCount++;
                        if (loadedCount === images.length) refreshAOS();
                    });
                }
            });
        }

        // Fallback: forcefully refresh AOS multiple times during the first 3 seconds
        let attempts = 0;
        let interval = setInterval(() => {
            refreshAOS();
            attempts++;
            if(attempts > 6) clearInterval(interval);
        }, 500);
    });
</script>
@endsection
