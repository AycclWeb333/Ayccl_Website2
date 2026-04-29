<!-- Magnific Popup CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" />

<section class="my-16" data-aos="fade-up">
    <x-divider>{{ app()->getLocale() == 'ar' ? 'اسمنت حضرموت 100%' : '100% Hadhramaut Cement' }}</x-divider>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
        @foreach ($hadramiProjects as $post)
            @php $firstDetail = $post->postDetailOne @endphp

            <div class="card bg-base-100 shadow-xl">
                <div class="card w-full h-full bg-base-100 shadow-md hover:shadow-2xl transition-all duration-200 p-2 relative">
                    <!-- Loader -->
                    <div id="home-loader-{{ $post->id }}"
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
                                <div class="w-fit gallery-trigger-container-home absolute -bottom-5 sm:-bottom-5 
                                        {{ app()->getLocale() =='en' ? 'start-7 sm:start-7' : 'start-0 sm:start-0'   }}
                                            -translate-x-1/2 -translate-y-1/2 text-white rounded-4xl
                                            opacity-100 md:opacity-0 md:group-hover:opacity-100 
                                            transition-opacity duration-300 p-3 bg-black/20 hover:cursor-pointer"
                                    data-post-id="{{ $post->id }}">
                                    <i class="fas fa-expand resize-icon text-xl"></i>
                                </div>
                            </div>
                        @else
                            <div id="home-post-carousel-{{ $post->id }}"
                                class="owl-carousel relative w-full overflow-hidden rounded-t-lg">
                                @foreach ($post->media as $img)
                                    <div class="item relative w-full overflow-hidden cursor-grabbing group">
                                        <!-- Image -->
                                        <img src="{{ asset($img->filepath) }}" alt="{{ $img->alt ?? '' }}"
                                            class="h-60 sm:h-60 object-cover w-full" />

                                        <!-- Resize Icon -->
                                        <div class="w-fit gallery-trigger-container-home absolute -bottom-5  sm:-bottom-5 
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
                        <!-- Album Details -->
                        <div class="space-y-2 font-bold line-clamp-3 overflow-hidden">
                            <p>{!! nl2br($firstDetail->content) ?? __('adminlte::landingpage.nodescription') !!}</p>
                        </div>

                        <div id="home-hidden-gallery-{{ $post->id }}" class="hidden">
                            @isset($post->media)
                                @foreach ($post->media as $media)
                                    <a href="{{ asset($media->filepath) }}"></a>
                                @endforeach
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- زر عرض المزيد --}}
    <div class="mt-12 text-center">
        <a href="{{ localizedRoute('hadrami') }}" 
           class="inline-flex items-center bg-brand-green text-white rtl:pr-12 rtl:pl-2.5 ltr:pl-12 ltr:pr-2.5 rounded-none text-xl hover:bg-black transition-all duration-500 shadow-xl hover:shadow-2xl hover:-translate-y-1 group special_font_nasser" style="margin-top: 20px;">
            <span>{{ app()->getLocale() == 'ar' ? 'عرض المزيد' : 'View More' }}</span>
            <div class="bg-white/20 p-2 rounded-full group-hover:bg-white/30 transition-colors ms-4 me-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ app()->getLocale() == 'ar' ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>
        </a>
    </div>

    {{-- شريط الاستفسار (CTA) --}}
    <div class="mt-16 bg-brand-green text-white py-6 px-4 text-center rounded-none shadow-lg" data-aos="zoom-in" style="margin-top: 10px;">
        <p class="text-lg md:text-2xl font-bold flex flex-wrap justify-center items-center gap-3">
            <span>{{ app()->getLocale() == 'ar' ? 'هل ترغب في بناء مشروع أحلامك معنا؟' : 'Do you want to build your dream project with us?' }}</span>
            <a href="{{ localizedRoute('customerservice') }}" class="underline decoration-2 underline-offset-8 hover:text-black transition-colors">
                {{ app()->getLocale() == 'ar' ? 'استفسر هنا' : 'Inquire here' }}
                <span class="inline-block {{ app()->getLocale() == 'ar' ? 'rotate-180' : '' }}">❯</span>
            </a>
        </p>
    </div>
</section>

<!-- Magnific Popup JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

