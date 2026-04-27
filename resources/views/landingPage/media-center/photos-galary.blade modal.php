@extends('layouts.app')

@section('css')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mixitup/3.3.1/mixitup.min.js"></script>
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

        <div class="tabs tabs-boxed space-x-2">
            <a class="tab bg-emerald-300 text-black font-bold" data-filter="all">{{ __('adminlte::landingpage.all') }}</a>
            @foreach ($posts as $post)
                @foreach ($post->postDetail as $postDetail)
                    <a class="tab bg-transparent text-black"
                        data-filter=".{{ $postDetail->category->name }}">{{ $postDetail->category->name }}</a>
                @endforeach
            @endforeach
        </div>

        <div id="photos-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
            @foreach ($posts as $post)
                @php $firstDetail = $post->postDetail[0] @endphp
                <div class="mix {{ $firstDetail->category->name }} card bg-base-100 shadow-xl">

                    <div
                        class="card w-full h-full bg-base-100 shadow-md hover:shadow-2xl transition-all duration-200 p-2 relative">
                        <!-- Loader -->
                        <div id="loader-{{ $post->id }}"
                            class="absolute inset-0 flex justify-center items-center bg-white/70 z-50">
                            <svg class="animate-spin h-10 w-10 text-gray-700" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                        </div>

                        {{-- images numbers if one or multi --}}
                        @if (count($post->media) === 1)
                            <div class="relative w-full h-100 overflow-hidden rounded-t-lg">
                                <img src="{{ asset($post->media[0]->filepath) }}" alt="{{ $post->media[0]->alt ?? '' }}"
                                    class="h-100 object-cover w-full" />
                            </div>
                        @else
                            <div id="post-carousel-{{ $post->id }}"
                                class="owl-carousel relative w-full overflow-hidden rounded-t-lg">
                                @foreach ($post->media as $img)
                                    <div class="item w-full overflow-hidden cursor-grabbing">
                                        <img src="{{ asset($img->filepath) }}" alt="{{ $img->alt ?? '' }}"
                                            class="h-100 object-cover w-full" />
                                    </div>
                                @endforeach
                            </div>
                        @endif
                            
                        <div x-data="{ open: false }" class="card-body">
                            <h2 class="card-title justify-center lg:text-2xl text-emerald-700 mt-0">
                                {{ $firstDetail->title }}
                            </h2>
                            <h2 class="justify-center m-0 p-2 w-fit bg-emerald-800  rounded-2xl text-white font-bold">
                                {{ $firstDetail->category->name }}
                            </h2>
                            <!-- Album Details -->
                            <div class="space-y-2 font-bold">
                                <p>{!! nl2br($firstDetail->content) ?? __('adminlte::landingpage.nodescription') !!}</p>
                            </div>

                            <div class="flex justify-between space-x-2 mt-2">
                                <!-- View Button -->
                                <button @click="open = true" class="btn btn-primary w-1/2">View</button>
                                <!-- Resize Button (optional functionality) -->
                                <button class="btn btn-primary w-1/2">Resize</button>
                            </div>

                            <!-- Modal -->
                            <div x-show="open" x-transition.opacity
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                                <!-- Modal Container -->
                                <div @click.away="open = false" x-transition.scale
                                    class="bg-white rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-2/3 max-h-[90vh] overflow-y-auto relative p-4">

                                    <!-- Close Button -->
                                    <button @click="open = false"
                                        class="absolute top-2 end-2 cursor-pointer text-gray-700 hover:text-red-500 text-4xl font-bold">&times;</button>

                                    <!-- Modal Content -->
                                    <h3 class="text-xl font-bold text-emerald-700 mb-4">{{ $firstDetail->title }}</h3>

                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                        @if($post->mediaOne)
                                            <img src="{{ asset($post->mediaOne->filepath) }}" alt="{{ $post->mediaOne->alt ?? '' }}"
                                                class="w-full h-40 object-cover rounded-md">
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('jsafter')
    <script>
        // MixItUp filter initialization
        var containerEl = document.querySelector('#photos-grid');
        var mixer = mixitup(containerEl, {
            selectors: {
                target: '.mix'
            },
            animation: {
                duration: 300
            }
        });

        // Tabs active state
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(item => {
                        item.classList.remove('bg-emerald-900', 'text-white');
                        item.classList.add('bg-transparent', 'text-black');
                    });
                    this.classList.remove('bg-transparent', 'text-black');
                    this.classList.add('bg-emerald-900', 'text-white');
                });
            });
        });

        // OwlCarousel initialization
        @foreach ($posts as $post)
            @if (count($post->media) > 1)
                $(document).ready(function() {
                    var owl_{{ $post->id }} = $('#post-carousel-{{ $post->id }}');
                    var loader_{{ $post->id }} = $('#loader-{{ $post->id }}');

                    owl_{{ $post->id }}.owlCarousel({
                        loop: true,
                        autoplay: true,
                        autoplayTimeout: 5000,
                        autoplayHoverPause: true,
                        rtl: document.documentElement.getAttribute('dir') === 'rtl',
                        items: 1,
                        nav: true,
                        dots: false,
                        navText: [
                            '<button class="btn absolute top-1/3 right-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/30 hover:bg-black/50 text-white border-0">❮</button>',
                            '<button class="btn absolute top-1/3 left-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/30 hover:bg-black/50 text-white border-0">❯</button>'
                        ]
                    });

                    // Image loader
                    var totalImages = owl_{{ $post->id }}.find('img').length;
                    var imagesLoaded = 0;

                    function checkAllLoaded() {
                        if (imagesLoaded === totalImages) loader_{{ $post->id }}.fadeOut(300);
                    }

                    owl_{{ $post->id }}.find('img').each(function() {
                        const img = $(this);
                        if (img[0].complete && img[0].naturalHeight !== 0) {
                            imagesLoaded++;
                            checkAllLoaded();
                        } else {
                            img.on('load error', function() {
                                imagesLoaded++;
                                checkAllLoaded();
                            });
                        }
                    });

                    // Fallback
                    setTimeout(function() {
                        if (loader_{{ $post->id }}.is(':visible')) loader_{{ $post->id }}.fadeOut(
                            300);
                    }, 10000);
                });
            @else
                // Single image loader
                $(document).ready(function() {
                    var loader_{{ $post->id }} = $('#loader-{{ $post->id }}');
                    var img = $('img[src="{{ asset($post->media[0]->filepath) }}"]');
                    if (img[0].complete && img[0].naturalHeight !== 0) loader_{{ $post->id }}.fadeOut(300);
                    else img.on('load error', function() {
                        loader_{{ $post->id }}.fadeOut(300);
                    });
                    setTimeout(function() {
                        if (loader_{{ $post->id }}.is(':visible')) loader_{{ $post->id }}.fadeOut(
                            300);
                    }, 5000);
                });
            @endif
        @endforeach
    </script>
@endsection
