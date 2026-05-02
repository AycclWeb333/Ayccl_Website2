<div class="card w-full h-full bg-base-100 shadow-md hover:shadow-2xl transition-all duration-200 p-2">
    <!-- Loader -->
    <div id="loader-{{ $post->id }}" class="absolute inset-0 flex justify-center items-center bg-white/70 z-50">
        <svg class="animate-spin h-10 w-10 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
            </circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
    </div>
    <a href="{{ localizedRoute('News.show', ['id' => $post->id, 'slug' => $post->postDetail[0]->slug]) }}">
    @if (isset($post->mediaOne->id))
        {{-- @if (count($post->media) == 1) --}}
            <!-- Single Image -->
            <div class="relative w-full h-50 overflow-hidden rounded-t-lg">
                <img src="{{ asset($post->media[0]->thumbnailpath) }}" alt="{{ $post->media[0]->alt ?? '' }}"
                    class=" object-cover w-full" />
            </div>
        {{-- @else
            <!-- OwlCarousel for multiple images -->
            <div id="post-carousel-{{ $post->id }}"
                class="owl-carousel relative w-full h-50 overflow-hidden rounded-t-lg">
                @foreach ($post->media as $img)
                    <div class="item cursor-grabbing w-full overflow-hidden">
                        <img src="{{ asset($img->thumbnailpath) }}" alt="{{ $img->alt ?? '' }}"
                            class="object-cover" />
                    </div>
                @endforeach
            </div>
        @endif --}}
    @endif
    </a>

    <!-- Card Body -->
    @php
        $routeNames = [
            51 => 'News.show',
            54 => 'landing.documents.show',
            55 => 'landing.inspectionCertificates.show',
            56 => 'landing.specifications.show',
        ];
        $routeName = $routeNames[$post->page_id] ?? 'News.show';
        $slug = !empty($post->postDetail[0]->slug) ? $post->postDetail[0]->slug : 'post';
        $postUrl = localizedRoute($routeName, ['id' => $post->id, 'slug' => $slug]);
    @endphp
    <div class="card-body">
        <h2 class="card-title"> <a
                href="{{ $postUrl }}"
                class="text-emerald-700"> {{ $post->postDetail[0]->title }}</a> </h2>
        <time datetime="{{ \Carbon\Carbon::parse($post->date)->toIso8601String() }}" class="text-sm text-gray-500">
            {{ \Carbon\Carbon::parse($post->date)->format('M d, Y') }}
        </time>
        <p class="text-gray-800 ">
            {!! \Illuminate\Support\Str::limit(strip_tags($post->postDetail[0]->content), 180) !!}
        </p>
        <a href="{{ $postUrl }}"
            class="text-white font-bold  mx-auto btn bg-emerald-700">{{ __('adminlte::landingpage.readmore') }}</a>
    </div>
</div>

<!-- Init Script - Only for multiple images -->
{{-- @if (count($post->media) > 1)
    <script>
        (function() {
            // Create unique variables for each post
            var owl_{{ $post->id }} = jQuery('#post-carousel-{{ $post->id }}');
            const loader_{{ $post->id }} = $('#loader-{{ $post->id }}');

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
                    '<button class="btn absolute top-1/3 sm:top-1/4 right-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/30 hover:bg-black/50 font-bold text-white border-0">❮</button>',
                    '<button class="btn absolute top-1/3 sm:top-1/4 left-0 h-2/6 btn-square btn-lg shadow-2xl bg-black/30 hover:bg-black/50 font-bold text-white border-0">❯</button>'
                ]
            });

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
@elseif (count($post->media) == 1) --}}
    <!-- Simple image loader for single image -->
    <script>
        (function() {
            const loader_{{ $post->id }} = $('#loader-{{ $post->id }}');
            const img = $('img[src="{{ asset($post->media[0]->thumbnailpath) }}"]');

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
{{-- @endif --}}
