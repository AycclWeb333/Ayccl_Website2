@extends('layouts.app')

@section('content')
    {{-- @include('daisyUI.hero') --}}

    <x-hero title="{{ __('adminlte::landingpage.aboutcompany') }}" description="{{ $page->content }}"
        img="{{ asset($page->background) }}" />

    <div class="w-[95%] sm:w-[90%] mx-auto ">

        {{-- <x-divider>{{ $posts[0]->postDetailOne->title }} </x-divider>
        <div class="text-center sm:m-10 mt-0" data-aos="fade-up" data-aos-delay="100">
            <p class="text-sm font-semibold sm:text-lg text-gray-700 mt-4">
                {!! $posts[0]->postDetailOne->content !!}
            </p>
        </div> --}}

        <x-divider>{{ $posts[0]->postDetailOne->title }}</x-divider>

        <section class="max-w-6xl mx-auto px-4 sm:px-8 mt-10">

            <div class="grid md:grid-cols-2 gap-6 items-start">

                @foreach ($companySections as $index => $section)
                    <div class="info-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <button class="card-toggle">
                            <span>{{ $section->icon }}
                                {{ app()->getLocale() == 'ar' ? $section->title : ($section->title_en ?? $section->title) }}</span>
                        </button>
                        <div class="card-content text-justify">
                            {{ app()->getLocale() == 'ar' ? $section->content : ($section->content_en ?? $section->content) }}
                        </div>
                    </div>
                @endforeach

                {{-- FULL DETAILS: يعرض المحتوى كاملاً --}}
                <div class="info-card {{ count($companySections) % 2 == 0 ? 'md:col-span-2' : '' }}" data-aos="fade-up" data-aos-delay="500">
                    <button class="card-toggle">
                        📖 {{ __('adminlte::landingpage.moreDetails') }}
                    </button>
                    <div class="card-content text-justify">
                        {!! $mainContentHtml !!}
                    </div>
                </div>

            </div>{{-- end grid --}}

           


        </section>
        <style>
            .info-card {
                /* background: linear-gradient(145deg, #1f2937, #111827); */
                background: linear-gradient(145deg, #006b36, #274f36);
                border-radius: 18px;
                border: 1px solid rgba(255, 255, 255, 0.06);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
                overflow: hidden;
            }

            .card-toggle {
                width: 100%;
                text-align: right;
                padding: 18px 22px;
                font-weight: bold;
                font-size: 18px;
                color: white;
                background: transparent;
                cursor: pointer;
                border: none;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .card-toggle::after {
                content: "＋";
                font-size: 22px;
                transition: transform .3s;
            }

            .info-card.active .card-toggle::after {
                content: "−";
            }

            .card-content {
                max-height: 0;
                overflow: hidden;
                padding: 0 22px;
                color: #d1d5db;
                line-height: 1.9;
                transition: all .4s ease;
            }

            .info-card.active .card-content {
                max-height: 5000px;
                padding: 0 22px 20px;
            }

            .stat-box {
                background: #ffffff;
                border-radius: 14px;
                padding: 30px 20px;
                border: 1px solid #e5e7eb;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
                transition: all .3s ease;

            }

            .stat-box:hover {
                transform: translateY(-6px);
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            }

            .stat-number {
                font-size: 40px;
                font-weight: 800;
                color: #006b36;
                /* أخضر الهوية */
            }

            .stat-label {
                margin-top: 10px;
                font-size: 15px;
                color: #111827;
                /* أسود أنيق */
                font-weight: 600;
            }
        </style>
        <script>
            document.querySelectorAll('.card-toggle').forEach(btn => {
                btn.addEventListener('click', () => {
                    const card = btn.parentElement;
                    card.classList.toggle('active');
                });
            });

            const counters = document.querySelectorAll('.stat-number');
            let started = false;

            function startCounting() {
                counters.forEach(counter => {
                    const target = +counter.dataset.target;
                    const duration = 2000;
                    const step = target / (duration / 16);

                    let count = 0;
                    const update = () => {
                        count += step;
                        if (count < target) {
                            counter.innerText = Math.floor(count).toLocaleString();
                            requestAnimationFrame(update);
                        } else {
                            counter.innerText = target.toLocaleString();
                        }
                    };
                    update();
                });
            }

            window.addEventListener('scroll', () => {
                const statsSection = document.getElementById('stats');
                const rect = statsSection.getBoundingClientRect();

                if (!started && rect.top < window.innerHeight - 100) {
                    started = true;
                    startCounting();
                }
            });
        </script>


        {{-- ═══════════════════════════════════════════════════════════
             قسم الرؤية والرسالة
        ═══════════════════════════════════════════════════════════ --}}
        <x-divider>الرؤية والرسالة</x-divider>
        @if($visionPosts->isNotEmpty())
        <div class="bg-base-100 shadow-lg m-10 lg:w-[90%] mx-auto rounded-3xl overflow-hidden" data-aos="fade-up" data-aos-duration="700">
            <div class="flex flex-col lg:flex-row gap-8 p-6">

                {{-- جانب الـ Accordion (الرؤية والرسالة) --}}
                <div class="lg:w-1/2 w-full space-y-4">
                    <!-- <h2 class="font-semibold text-3xl lg:text-4xl text-green-900 text-center mb-6">
                        {{ __('adminlte::landingpage.aboutcompany') }}
                    </h2> -->

                    @foreach($visionPosts as $i => $vPost)
                        @if($vPost->postDetailOne)
                        <div class="accordion-item border border-gray-200 rounded-lg overflow-hidden">
                            <button class="accordion-header w-full text-right p-4 bg-green-50 hover:bg-green-100 transition-colors duration-300 flex justify-between items-center cursor-pointer"
                                    onclick="toggleVisionAccordion({{ $i + 1 }})">
                                <span class="font-semibold text-xl text-green-900">
                                    {{ app()->getLocale() == 'ar'
                                        ? $vPost->postDetailOne->title
                                        : ($vPost->postDetailOne->title_en ?? $vPost->postDetailOne->title) }}
                                </span>
                                <svg id="vision-icon-{{ $i + 1 }}" class="w-6 h-6 text-green-900 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="vision-content-{{ $i + 1 }}" class="accordion-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                                <div class="p-4 bg-white">
                                    <div class="content-area font-semibold text-lg text-gray-700">
                                        {!! app()->getLocale() == 'ar'
                                            ? $vPost->postDetailOne->content
                                            : ($vPost->postDetailOne->content_en ?? $vPost->postDetailOne->content) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                {{-- جانب الصورة --}}
                <div class="w-full lg:w-1/2 flex justify-center items-center">
                    <div class="relative inline-block group">
                        <div class="relative z-10 overflow-hidden shadow-lg" style="box-shadow: -20px -18px 4px 1px #2d843d; border-radius: 0px;">
                            @isset($visionPosts[0]->mediaOne->filepath)
                                <img src="{{ asset($visionPosts[0]->mediaOne->filepath) }}"
                                     alt="{{ $visionPosts[0]->mediaOne->alt ?? 'صورة توضيحية' }}"
                                     class="w-full h-80 sm:h-[400px] object-cover block" />
                            @endisset
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @endif

        <script>
        function toggleVisionAccordion(index) {
            const content = document.getElementById(`vision-content-${index}`);
            const icon    = document.getElementById(`vision-icon-${index}`);

            const allContents = document.querySelectorAll('[id^="vision-content-"]');
            const allIcons    = document.querySelectorAll('[id^="vision-icon-"]');

            allContents.forEach((item, i) => {
                if (item.id !== `vision-content-${index}`) {
                    item.style.maxHeight = '0px';
                    allIcons[i].style.transform = 'rotate(0deg)';
                }
            });

            if (content.style.maxHeight && content.style.maxHeight !== '0px') {
                content.style.maxHeight = '0px';
                icon.style.transform = 'rotate(0deg)';
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
                icon.style.transform = 'rotate(180deg)';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleVisionAccordion(1);
        });
        </script>

        <x-divider>{{ __('adminlte::landingpage.values') }}</x-divider>
        <!-- <section class=" px-4 bg-base-100 justify-center">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:inline-grid lg:grid-cols-3 lg:justify-center gap-6">
                @foreach ($posts->skip(1) as $post)
                    <x-icon-card title="{{ $post->postDetailOne->title }}" icon="{{ $post->postDetailOne->color }}"
                        description="{{ $post->postDetailOne->content }}" />
                @endforeach
            </div>
        </section> -->

        <section class="px-4 bg-base-100 flex justify-center w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
                    @foreach ($posts->skip(1) as $post)
                        <x-icon-card title="{{ $post->postDetailOne->title }}" icon="{{ $post->postDetailOne->color }}"
                            description="{{ $post->postDetailOne->content }}" />
                    @endforeach
                </div>
       </section>
    </div>
@endsection
@section('jsafter')
    {{-- <livewire:carousel />  --}}
@endsection
