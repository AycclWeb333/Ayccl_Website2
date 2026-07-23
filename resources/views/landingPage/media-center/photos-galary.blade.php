@extends('layouts.app')

@section('css')
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mixitup/3.3.1/mixitup.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

@endsection

@section('jsbefore')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
@endsection

@section('content')
    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

    <div class="w-[95%] md:w-[90%] lg:w-[95%] mx-auto my-20">

        <div class="tabs tabs-boxed bg-base-200/90 w-fit space-x-2">
            <a class="tab bg-emerald-300 text-black font-bold" data-filter="all">{{ __('adminlte::landingpage.all') }}</a>
            @foreach ($categories as $category)
                <a class="tab bg-transparent text-black" data-filter=".{{ str_replace(' ', '_', $category->name) }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div id="photos-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-4 mt-6">
            @foreach ($posts as $post)
                @php $firstDetail = $post->postDetailOne @endphp

                <div class="mix {{ str_replace(' ', '_', $firstDetail->category->name) }} card bg-base-100 shadow-xl">
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
                        @isset($post->media)
                        @if (count($post->media) === 1)
                            <div class="relative w-full h-fit overflow-hidden rounded-t-lg group">
                                <img src="{{ asset($post->media[0]->filepath) }}" alt="{{ $post->media[0]->alt ?? '' }}"
                                    class="h-60 sm:h-60 object-cover w-full" loading="lazy" />
                                <div class="w-fit gallery-trigger-container absolute -bottom-5 sm:-bottom-5 
                                        {{ app()->getLocale() =='en' ? 'start-7 sm:start-7' : 'start-0 sm:start-0'   }}
                                            -translate-x-1/2 -translate-y-1/2 text-white rounded-4xl
                                            opacity-100 md:opacity-0 md:group-hover:opacity-100 
                                            transition-opacity duration-300 p-3 bg-black/20 hover:cursor-pointer"
                                    data-post-id="{{ $post->id }}">
                                    <i class="fas fa-expand resize-icon text-xl"></i>
                                </div>
                            </div>
                        @else
                            <div class="relative w-full overflow-hidden rounded-t-lg group">
                                <!-- Multi-image Badge -->
                                <div class="absolute top-3 end-3 z-20 bg-black/60 text-white text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1.5 shadow-md backdrop-blur-sm pointer-events-none">
                                    <i class="fas fa-images text-emerald-400"></i>
                                    <span>{{ count($post->media) }} {{ app()->getLocale() == 'ar' ? 'صور' : 'photos' }}</span>
                                </div>

                                <div id="post-carousel-{{ $post->id }}"
                                    class="owl-carousel relative w-full overflow-hidden">
                                    @foreach ($post->media as $img)
                                        <div class="item relative w-full overflow-hidden cursor-grabbing">
                                            <!-- Image -->
                                            <img src="{{ asset($img->filepath) }}" alt="{{ $img->alt ?? '' }}"
                                                class="h-60 sm:h-60 object-cover w-full" />

                                            <!-- Resize Icon -->
                                            <div class="w-fit gallery-trigger-container absolute -bottom-5  sm:-bottom-5 
                                            {{ app()->getLocale() =='en' ? 'start-7 sm:start-7' : 'start-0 sm:start-0'   }}
                                                -translate-x-1/2 -translate-y-1/2 text-white rounded-4xl
                                                opacity-100 md:opacity-0 md:group-hover:opacity-100 
                                                transition-opacity duration-300 p-3 bg-black/20 hover:cursor-pointer z-10"
                                                data-post-id="{{ $post->id }}">
                                                <i class="fas fa-expand resize-icon text-xl"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endisset

                        <div x-data="{ open: false }" class="card-body">
                            <h2 class="card-title justify-center text-lg sm:text-2xl text-emerald-700 mt-0">
                                {{ $firstDetail->title }}
                            </h2>
                            <h2 class="justify-center m-0 p-2 w-fit bg-emerald-800  rounded-2xl text-white font-bold">
                                {{ $firstDetail->category->name }}
                            </h2>
                            <!-- Album Details -->
                            <div class="space-y-2 font-bold">
                                <p>{!! nl2br($firstDetail->content) ?? __('adminlte::landingpage.nodescription') !!}</p>
                            </div>

                            <div class="flex justify-center-safe space-x-2 mt-2 fixed top-1/2 start-1/2">
                                <!-- View Button -->
                                {{-- <button class="btn btn-primary w-fit gallery-trigger-container" data-post-id="{{ $post->id }}">
                                         {{ __('adminlte::landingpage.viewmore') }}<i class="fas fa-expand resize-icon"></i>
                                </button> --}}
                                <!-- Resize Button (optional functionality) -->
                            </div>
                            <div id="hidden-gallery-{{ $post->id }}" class="hidden">
                                @isset($post->media)
                                @foreach ($post->media as $media)
                                    <a href="{{ asset($media->filepath) }}" title="{{ $media->filepath }}"
                                        data-date="{{ $media->created_at->format('M d, Y') }}"
                                        data-content="{{ $media->id }}">
                                    </a>
                                @endforeach
                                @endisset
                            </div>

                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('jsafter')
{{-- Mixit Up --}}
    <script>
        // MixItUp filter initialization
        var containerEl = document.querySelector('#photos-grid');
        var mixer = mixitup(containerEl, {
            selectors: {
                target: '.mix'
            },
            animation: {
                duration: 300
            },
            callbacks: {
                onMixEnd: function() {
                    $('.owl-carousel').each(function() {
                        $(this).trigger('refresh.owl.carousel');
                        $(this).trigger('play.owl.autoplay', [3000]);
                    });
                }
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

        // Helper function to reliably wait for images
        function waitForImagesGallery($imgs, timeoutMs = 3000) {
            return new Promise((resolve) => {
                if (!$imgs || !$imgs.length) return resolve({ loaded: 0, total: 0 });
                let total = $imgs.length;
                let loaded = 0;
                let resolved = false;

                const checkDone = () => {
                    if (!resolved && loaded >= total) {
                        resolved = true;
                        resolve();
                    }
                };

                $imgs.each(function () {
                    const img = this;
                    if (img.complete && img.naturalWidth > 0) {
                        loaded++;
                    } else {
                        $(img).one('load error', function () {
                            loaded++;
                            checkDone();
                        });
                    }
                });
                
                checkDone();
                setTimeout(() => { if (!resolved) { resolved = true; resolve(); } }, timeoutMs);
            });
        }

        $(document).ready(function () {
            function initGalleryCarousel($owl, $loader) {
                // Prevent duplicate initialization
                if ($owl.hasClass('owl-loaded')) {
                    $owl.trigger('destroy.owl.carousel');
                    $owl.removeClass('owl-loaded');
                    $owl.find('.owl-stage-outer').children().unwrap();
                    $owl.find('.owl-stage').children().unwrap();
                    $owl.find('.owl-item').children().unwrap();
                }

                $owl.owlCarousel({
                    loop: true,
                    autoplay: true,
                    autoplayTimeout: 3000,
                    autoplaySpeed: 800,
                    autoplayHoverPause: true,
                    rtl: document.documentElement.getAttribute('dir') === 'rtl',
                    items: 1,
                    nav: true,
                    dots: true,
                    smartSpeed: 800,
                    autoHeight: false,
                    responsiveRefreshRate: 100,
                    navText: [
                        '<button class="btn absolute top-1/3 rtl:right-0 ltr:left-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/30 hover:bg-black/50 text-white border-0 z-10">❮</button>',
                        '<button class="btn absolute top-1/3 rtl:left-0 ltr:right-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/30 hover:bg-black/50 text-white border-0 z-10">❯</button>'
                    ]
                });

                if ($loader && $loader.length) {
                    waitForImagesGallery($owl.find('img'), 2000).then(() => {
                        $loader.fadeOut(200);
                    });
                }
            }

            @foreach ($posts as $post)
            @isset($post->media)
                @if (count($post->media) > 1)
                    (function () {
                        const $owl = $('#post-carousel-{{ $post->id }}');
                        const $loader = $('#loader-{{ $post->id }}');

                        initGalleryCarousel($owl, $loader);

                        // Re-initialize on window resize to fix broken layout occasionally
                        let resizeTimer;
                        $(window).on('resize', function () {
                            clearTimeout(resizeTimer);
                            resizeTimer = setTimeout(function () {
                                initGalleryCarousel($owl, $loader);
                            }, 300);
                        });
                    })();
                @elseif (count($post->media) == 1)
                    // Single image loader
                    (function () {
                        const $loader = $('#loader-{{ $post->id }}');
                        const $img = $loader.closest('.card').find('img');

                        waitForImagesGallery($img, 2000).then(() => {
                            $loader.fadeOut(200);
                        });
                    })();
                @endif
                @endisset
            @endforeach
        });
    </script>

    {{-- Images Preview --}}
    <script>
        $(document).ready(function() {
            $('.gallery-trigger-container').on('click', function(e) {
                e.preventDefault();
                const postId = $(this).data('post-id');
                const galleryLinks = $(`#hidden-gallery-${postId} a`);

                const items = galleryLinks.map(function() {
                    return {
                        src: $(this).attr('href'),
                        //         data: {
                        //             title: $(this).attr('title'),
                        //             date: $(this).data('date'),
                        //             content: $(this).data('content')
                        //         }
                    };
                }).get();

                $.magnificPopup.open({
                    items: items,
                    gallery: {
                        enabled: true
                    },
                    type: 'image',
                    callbacks: {
                        open: function() {
                            // Get the first item's data to display on open
                            const currentItem = this.currItem.data;

                            // Create the HTML for the details panel
                            // const detailsHtml = `
                            // <div class="mfp-details-panel">
                            //     <h3 class="text-xl font-bold">${currentItem.title}</h3>
                        //         <p class="text-sm my-2">Date: ${currentItem.date}</p>
                        //         <p class="text-base">${currentItem.content}</p>
                        //     </div>
                        // `;

                            // Inject the details panel into the popup's container
                            this.container.append(detailsHtml);
                        },
                    }
                });
            });
        });
    </script>
@endsection
