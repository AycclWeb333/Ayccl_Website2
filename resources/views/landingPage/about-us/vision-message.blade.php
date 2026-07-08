@extends('layouts.app')
@section('css')
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&family=Cairo:wght@400;700&family=Almarai:wght@400;700&family=Amiri:wght@400;700&family=Lemonada:wght@400;700&family=Changa:wght@400;700&family=Reem+Kufi:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
@endsection
@section('content')

    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

<div class="w-[85%] sm:w-[100%] md:w-[80%] lg:w-[90%] mx-auto" data-aos="fade-up" data-aos-duration="700">

    {{-- القسم الرئيسي: الأهداف مع الصورة --}}
    @php $objectivesPost = $posts->firstWhere(fn($p) => $p->id == 20) ?? $posts->first(); @endphp

    @if($objectivesPost && $objectivesPost->postDetailOne)
    <div class="bg-base-100 shadow-lg m-10 lg:w-[90%] mx-auto rounded-3xl overflow-hidden">
        <div class="flex flex-col lg:flex-row gap-8 p-6">

            {{-- جانب الـ Accordion --}}
            <div class="lg:w-1/2 w-full space-y-4">
                <h2 class="font-semibold text-3xl lg:text-4xl text-green-900 text-center mb-6">
                   الشركة العربية للإسمنت
                </h2>

                <div class="accordion-item border border-gray-200 rounded-lg overflow-hidden">
                    <button class="accordion-header w-full text-right p-4 bg-green-50 hover:bg-green-100 transition-colors duration-300 flex justify-between items-center cursor-pointer" onclick="toggleAccordion(1)">
                        <span class="font-semibold text-xl text-green-900">
                            {{ app()->getLocale() == 'ar'
                                ? $objectivesPost->postDetailOne->title
                                : ($objectivesPost->postDetailOne->title_en ?? $objectivesPost->postDetailOne->title) }}
                        </span>
                        <svg id="icon-1" class="w-6 h-6 text-green-900 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="content-1" class="accordion-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                        <div class="p-4 bg-white">
                            <div class="content-area font-semibold text-lg text-gray-700">
                                {!! app()->getLocale() == 'ar'
                                    ? $objectivesPost->postDetailOne->content
                                    : ($objectivesPost->postDetailOne->content_en ?? $objectivesPost->postDetailOne->content) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- جانب الصورة --}}
            <div class="w-full lg:w-1/2 flex justify-center items-center">
                <div class="relative inline-block group">
                    <div class="relative z-10 overflow-hidden shadow-lg" style="box-shadow: -20px -18px 4px 1px #2d843d; border-radius: 0px;">
                        @isset($objectivesPost->mediaOne->filepath)
                            <img src="{{ asset($objectivesPost->mediaOne->filepath) }}"
                                 alt="{{ $objectivesPost->mediaOne->alt ?? 'صورة توضيحية' }}"
                                 class="w-full h-80 sm:h-[400px] object-cover block" />
                        @endisset
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endif

</div>

{{-- JavaScript للتحكم في الـ Accordion --}}
<script>
function toggleAccordion(index) {
    const content = document.getElementById(`content-${index}`);
    const icon = document.getElementById(`icon-${index}`);

    const allContents = document.querySelectorAll('.accordion-content');
    const allIcons = document.querySelectorAll('[id^="icon-"]');

    allContents.forEach((item, i) => {
        if (item.id !== `content-${index}`) {
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
    toggleAccordion(1);
});
</script>

<style>
.accordion-content {
    transition: max-height 0.5s ease-in-out;
}
.accordion-header:focus {
    outline: 2px solid #006b36;
    outline-offset: 2px;
}
.content-area ul {
    list-style-type: disc !important;
    padding-right: 2rem !important;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}
.content-area ol {
    list-style-type: decimal !important;
    padding-right: 2rem !important;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}
.content-area li {
    margin-bottom: 0.25rem;
}
[dir="ltr"] .content-area ul, [dir="ltr"] .content-area ol {
    padding-right: 0 !important;
    padding-left: 2rem !important;
}
.content-area {
    font-family: 'Tajawal', sans-serif;
    line-height: 1.8;
}
</style>

@endsection
@section('jsafter')
@endsection
