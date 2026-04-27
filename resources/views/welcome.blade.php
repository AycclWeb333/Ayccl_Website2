@extends('layouts.app')
@section('css')
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
@endsection
@section('content')
    {{-- @include('daisyUI.drawer') --}}
    {{-- @include('daisyUI.navbar-upper') --}}
    {{-- @include('daisyUI.navbar') --}}
    {{-- <x-hero title="{{ __('adminlte::landingpage.ayccl') }}" description='' img="{{ asset('images/news1.png') }}" /> --}}
    {{-- @include('daisyUI.carousel') --}}
    <x-slideshow :slideshows="$slideshows" />
    <div class="w-[95%] sm:w-[90%] md:w-[80%] lg:w-[85%] mx-auto pattern">


        {{-- قسم من نحن --}}
        <x-divider>{{ __('adminlte::landingpage.aboutus') }}</x-divider>
        @include('daisyUI.About-us')


        {{-- products --}}
        <x-divider>{{ __('adminlte::landingpage.products') }}</x-divider>
        <div class="w-fit mx-auto">
            <div class="flex flex-col sm:flex-row justify-center px-4">
                @foreach ($products as $product)
                    <x-product-card :id="$product->id" :image="$product->mediaOne ? asset($product->mediaOne->filepath) : asset('images/default-product.png')" :name="$product->postDetailOne->title" detailsUrl="" />
                    {{-- <img class="h-52 m-0" src="{{ $product->mediaOne ? asset($product->mediaOne->filepath) : asset('images/default-product.png') }}" alt="{{ $product->mediaOne->alt ?? '' }}"> --}}
                @endforeach
            </div>

            <a class="flex min-w-fit w-1/3 p-7 mx-auto btn bg-transparent border-2 border-emerald-800/80 hover:bg-emerald-800/90 hover:text-white mt-2"
                href="{{ localizedRoute('products') }}">
                {{ __('adminlte::landingpage.productSpecs') }}
            </a>
        </div>

         {{-- @include('daisyUI.collapse') --}}
        <x-divider>{{ __('adminlte::landingpage.isoCertificates') }}</x-divider>
        <div class="my-10">


            <x-iso-carousel :posts="$isoCertificates" style="" />
        </div> {{-- <x-slideshow :slideshows="$slideshows" /> --}}
        {{-- قسم اسمنت حضرموت 100% (أخر المشاريع) --}}
        @include('daisyUI.hadrami')

        {{-- social media --}}
        @include('daisyUI.social-media')

        {{-- contact us details --}}
        @include('daisyUI.address-map')
    </div>
@endsection

@section('jsafter')
@endsection
