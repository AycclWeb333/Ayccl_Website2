@extends('layouts.app')

@section('css')
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


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6 ">
            @foreach ($posts as $post)
                @php $firstDetail = $post->postDetailOne @endphp

                <div class="card bg-base-100 shadow-xl">
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
                                        class="h-60 sm:h-60 object-cover w-full" />
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
                                <div id="post-carousel-{{ $post->id }}"
                                    class="owl-carousel relative w-full overflow-hidden rounded-t-lg">
                                    @foreach ($post->media as $img)
                                        <div class="item relative w-full overflow-hidden cursor-grabbing group">
                                            <!-- Image -->
                                            <img src="{{ asset($img->filepath) }}" alt="{{ $img->alt ?? '' }}"
                                                class="h-60 sm:h-60 object-cover w-full" />

                                            <!-- Resize Icon -->
                                            <div class="w-fit gallery-trigger-container absolute -bottom-5  sm:-bottom-5 
                                            {{ app()->getLocale() =='en' ? 'start-7 sm:start-7' : 'start-0 sm:start-0'   }}
                                                -translate-x-1/2 -translate-y-1/2 text-white rounded-4xl
                                                opacity-100 md:opacity-0 md:group-hover:opacity-100 
                                                transition-opacity duration-300 p-3 bg-black/20 hover:cursor-pointer"
                                                data-post-id="{{ $post->id }}">
                                                <i class="fas fa-expand resize-icon text-xl"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endisset

                        <div x-data="{ open: false }" class="card-body">
                            <h2 class="card-title justify-start text-lg sm:text-2xl text-emerald-700 mt-0">
                                {{ $firstDetail->title }}
                            </h2>
                            {{-- <h2 class="justify-center m-0 p-2 w-fit bg-emerald-800  rounded-2xl text-white font-bold">
                                {{ $firstDetail->category->name }}
                            </h2> --}}
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
<script>
    function waitForImages($imgs, timeoutMs = 5000) {
        return new Promise((resolve) => {
            if (!$imgs || !$imgs.length) return resolve();

            let total = $imgs.length;
            let loaded = 0;
            let resolved = false;
            let timer;

            const done = () => {
                if (!resolved && loaded >= total) {
                    resolved = true;
                    if (timer) clearTimeout(timer);
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
                        done();
                    });
                }
            });

            // تفقد الحالة فوراً للصور المخزنة
            done();

            timer = setTimeout(() => {
                if (!resolved) {
                    resolved = true;
                    resolve();
                }
            }, timeoutMs);
        });
    }

    $(document).ready(function () {
        @foreach ($posts as $post)
            @if(isset($post->media) && count($post->media) > 1)
                (function() {
                    const $owl = $('#post-carousel-{{ $post->id }}');
                    const $loader = $('#loader-{{ $post->id }}');

                    // ربط الأحداث قبل التهيئة لضمان عدم ضياعها
                    $owl.on('initialized.owl.carousel refreshed.owl.carousel', function () {
                        setTimeout(() => {
                            waitForImages($owl.find('img'), 5000).then(() => {
                                $loader.fadeOut(300);
                            });
                        }, 100);
                    });

                    // Initialize Owl
                    $owl.owlCarousel({
                        loop: true,
                        autoplay: true,
                        autoplayTimeout: 5000,
                        autoplayHoverPause: true,
                        rtl: document.documentElement.getAttribute('dir') === 'rtl',
                        items: 1,
                        nav: true,
                        dots: false,
                        navText: [
                            '<button class="btn absolute top-1/3 ltr:left-0 rtl:right-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/30 hover:bg-black/50 text-white border-0">❮</button>',
                            '<button class="btn absolute top-1/3 ltr:right-0 rtl:left-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/30 hover:bg-black/50 text-white border-0">❯</button>'
                        ]
                    });
                })();
            @elseif(isset($post->media) && count($post->media) === 1)
                (function() {
                    const $loader = $('#loader-{{ $post->id }}');
                    const $img = $loader.closest('.card').find('img');

                    waitForImages($img, 3000).then(() => {
                        $loader.fadeOut(300);
                    });
                })();
            @endif
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
