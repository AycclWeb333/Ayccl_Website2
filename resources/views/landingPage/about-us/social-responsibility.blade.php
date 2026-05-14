@extends('layouts.app')
@section('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mixitup/3.3.1/mixitup.min.js"></script>
@endsection
@section('content')
    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

    <div class="w-[95%] mx-auto mt-20">
        {{-- Filter Tabs --}}
        <div class="tabs tabs-boxed bg-base-200/90 w-fit space-x-2 mb-10 mx-auto lg:mx-0">
            <a class="tab bg-emerald-900 text-white font-bold cursor-pointer" data-filter="all">{{ __('adminlte::landingpage.all') }}</a>
            @foreach ($categories as $category)
                <a class="tab bg-transparent text-black cursor-pointer" data-filter=".cat-{{ $category->id }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        @isset($posts)
            <div id="social-grid" class="space-y-10 lg:space-y-20">
                @foreach ($posts as $index => $post)
                    @php
                        $isEven = $index % 2 === 0;
                        $catId = $post->category_id ?? ($post->postDetailOne->category_id ?? 0);
                    @endphp

                    <div class="mix cat-{{ $catId }} flex flex-col lg:flex-row items-center gap-8 lg:gap-16 my-10 shadow-2xl p-6 lg:p-10 py-12 lg:py-20 rounded-4xl {{ $isEven ? 'lg:flex-row-reverse' : 'lg:flex-row' }}" data-aos="fade-up" data-aos-duration="400">

                        @if (isset($post->mediaOne->filepath))
                            <div class="w-full lg:w-1/2 flex justify-center items-center">
                                <div class="relative inline-block group">
                                    <div class="relative z-10 overflow-hidden shadow-lg" style="box-shadow: -20px -18px 4px 1px #2d843d; border-radius: 0px;">
                                        <img src="{{ asset($post->mediaOne->filepath) }}"
                                             alt="{{ $post->mediaOne->alt }}"
                                             class="w-full h-80 sm:h-[400px] object-cover block" />
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="w-full lg:w-1/2 lg:text-start space-y-6">
                            <h2 class="card-title text-2xl xl:text-5xl text-green-900 text-center lg:text-right">
                                {{ $post->postDetailOne->title }}
                            </h2>
                            <div class="text-md xl:text-xl space-y-4 text-gray-700 text-justify">
                                {!! $post->postDetailOne->content !!}
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endisset
    </div>
@endsection
@section('jsafter')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var containerEl = document.querySelector('#social-grid');
            if (containerEl) {
                var mixer = mixitup(containerEl, {
                    selectors: {
                        target: '.mix'
                    },
                    animation: {
                        duration: 300
                    }
                });

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
            }
        });
    </script>
@endsection
