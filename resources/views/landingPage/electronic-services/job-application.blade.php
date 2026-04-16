@extends('layouts.app')
@section('css')
@endsection
@section('content')

    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

    <div class="container mx-auto px-4 py-12 mt-10 md:mt-20">
        @isset($posts)
            @if(count($posts) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8" data-aos="fade-up">
                    @foreach ($posts as $post)
                        @php $detail = $post->postDetailOne; @endphp
                        <div class="card bg-white shadow-xl hover:shadow-2xl transition-all duration-300 border-t-4 border-green-700 overflow-hidden group">
                            <div class="flex flex-col h-full">
                                {{-- الوجه البصري للوظيفة --}}
                                @if(isset($post->mediaOne))
                                <div class="h-48 overflow-hidden relative">
                                    <img src="{{ asset($post->mediaOne->filepath) }}" 
                                         alt="{{ $detail?->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                                    <div class="absolute top-4 right-4 bg-green-700 text-white px-3 py-1 text-sm font-bold rounded-full">
                                        {{ app()->getLocale() == 'ar' ? 'وظيفة متاحة' : 'Job Opening' }}
                                    </div>
                                </div>
                                @endif

                                <div class="card-body p-6 flex-grow">
                                    <h2 class="card-title text-2xl font-bold text-green-900 mb-4 group-hover:text-green-700 transition-colors">
                                        {{ $detail?->title }}
                                    </h2>
                                    
                                    <div class="prose prose-sm max-w-none text-gray-600 line-clamp-4 mb-6">
                                        {!! html_entity_decode($detail?->content) !!}
                                    </div>

                                    <div class="mt-auto pt-6 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
                                        <div class="flex items-center text-gray-400 text-sm italic">
                                            <i class="far fa-calendar-alt me-2 font-bold p-2 text-green-700"></i>
                                            {{ $post->created_at?->format('Y-m-d') }}
                                        </div>

                                        @if(isset($post->mediaOne->link))
                                        <a href="{{ asset($post->mediaOne->link) }}" 
                                           target="_blank"
                                           class="btn btn-primary bg-green-700 border-none hover:bg-green-800 text-white rounded-lg px-6 shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                                            <i class="fas fa-file-pdf"></i>
                                            {{ app()->getLocale() == 'ar' ? 'تحميل الشروط والتقديم' : 'Download & Apply' }}
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="max-w-2xl mx-auto text-center py-20 bg-white rounded-2xl shadow-sm border-2 border-dashed border-gray-200" data-aos="zoom-in">
                    <div class="mb-6 opacity-20">
                        <i class="fas fa-briefcase text-8xl text-green-900"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-400 mb-2">
                        {{ __('adminlte::landingpage.noJobApplication') }}
                    </h3>
                    <p class="text-gray-400">
                        {{ app()->getLocale() == 'ar' ? 'نشكرك على اهتمامك، سنقوم بنشر أي وظائف جديدة هنا فور توفرها.' : 'Thank you for your interest. New positions will be posted here as they become available.' }}
                    </p>
                </div>
            @endif
        @endisset
    </div>
@endsection
@section('jsafter')
@endsection
