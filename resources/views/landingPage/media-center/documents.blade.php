@extends('layouts.app')
@section('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mixitup/3.3.1/mixitup.min.js"></script>
@endsection

@section('content')
    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

    <x-divider>{{ __('adminlte::landingpage.download') }}</x-divider>

    <div class="w-[95%] md:w-[90%] lg:w-[95%] mx-auto my-10">
        
        <div class="tabs tabs-boxed bg-base-200/90 w-fit space-x-2 mb-10 mx-auto">
            <a class="tab bg-emerald-900 text-white font-bold cursor-pointer" data-filter="all">{{ __('adminlte::landingpage.all') }}</a>
            @foreach ($categories as $category)
                <a class="tab bg-transparent text-black cursor-pointer" data-filter=".cat-{{ $category->id }}">
                    {{ app()->getLocale() == 'en' ? ($category->name_en ?? $category->name) : $category->name }}
                </a>
            @endforeach
        </div>

        <div id="documents-grid" class="flex flex-wrap gap-6 my-5 justify-center w-full">
            @foreach ($posts as $post)
                @if (isset($post->mediaOne))
                    @php $catId = $post->category_id; @endphp
                    <div class="mix {{ $catId ? 'cat-' . $catId : '' }}">
                        <x-document-cards :post="$post" />
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection

@section('jsafter')
    <script>
        var containerEl = document.querySelector('#documents-grid');
        if (containerEl) {
            var mixer = mixitup(containerEl, {
                selectors: {
                    target: '.mix'
                },
                animation: {
                    duration: 300
                }
            });
        }

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
    </script>
@endsection
