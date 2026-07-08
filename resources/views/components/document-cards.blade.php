@props(['post'])

@php
    $thumbSrc = $post->mediaOne->thumbnailpath ?? '';
    // إذا كان المسار URL كامل (من DO Spaces) استخدمه مباشرة، وإلا أضف asset()
    $thumbUrl  = str_starts_with($thumbSrc, 'http') ? $thumbSrc : asset($thumbSrc ?: 'images/thumbnails/document.png');

    $linkSrc   = $post->mediaOne->link ?? '';
    $linkUrl   = str_starts_with($linkSrc, 'http') ? $linkSrc : asset($linkSrc);

    // صورة احتياطية في حال فشل التحميل
    $fallback  = asset('images/thumbnails/document.png');
@endphp

<div class="card bg-white rounded-4xl shadow-md hover:shadow-2xl transition-all duration-300 border-4 border-gray-200 w-64">

    {{-- Thumbnail --}}
    <figure class="p-4 flex justify-center items-center overflow-hidden bg-gray-50 rounded-t-4xl min-h-[120px]">
        <img
            src="{{ $thumbUrl }}"
            alt="PDF Thumbnail"
            class="w-24 h-24 object-contain drop-shadow-md"
            onerror="this.onerror=null; this.src='{{ $fallback }}';"
        />
    </figure>

    {{-- Body --}}
    <div class="card-body text-center p-4 rounded-b-2xl"
        style="background-image: url({{ asset('images/backgrounds/subtle-prism.svg') }})">

        <h3 class="text-base font-semibold text-gray-800 line-clamp-2 mb-1">
            {{ $post->postDetail[0]->title }}
        </h3>
        @if($post->postDetail[0]->content)
            <p class="text-sm text-gray-600 line-clamp-2">
                {!! $post->postDetail[0]->content !!}
            </p>
        @endif

        <div class="mt-3">
            <a href="{{ $linkUrl }}" target="_blank"
                class="btn btn-sm rounded-lg bg-white text-emerald-800 font-semibold shadow-md hover:scale-105 transition-transform duration-300">
                <x-heroicon-c-document-arrow-down class="w-5 h-5 inline-block me-1" />
                {{ __('adminlte::landingpage.download') }}
            </a>
        </div>
    </div>
</div>

