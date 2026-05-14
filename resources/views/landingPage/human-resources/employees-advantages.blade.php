@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

    <div class="w-[95%]  lg:w-[90%] mx-auto mt-20  ">
        @isset($posts)
            @php
                // Check if any posts have images
                $hasAnyImages = $posts->contains(function($post) {
                    return isset($post->mediaOne);
                });
            @endphp

            @if ($hasAnyImages)
                {{-- Premium Alternating Layout --}}
                <div class="space-y-24 py-10" data-aos="fade-up" data-aos-duration="800">
                    @foreach ($posts as $index => $post)
                        @php
                            $isEven = $index % 2 === 0;
                            $hasMedia = isset($post->mediaOne);
                        @endphp

                        <div class="flex flex-col {{ $isEven ? 'lg:flex-row-reverse' : 'lg:flex-row' }} items-center gap-12 lg:gap-20">
                            {{-- Image Container with decorative elements --}}
                            @if($hasMedia)
                                <div class="w-full lg:w-1/2 relative group">
                                    <div class="absolute -inset-4 bg-emerald-100/50 rounded-[2rem] transform {{ $isEven ? 'rotate-2' : '-rotate-2' }} group-hover:rotate-0 transition-transform duration-500 -z-10"></div>
                                    <div class="relative h-[300px] md:h-[450px] overflow-hidden rounded-[1.5rem] shadow-2xl">
                                        <img src="{{ asset($post->mediaOne->filepath) }}" alt="{{ $post->postDetailOne->title }}" 
                                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" />
                                        <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/40 via-transparent to-transparent opacity-60"></div>
                                    </div>
                                    {{-- Floating badge or icon could go here --}}
                                </div>
                            @endif

                            {{-- Content Container --}}
                            <div class="w-full lg:w-1/2 text-right {{ session('locale') == 'en' ? 'lg:text-left' : 'lg:text-right' }}">
                                @foreach ($post->postDetail as $postDetail)
                                    <!-- <div class="inline-block px-4 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-sm mb-4 tracking-wider uppercase">
                                        {{ __('adminlte::menu.employeeAdvantages') }}
                                    </div> -->
                                    <h2 class="text-2xl lg:text-3xl font-black text-gray-900 mb-8 leading-tight">
                                        {{ $postDetail->title }}
                                    </h2>
                                    <div class="prose prose-lg prose-emerald max-w-none text-gray-600 leading-relaxed font-medium">
                                        {!! $postDetail->content !!}
                                    </div>
                                @endforeach
                                
                                {{-- Subtle separator --}}
                                <div class="mt-10 flex {{ session('locale') == 'en' ? 'justify-start' : 'justify-end' }}">
                                    <div class="h-1 w-20 bg-emerald-500 rounded-full"></div>
                                    <div class="h-1 w-4 bg-emerald-200 rounded-full mx-2"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Grid layout for posts without images --}}
                <div class="grid gap-6" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                    @foreach ($posts as $index => $post)
                        @php
                            // Determine content length and set grid span accordingly
                            $contentLength = 0;
                            $colors = ['primary', 'info', 's-gray-500', 'warning']; 
                            $color = $colors[$index % count($colors)];
                            $detailCount = count($post->postDetail);
                            foreach ($post->postDetail as $index => $postDetail) {
                                $contentLength += strlen($postDetail->title) + strlen($postDetail->content);
                            }
                            // Large content: full row, Medium content: half row, Small content: third row
                            $isLargeContent = $contentLength > 800 || $detailCount > 3;
                            $isMediumContent = $contentLength > 400 || $detailCount > 2;
                        @endphp
                        <div class="bg-base-100 border-s-4  border-{{ $color }} shadow-lg rounded-lg p-6 hover:shadow-xl transition-shadow duration-300 min-h-fir h-full {{ $isLargeContent ? 'col-span-full' : ($isMediumContent ? 'md:col-span-2' : '') }}">
                            @foreach ($post->postDetail as $postDetail)
                                <h2 class="card-title text-xl xl:text-3xl text-green-900 text-center mb-4">{{ $postDetail->title }}</h2>
                                @if ($postDetail->category_id == 1)
                                    <p class="text-sm xl:text-lg text-gray-700 leading-relaxed">
                                        {!! $postDetail->content !!}
                                    </p>
                                @else
                                    <ol class="text-sm xl:text-lg text-gray-700 leading-relaxed list-decimal list-inside space-y-2">
                                        {!! $postDetail->content !!}
                                    </ol>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
        @endisset
    </div>

    {{-- @include('daisyUI.footer') --}}
@endsection
@section('jsafter')
@endsection
