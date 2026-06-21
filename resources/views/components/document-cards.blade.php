@props(['post'])

<div
    class="card bg-white rounded-4xl shadow-md hover:shadow-2xl transition-all duration-300 border-4 border-gray-200 w-64">

    {{-- Thumbnail --}}
    <figure class="p-4 flex justify-center overflow-hidden">
        <img src="{{ asset($post->mediaOne->thumbnailpath) }}" alt="Thumbnail" class="w-20 h-20 object-contain" />

    </figure>

    {{-- Body --}}
    <div class="card-body text-center p-4 rounded-b-2xl"
        style="background-image: url({{ asset('images/backgrounds/subtle-prism.svg') }})">

        <h3 class="text-base font-semibold text-gray-800 line-clamp-2">
            {{ $post->postDetail[0]->title }}
        </h3>
        <h3 class="text-base font-semibold text-gray-800 line-clamp-2">
            {!! $post->postDetail[0]->content !!}
        </h3>
        {{-- @php
            $filePath = isset($post->mediaOne->link) && $post->mediaOne->link != '' ? public_path(path: $post->mediaOne->link) : '';
            if ($filePath != ''&& isset($filePath) && file_exists($filePath)) {
                $size = formatSize(\Illuminate\Support\Facades\File::size($filePath));
            }
        @endphp --}}

        <h2 class="text-sm text-gray-700">{!! $formattedSize ?? '' !!}</h2>

        <div class="mt-3">
            <a href="{{ asset($post->mediaOne->link) }}" target="_blank"
                class="btn btn-sm rounded-lg bg-white text-emerald-800 font-semibold shadow-md hover:scale-105 transition-transform duration-300">
                <x-heroicon-c-document-arrow-down class="w-5 h-5" />
                {{ __('adminlte::landingpage.download') }}
            </a>
        </div>
    </div>
</div>
