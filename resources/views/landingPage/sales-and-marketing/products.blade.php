@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

    <div class=" mx-auto mt-20  ">

        <div class="space-y-30"  data-aos="fade-up" data-aos-duration="700">
            @foreach ($posts as $index => $post)
                @php
                    $isEven = $index % 2 === 0;
                    $dir = session('locale') == 'ar' ? 1 : 2;
                    $dir = ($dir + $isEven) % 2 == 0 ? 1 : 0;
                @endphp

                <div class="card sm:card-side bg-base-100 shadow-lg m-10 lg:w-[90%] justify-center mx-auto">

                    @if (isset($post->mediaOne->filepath))
                        <figure class="lg:w-[40%] sm:w-[50%] relative order-1 {{ $isEven ? 'sm:order-2' : 'sm:order-1' }}"
                            style="background-size:cover; background-position: center;;background-image: url({{ asset('images/backgrounds/gggyrate.svg') }});">
                            <img class="h-72 w-72 sm:w-96 sm:h-96" src="{{ asset($post->mediaOne->filepath) }}"
                                alt="{{ $post->mediaOne->alt }}" />
                        </figure>
                    @endif

                    <div
                        class="card-body my-auto space-y-5 w-full lg:w-[60%] sm:w-[50%] {{ $isEven ? 'order-1' : 'order-2' }}">
                        <h2 class="card-title text-xl xl:text-3xl text-green-900 text-center">{{ $post->postDetailOne->title }}</h2>
                        {{-- <p class="text-md xl:text-lg font-bold space-y-2">
                            {!! html_entity_decode($post->description) !!}
                        </p>     --}}
                        <ol class="text-md xl:text-lg space-y-4 font-bold">
                            {!! html_entity_decode($post->postDetailOne->content) !!}
                        </ol>
                        @isset($post->mediaOne->link)
                            <a href="{{ asset($post->mediaOne->link) }}" download
                                class="btn btn-primary hover:text-white w-min-2/5 w-max-full mx-auto flex ">
                                <x-heroicon-c-document-arrow-down class="w-10 h-10" />
                                {{ __('adminlte::landingpage.downloadSpecsFile') }}
                            </a>
                            {{-- <a href="{{ localizedRoute('view.pdf', basename($post->mediaOne->link)) }}"  
                            class="btn btn-primary w-fit mx-auto" target="_blank" >
                            view
                        </a> --}}
                        @endisset

                    </div>
                </div>
            @endforeach
        </div>
        {{-- <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3482.345842888201!2d44.1887373!3d14.498425!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x16027c6225b42d1f%3A0x6a2b5e2a2c6d48c8!2sIbb%20City!5e0!3m2!1sen!2sye!4v1628287515152!5m2!1sen!2sye"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe> --}}
        {{-- <iframe 
            src="{{ localizedRoute('view.pdf', ['filename' => basename($posts[0]->mediaOne->link)]) }}"
            width="600" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
        </iframe> --}}
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
