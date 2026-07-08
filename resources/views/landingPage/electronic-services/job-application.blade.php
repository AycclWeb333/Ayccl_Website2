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

                                <div class="card-body p-6 flex-grow">
                                    <h2 class="card-title text-2xl font-bold text-green-900 mb-4 group-hover:text-green-700 transition-colors">
                                        {{ $detail?->title }}
                                    </h2>
                                    
                                    <div class="prose prose-sm max-w-none text-gray-600 mb-6 job-desc-container">
                                        @php
                                            $fullContent = html_entity_decode($detail?->content ?? '');
                                            $plainText = strip_tags($fullContent);
                                            $shouldTruncate = mb_strlen($plainText) > 200;
                                        @endphp

                                        @if($shouldTruncate)
                                            <div class="short-content" style="max-height: 100px; overflow: hidden;">
                                                {!! $fullContent !!}
                                            </div>
                                            <div class="toggle-btn-container mt-2">
                                                <button class="read-more-btn font-bold inline-block focus:outline-none transition-colors duration-200" style="color: #006b36;">
                                                    {{ app()->getLocale() == 'ar' ? 'عرض المزيد' : 'Read More' }}
                                                </button>
                                            </div>
                                            <div class="full-content" style="display: none;">
                                                {!! $fullContent !!}
                                            </div>
                                        @else
                                            <div class="full-content">
                                                {!! $fullContent !!}
                                            </div>
                                        @endif
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
<script>
    $(document).ready(function() {
        $('.job-desc-container').each(function() {
            var $container = $(this);
            var $shortContent = $container.find('.short-content');
            var $fullContent = $container.find('.full-content');
            var $btnContainer = $container.find('.toggle-btn-container');
            var $btn = $container.find('.read-more-btn');

            $btn.on('click', function() {
                $shortContent.addClass('hidden').hide();
                $btnContainer.addClass('hidden').hide();
                $fullContent.removeClass('hidden').show();

                // Create inline "Read Less / عرض أقل" link
                var lessText = "{{ app()->getLocale() == 'ar' ? 'عرض أقل' : 'Read Less' }}";
                var $lessBtn = $('<span class="read-less-inline cursor-pointer font-bold inline-block ms-2 transition-colors duration-200 hover:opacity-80" style="color: #006b36;">(' + lessText + ')</span>');

                // Append to the last paragraph/list/div inside the full content
                var $lastEl = $fullContent.find('p, li, div').last();
                if ($lastEl.length) {
                    $lastEl.append($lessBtn);
                } else {
                    $fullContent.append($lessBtn);
                }

                // Click handler for Read Less
                $lessBtn.on('click', function(e) {
                    e.stopPropagation();
                    $fullContent.addClass('hidden').hide();
                    $shortContent.removeClass('hidden').show();
                    $btnContainer.removeClass('hidden').show();
                    $lessBtn.remove();
                });
            });
        });
    });
</script>
@endsection