<script>
    function waitForImagesHome($imgs, timeoutMs = 5000) {
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

    // $(document).ready(function () {
    //     @foreach ($hadramiProjects as $post)
    //         @if(isset($post->media) && count($post->media) > 1)
    //             (function() {
    //                 const $owl = $('#home-post-carousel-{{ $post->id }}');
    //                 const $loader = $('#home-loader-{{ $post->id }}');

    //                 // Initialize Owl EXACTLY like the original page
    //                 $owl.owlCarousel({
    //                     loop: true,
    //                     autoplay: true,
    //                     autoplayTimeout: 5000,
    //                     autoplayHoverPause: true,
    //                     rtl: document.documentElement.getAttribute('dir') === 'rtl',
    //                     items: 1,
    //                     nav: true,
    //                     dots: false,
    //                     navText: [
    //                         '<button class="btn absolute top-1/3 ltr:left-0 rtl:right-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/30 hover:bg-black/50 text-white border-0">❮</button>',
    //                         '<button class="btn absolute top-1/3 ltr:right-0 rtl:left-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/30 hover:bg-black/50 text-white border-0">❯</button>'
    //                     ]
    //                 });

    //                 $owl.on('initialized.owl.carousel', function () {
    //                     waitForImagesHome($owl.find('.owl-item img'), 10000).then(() => {
    //                         $loader.fadeOut(300);
    //                     });
    //                 });
    //                 // $owl.trigger('initialized.owl.carousel');
    //             })();
    //         @elseif(isset($post->media) && count($post->media) === 1)
    //             (function() {
    //                 const $loader = $('#home-loader-{{ $post->id }}');
    //                 const $img = $('img[src="{{ asset($post->media[0]->filepath) }}"]');
    //                 waitForImagesHome($img, 5000).then(() => { $loader.fadeOut(300); });
    //             })();
    //         @endif
    //     @endforeach

    //     $('.gallery-trigger-container-home').on('click', function(e) {
    //         e.preventDefault();
    //         const postId = $(this).data('post-id');
    //         const items = $(`#home-hidden-gallery-${postId} a`).map(function() { 
    //             return { src: $(this).attr('href') }; 
    //         }).get();
    //         $.magnificPopup.open({ items: items, gallery: { enabled: true }, type: 'image' });
    //     });
    // });

    $(document).ready(function () {

    function initHomeCarousel($owl, $loader) {
        // منع التهيئة المكررة
        if ($owl.hasClass('owl-loaded')) {
            $owl.trigger('destroy.owl.carousel');
            $owl.removeClass('owl-loaded');
            $owl.find('.owl-stage-outer').children().unwrap();
            $owl.find('.owl-stage').children().unwrap();
            $owl.find('.owl-item').children().unwrap();
        }

        // ربط الأحداث قبل التهيئة لضمان عدم ضياعها
        $owl.on('initialized.owl.carousel refreshed.owl.carousel', function () {
            setTimeout(() => {
                waitForImagesHome($owl.find('img'), 5000).then(() => {
                    $loader.fadeOut(300);
                });
            }, 100);
        });

        $owl.owlCarousel({
            items: 1,
            loop: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            rtl: document.documentElement.getAttribute('dir') === 'rtl',
            nav: true,
            dots: false,
            smartSpeed: 600,
            autoHeight: false,
            responsiveRefreshRate: 100,
            navText: [
                '<button class="btn absolute top-1/3 ltr:left-0 rtl:right-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/30 hover:bg-black/50 text-white border-0">❮</button>',
                '<button class="btn absolute top-1/3 ltr:right-0 rtl:left-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/30 hover:bg-black/50 text-white border-0">❯</button>'
            ]
        });
    }

    @foreach ($hadramiProjects as $post)
        @if(isset($post->media) && count($post->media) > 1)
            (function () {
                const $owl = $('#home-post-carousel-{{ $post->id }}');
                const $loader = $('#home-loader-{{ $post->id }}');

                initHomeCarousel($owl, $loader);

                // إعادة التهيئة عند تغيير حجم الشاشة
                let resizeTimer;
                $(window).on('resize', function () {
                    clearTimeout(resizeTimer);

                    resizeTimer = setTimeout(function () {
                        initHomeCarousel($owl, $loader);
                    }, 300);
                });
            })();
        @elseif(isset($post->media) && count($post->media) === 1)
            (function () {
                const $loader = $('#home-loader-{{ $post->id }}');
                const $img = $loader.closest('.card').find('img');

                waitForImagesHome($img, 3000).then(() => {
                    $loader.fadeOut(300);
                });
            })();
        @endif
    @endforeach

    $('.gallery-trigger-container-home').on('click', function (e) {
        e.preventDefault();

        const postId = $(this).data('post-id');

        const items = $(`#home-hidden-gallery-${postId} a`)
            .map(function () {
                return { src: $(this).attr('href') };
            }).get();

        $.magnificPopup.open({
            items: items,
            gallery: { enabled: true },
            type: 'image'
        });
    });
});
</script>
