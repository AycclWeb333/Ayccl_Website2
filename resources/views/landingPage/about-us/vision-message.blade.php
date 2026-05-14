@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <!-- <x-hero title="{{ __('adminlte::landingpage.products') }}" description="" img="{{ asset('/images/news1.png') }}" /> -->

    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />
    {{-- <div class="w-[85%] sm:w-[100%] md:w-[80%] lg:w-[90%] mx-auto " data-aos="fade-up" data-aos-duration="700">

        <div class="card sm:card-side bg-base-100 shadow-lg m-10 lg:w-[90%] justify-center mx-auto">
            <div class="card-body my-auto space-y-10 w-full sm:w-[40%]">
                <h2 class="font-semibold text-5xl text-green-900 text-center">{{ $posts[0]->postDetailOne->title }}</h2>
                <p class="font-semibold  {{ isset($posts[0]->mediaOne->filepath) ? 'text-xl':' text-3xl text-center' }}">{!! $posts[0]->postDetailOne->content !!}</p>
            </div>
            @isset($posts[0]->mediaOne->filepath)
                <figure class="w-full sm:w-[60%] relative ">
                    <img src="{{ asset($posts[0]->mediaOne->filepath) }}" alt="{{ $posts[0]->mediaOne->alt }}" />

                    <div
                        class="absolute
                    {{ app()->getlocale() == 'ar' ? 'bg-gradient-to-l left-[60%]  ' : 'bg-gradient-to-r right-[60%]  ' }} from-green-900 to-transparent
                    rounded-medium">
                    </div>
                </figure>

            @endisset
        </div>


        <div class="bg-base-100 shadow-lg m-15 lg:w-[90%] mx-auto rounded-3xl">
            <div class="flex flex-col lg:flex-row justify-center items-start gap-8 px-4 py-6">


                <div class="lg:w-1/2 w-full h-full my-auto">
                    <h2 class="font-semibold text-3xl lg:text-5xl text-green-900 text-center m-2">
                        {{ $posts[1]->postDetailOne->title }}
                    </h2>
                    <p class="font-semibold text-lg lg:text-xl" data-aos="fade-up" data-aos-once="true"
                        data-aos-duration="700">
                        {!! nl2br($posts[1]->postDetailOne->content) !!}
                    </p>
                </div>


                <div class="lg:w-1/2 w-full">
                    <h2 class="font-semibold text-3xl lg:text-5xl text-green-900 text-center m-2">
                        {{ $posts[2]->postDetailOne->title }}
                    </h2>
                    <ol class="list-disc list-inside mt-4 space-y-2" data-aos="fade-up" data-aos-once="true"
                        data-aos-duration="700">
                        <p class="font-semibold text-lg lg:text-xl" data-aos="fade-up" data-aos-once="true"
                            data-aos-duration="700">
                            {!! nl2br($posts[2]->postDetailOne->content) !!}
                        </p>
                    </ol>
                </div>

            </div>
        </div>





    </div> --}}

