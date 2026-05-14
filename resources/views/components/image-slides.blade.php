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
    @php
        $firstMedia = $post->media[0] ?? null;
        $isPdf = $firstMedia && (Str::endsWith($firstMedia->filepath, '.pdf') || $firstMedia->media_type_id == 3);
        
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
    
    <a href="{{ $postUrl }}">
    @if ($firstMedia)
        @if ($isPdf)
            <!-- PDF Preview -->
            <div class="relative w-full h-64 overflow-hidden rounded-t-lg bg-gray-100">
                <iframe src="{{ asset($firstMedia->filepath) }}#toolbar=0&navpanes=0&scrollbar=0" 
                    class="w-full h-full pointer-events-none" 
                    frameborder="0"></iframe>
                <div class="absolute inset-0 bg-transparent cursor-pointer"></div>
            </div>
        @else
            <!-- Single Image -->
            <div class="relative w-full h-64 overflow-hidden rounded-t-lg">
                <img src="{{ asset($firstMedia->thumbnailpath) }}" alt="{{ $firstMedia->alt ?? '' }}"
                    class="object-cover w-full h-full" />
            </div>
        @endif
    @endif
    </a>

    <!-- Card Body -->
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
        <div class="flex flex-wrap gap-2 justify-center mt-auto">
            <a href="{{ $postUrl }}"
                class="text-white font-bold btn bg-emerald-700 hover:bg-emerald-800 border-none flex-1 min-w-[120px]">
                {{ in_array($post->page_id, [54, 55, 56]) ? __('adminlte::adminlte.preview') : __('adminlte::landingpage.readmore') }}
            </a>
            {{ $slot }}
        </div>
    </div>
</div>

<!-- Init Script -->
<script>
    (function() {
        const loader_{{ $post->id }} = $('#loader-{{ $post->id }}');
        @if($isPdf)
            const iframe = $('iframe[src*="{{ $firstMedia->filepath }}"]');
            iframe.on('load', function() {
                loader_{{ $post->id }}.fadeOut(300);
            });
            // Fallback
            setTimeout(() => loader_{{ $post->id }}.fadeOut(300), 4000);
        @elseif($firstMedia)
            const img = $('img[src="{{ asset($firstMedia->thumbnailpath) }}"]');
            if (img[0] && img[0].complete && img[0].naturalHeight !== 0) {
                loader_{{ $post->id }}.fadeOut(300);
            } else if (img[0]) {
                img.on('load', function() {
                    loader_{{ $post->id }}.fadeOut(300);
                }).on('error', function() {
                    loader_{{ $post->id }}.fadeOut(300);
                });
            } else {
                loader_{{ $post->id }}.fadeOut(300);
            }
        @else
            loader_{{ $post->id }}.fadeOut(300);
        @endif

        setTimeout(function() {
            if (loader_{{ $post->id }}.is(':visible')) {
                loader_{{ $post->id }}.fadeOut(300);
            }
        }, 6000);
    })();
</script>
