@extends('layouts.app')
@section('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mixitup/3.3.1/mixitup.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <style>
        .owl-nav {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .owl-nav button {
            pointer-events: auto;
        }
    </style>
@endsection
@section('jsbefore')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
@endsection
@section('content')
    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

    <div class="w-[95%] md:w-[90%] lg:w-[95%] mx-auto my-20">
        
        <div class="tabs tabs-boxed bg-base-200/90 w-fit space-x-2 mb-10">
            <a class="tab bg-emerald-900 text-white font-bold" data-filter="all">{{ __('adminlte::landingpage.all') }}</a>
            @foreach ($categories as $category)
                <a class="tab bg-transparent text-black" data-filter=".cat-{{ $category->id }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div id="news-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-8 lg:gap-4">
            @foreach ($posts as $post)
                @php $catId = $post->postDetail->first()->category_id ?? $post->category_id; @endphp
                <div class="mix cat-{{ $catId }}">
                    <x-image-slides :post="$post" />
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('jsafter')
    <script>
        var containerEl = document.querySelector('#news-grid');
        var mixer = mixitup(containerEl, {
            selectors: {
                target: '.mix'
            },
            animation: {
                duration: 300
            }
        });

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