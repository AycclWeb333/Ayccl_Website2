@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
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
        
                <div class="breadcrumbs text-lg mb-5 ">
                    @php
                        $parentRoutes = [
                            51 => ['route' => 'newsAndActivities', 'label' => 'newsAndActivities'],
                            54 => ['route' => 'documents', 'label' => 'documents'],
                            55 => ['route' => 'inspectionCertificates', 'label' => 'inspectionCertificates'],
                            56 => ['route' => 'specifications', 'label' => 'specifications'],
                        ];
                        $parent = $parentRoutes[$post->page_id] ?? $parentRoutes[51];
                    @endphp
                    <ul>
                        <li class="hover:text-emerald-600 underline underline-offset-8">
                            <a href="{{ localizedRoute($parent['route']) }}">
                                {{ __("adminlte::landingpage.{$parent['label']}") }}
                            </a>
                        </li>
                        <li></li>
                    </ul>
                </div>

        <h2 class="card-title text-4xl mb-5"> <a class="text-emerald-700"> {{ $post->postDetail[0]->title }}</a> </h2>
        <time datetime="{{ \Carbon\Carbon::parse($post->date)->toIso8601String() }}" class="text-lg text-gray-600">
            {{ \Carbon\Carbon::parse($post->date)->format('M d, Y') }}
        </time>
        <article>

            <div
                class="float-end w-full lg:w-[50%] m-5 h-full mx-auto">
                <!-- Loader -->
                <div id="loader-{{ $post->id }}"
                    class="absolute inset-0 flex justify-center items-center  bg-white/70 z-50">
                    <svg class="animate-spin h-10 w-10 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                </div>

                @if (count($post->media) == 1)
                    <!-- Single Image -->
                    <div class="relative w-full lg:mx-5 h-100 overflow-hidden rounded-t-lg">
                        <img src="{{ asset($post->media[0]->filepath) }}" alt="{{ $post->media[0]->alt ?? '' }}"
                            class="h-100 object-cover w-full rounded-2xl" />
                    </div>
                @else
                    <div class="relative w-full h-full lg:mx-5">
                        <!-- OwlCarousel for multiple images -->
                        <div id="post-carousel-{{ $post->id }}"
                            class="owl-carousel relative w-full overflow-hidden rounded-t-lg">
                            @foreach ($post->media as $img)
                                <div class="item cursor-grabbing w-full overflow-hidden">
                                    <img src="{{ asset($img->filepath) }}" alt="{{ $img->alt ?? '' }}"
                                        class="h-100 object-cover rounded-2xl " />
                                </div>
                            @endforeach
                        </div>
                        <div id="owl_{{ $post->id }}-counter"
                            class="absolute bottom-2 end-2 z-10 bg-black/50 text-white text-xs px-2 py-1 rounded">
                            1 / {{ count($post->media) }}
                        </div>
                    </div>
                @endif


            </div>
            <!-- Card Body -->
            <div>
                <p class="text-black text-lg mx-2 sm:mx-0 text-justify">
                    {!! ($post->postDetail[0]->content) !!}
                </p>
            </div>
        </article>
        <!-- Init Script - Only for multiple images -->
        @if (count($post->media) > 1)
            <script>
                (function() {
                    // Create unique variables for each post
                    var owl_{{ $post->id }} = jQuery('#post-carousel-{{ $post->id }}');
                    const loader_{{ $post->id }} = $('#loader-{{ $post->id }}');
                    var carouselId = '#owl_{{ $post->id }}';
                    var $carousel = $(carouselId);

                    owl_{{ $post->id }}.owlCarousel({
                        loop: true,
                        // margin: 20,
                        autoplay: true,
                        autoplayTimeout: 5000,
                        autoplayHoverPause: true,
                        rtl: document.documentElement.getAttribute('dir') === 'rtl', // ← fixed comma here
                        items: 1,
                        nav: true,
                        dots: false,
                        navText: [
                            '<button class="btn absolute top-1/3 sm:top-1/4 right-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/15 hover:bg-black/50 font-bold text-white border-0">❮</button>',
                            '<button class="btn absolute top-1/3 sm:top-1/4 left-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/15 hover:bg-black/50 font-bold text-white border-0">❯</button>'
                        ],
                        onInitialized: updateCounter,
                        onChanged: updateCounter
                    });

                    function updateCounter(event) {
                        var total = event.item.count; // total slides
                        var current = event.item.index - event.relatedTarget._clones.length / 2;

                        // Fix negative or overflow values due to clones
                        if (current < 0) current = total + current;
                        if (current >= total) current = current - total;

                        current = current + 1; // make it 1-based

                        $(carouselId + '-counter').text(current + ' / ' + total);
                    }

                    // Image loading logic
                    let totalImages_{{ $post->id }} = owl_{{ $post->id }}.find('img').length;
                    let imagesLoaded_{{ $post->id }} = 0;

                    // Function to check if all images are loaded
                    function checkAllImagesLoaded_{{ $post->id }}() {
                        if (imagesLoaded_{{ $post->id }} === totalImages_{{ $post->id }}) {
                            loader_{{ $post->id }}.fadeOut(300);
                        }
                    }

                    // Check each image
                    owl_{{ $post->id }}.find('img').each(function() {
                        const img = $(this);

                        if (img[0].complete && img[0].naturalHeight !== 0) {
                            // Image is already loaded
                            imagesLoaded_{{ $post->id }}++;
                            checkAllImagesLoaded_{{ $post->id }}();
                        } else {
                            // Image is still loading
                            img.on('load', function() {
                                imagesLoaded_{{ $post->id }}++;
                                checkAllImagesLoaded_{{ $post->id }}();
                            }).on('error', function() {
                                // Handle image load errors
                                imagesLoaded_{{ $post->id }}++;
                                checkAllImagesLoaded_{{ $post->id }}();
                            });
                        }
                    });

                    // Fallback: hide loader after a reasonable timeout if images don't load
                    setTimeout(function() {
                        if (loader_{{ $post->id }}.is(':visible')) {
                            loader_{{ $post->id }}.fadeOut(300);
                        }
                    }, 10000); // 10 second timeout
                })();
            </script>
        @else
            <!-- Simple image loader for single image -->
            <script>
                (function() {
                    const loader_{{ $post->id }} = $('#loader-{{ $post->id }}');
                    const img = $('img[src="{{ asset($post->media[0]->filepath) }}"]');

                    if (img[0].complete && img[0].naturalHeight !== 0) {
                        // Image is already loaded
                        loader_{{ $post->id }}.fadeOut(300);
                    } else {
                        // Image is still loading
                        img.on('load', function() {
                            loader_{{ $post->id }}.fadeOut(300);
                        }).on('error', function() {
                            // Handle image load errors
                            loader_{{ $post->id }}.fadeOut(300);
                        });
                    }

                    // Fallback: hide loader after a reasonable timeout
                    setTimeout(function() {
                        if (loader_{{ $post->id }}.is(':visible')) {
                            loader_{{ $post->id }}.fadeOut(300);
                        }
                    }, 5000); // 5 second timeout for single image
                })();
            </script>
        @endif

    </div>
@endsection
@section('jsafter')
@endsection