<div class="w-[85%] sm:w-[100%] md:w-[80%] lg:w-[90%] mx-auto" data-aos="fade-up" data-aos-duration="700">

    {{-- القسم الأول: العنوان الرئيسي مع الصورة --}}


    {{-- القسم التفاعلي: Accordion مع الصورة والإطار الأزرق --}}
    <div class="bg-base-100 shadow-lg m-10 lg:w-[90%] mx-auto rounded-3xl overflow-hidden">
        <div class="flex flex-col lg:flex-row gap-8 p-6">

            {{-- جانب الـ Accordion --}}
            <div class="lg:w-1/2 w-full space-y-4">
                <h2 class="font-semibold text-3xl lg:text-4xl text-green-900 text-center mb-6">
                   الشركة العربية للإسمنت
                </h2>

                {{-- القسم الأول --}}
                <div class="accordion-item border border-gray-200 rounded-lg overflow-hidden">
                    <button class="accordion-header w-full text-right p-4 bg-green-50 hover:bg-green-100 transition-colors duration-300 flex justify-between items-center cursor-pointer" onclick="toggleAccordion(1)">
                        <span class="font-semibold text-xl text-green-900 ">{{ $posts[0]->postDetailOne->title }}</span>
                        <svg id="icon-1" class="w-6 h-6 text-green-900 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                        <div id="content-1" class="accordion-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                        <div class="p-4 bg-white">
                            <div class="font-semibold text-lg text-gray-700">
                                {!! $posts[0]->postDetailOne->content !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- القسم الثاني --}}
                <div class="accordion-item border border-gray-200 rounded-lg overflow-hidden">
                    <button class="accordion-header w-full cursor-pointer text-right p-4 bg-green-50 hover:bg-green-100 transition-colors duration-300 flex justify-between items-center" onclick="toggleAccordion(2)">
                        <span class="font-semibold text-xl text-green-900 ">{{ $posts[1]->postDetailOne->title }}</span>
                        <svg id="icon-2" class="w-6 h-6 text-green-900 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="content-2" class="accordion-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                        <div class="p-4 bg-white">
                            <div class="font-semibold text-lg text-gray-700">
                                {!! $posts[1]->postDetailOne->content !!}
                            </div>
                        </div>
                    </div>
                </div>
                 {{-- القسم الثالث --}}
                <div class="accordion-item border border-gray-200 rounded-lg overflow-hidden">
                    <button class="accordion-header w-full cursor-pointer text-right p-4 bg-green-50 hover:bg-green-100 transition-colors duration-300 flex justify-between items-center" onclick="toggleAccordion(3)">
                        <span class="font-semibold text-xl text-green-900">{{ $posts[2]->postDetailOne->title }}</span>
                        <svg id="icon-3" class="w-6 h-6 text-green-900 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="content-3" class="accordion-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                        <div class="p-4 bg-white">
                            <div class="font-semibold text-lg text-gray-700">
                                {!! $posts[2]->postDetailOne->content !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- يمكنك إضافة المزيد من الأقسام هنا --}}
            </div>

            {{-- جانب الصورة مع الإطار الأزرق --   old section of image}}
            {{-- <div class="lg:w-1/2 w-full flex items-center justify-center relative">
                <div class="relative w-full max-w-md">

                    <div class="absolute {{ app()->getlocale() == 'ar' ? '-right-6 -bottom-6' : '-left-6 -bottom-6' }} w-full h-full bg-blue-500 rounded-2xl -z-10"></div>


                    @isset($posts[0]->mediaOne->filepath)
                        <img src="{{ asset($posts[0]->mediaOne->filepath) }}"
                             alt="{{ $posts[0]->mediaOne->alt ?? 'صورة توضيحية' }}"
                             class="w-full h-auto rounded-2xl shadow-2xl relative z-10 object-cover"
                             data-aos="fade-{{ app()->getlocale() == 'ar' ? 'right' : 'left' }}"
                             data-aos-duration="700">
                    @else

                        <div class="w-full h-96 bg-gradient-to-br from-green-400 to-blue-500 rounded-2xl shadow-2xl relative z-10 flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">صورة توضيحية</span>
                        </div>
                    @endisset
                </div>
            </div> --}}

                <div class="w-full lg:w-1/2 flex justify-center items-center">
                            <div class="relative inline-block group">

                                <div class="relative z-10 overflow-hidden shadow-lg" style="box-shadow: -20px -18px 4px 1px #2d843d; border-radius: 0px;">
                                    @isset($posts[0]->mediaOne->filepath)
                        <img src="{{ asset($posts[0]->mediaOne->filepath) }}"
                             alt="{{ $posts[0]->mediaOne->alt ?? 'صورة توضيحية' }}"
                                         class="w-full h-80 sm:h-[400px] object-cover block" />
                                       @endisset
                                </div>
                            </div>
                        </div>

        </div>
    </div>

</div>

{{-- JavaScript للتحكم في الـ Accordion --}}
<script>
function toggleAccordion(index) {
    const content = document.getElementById(`content-${index}`);
    const icon = document.getElementById(`icon-${index}`);

    // إغلاق جميع الأقسام الأخرى
    const allContents = document.querySelectorAll('.accordion-content');
    const allIcons = document.querySelectorAll('[id^="icon-"]');

    allContents.forEach((item, i) => {
        if (item.id !== `content-${index}`) {
            item.style.maxHeight = '0px';
            allIcons[i].style.transform = 'rotate(0deg)';
        }
    });

    // فتح أو إغلاق القسم الحالي
    if (content.style.maxHeight && content.style.maxHeight !== '0px') {
        content.style.maxHeight = '0px';
        icon.style.transform = 'rotate(0deg)';
    } else {
        content.style.maxHeight = content.scrollHeight + 'px';
        icon.style.transform = 'rotate(180deg)';
    }
}

// فتح القسم الأول افتراضياً عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    toggleAccordion(1);
});
</script>

<style>
/* تحسينات إضافية للـ Accordion */
.accordion-content {
    transition: max-height 0.5s ease-in-out;
}

.accordion-header:focus {
    outline: 2px solid #006b36;
    outline-offset: 2px;
}

/* تأثير hover للإطار الأزرق */
.relative:hover .bg-blue-500 {
    transform: translate(4px, 4px);
    transition: transform 0.3s ease;
}

/* تأثيرات إضافية */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.accordion-content[style*="max-height: 0px"] {
    animation: none;
}

.accordion-content:not([style*="max-height: 0px"]) .p-4 {
    animation: slideDown 0.3s ease-out;
}
</style>



@endsection
@section('jsafter')
@endsection
