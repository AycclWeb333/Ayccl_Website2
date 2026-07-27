@extends('layouts.app')
@section('css')
@endsection
@section('content')
    {{-- @include('daisyUI.navbar-upper')
    @include('daisyUI.navbar') --}}

    <x-hero :title="$product->pname" description="" img="{{ asset('images/news1.png') }}" />

    <div class="w-[85%] sm:w-[100%] md:w-[80%] lg:w-[90%] mx-auto ">

        <div class="flex flex-col md:flex-row items-start gap-8 bg-base-100 p-6 rounded-lg shadow-md">

            {{-- Left: Product Image --}}
            <div class="flex justify-center items-center w-full md:w-1/2">
                <img src="{{ asset($product->pimage) }}" alt="{{ $product->pname }}"
                    class="w-full max-w-md rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
            </div>
        
            {{-- Right: Product Info --}}
            <div class="w-full md:w-1/2 space-y-4">
                <h1 class="text-3xl font-bold text-gray-800">{{ $product->pname }}</h1>
                <div class="product-description text-gray-700 text-lg leading-relaxed">{!! $product->description !!}</div>
        
                {{-- Specifications --}}
                @if (!empty($product->specs))
                    <div class="mt-4">
                        <h2 class="text-xl font-semibold text-gray-700 mb-2">المواصفات:</h2>
                        <ul class="list-disc pl-5 text-gray-600 space-y-1">
                            @foreach ($product->specs as $spec)
                                <li class="ms-10 no-hover-style">{{ $spec }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
        
                {{-- Download Button --}}
                <div class="mt-6 ">
                    <a href="{{ $product->downloadLink }}" class="btn btn-info">تحميل موصفات اضافية</a>
                </div>
            </div>
        </div>
        
    </div>

    {{-- @include('daisyUI.footer') --}}
@endsection
@section('jsafter')
@endsection
