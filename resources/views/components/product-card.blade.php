<!-- <div class="relative bg-transparent rounded-xl overflow-hidden  max-w-xs " data-aos="fade-up"
    data-aos-once="true" data-aos-duration="700">
    {{-- Gray bottom background layer (half card height) --}}
    <div class="absolute bottom-0 left-0 w-full h-1/2 z-0"></div>

    {{-- Inner wrapper for hover transition --}}
    <div
        class="relative z-10 flex flex-col items-center text-center p-4 transition-transform duration-500 ease-in-out hover:scale-105">
        {{-- Image section: stands out, partially over gray --}} -->


        <!-- <div class="w-full h-64 md:h-80 lg:h-96 mb-4 flex justify-center items-end">
            <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-full object-contain object-bottom transition-transform duration-500 ease-in-out hover:scale-110" />
        </div> -->


        <!-- <div class="w-full h-72 md:h-80 lg:h-80 mb-4 flex justify-center items-end">
            <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-full object-contain object-bottom transition-transform duration-500 ease-in-out hover:scale-110" />
        </div>

        {{-- Name --}}
        {{-- Button --}}
        {{-- @livewire('products-page') --}}
        <h3 class="text-xl font-semibold mb-2 text-gray-900">{{ $name }}</h3>
    </div>
</div> -->

<div class="relative bg-transparent rounded-xl overflow-hidden max-w-xs" data-aos="fade-up"
    data-aos-once="true" data-aos-duration="700">
    {{-- Gray bottom background layer (half card height) --}}
    <div class="absolute bottom-0 left-0 w-full h-1/2 z-0"></div>

    {{-- Inner wrapper for hover transition --}}
    <div
        class="relative z-10 flex flex-col items-center text-center p-4 transition-transform duration-500 ease-in-out hover:scale-105">
        
        {{-- 
            تعديل حاوية الصورة:
            استخدمنا نسبة أبعاد ثابتةaspect-[3/4] (عرض 3 إلى ارتفاع 4) 
            لتوحيد مساحة العرض في كل الشاشات المتوسطة والكبيرة تلقائياً.
        --}}
        <div class="w-full aspect-[3/4] mb-4 flex justify-center items-end overflow-hidden">
            {{-- 
                استخدمنا object-cover لملء المساحة المحددة بذكاء ودون تمدد، 
                مع الحفاظ على التمركز في الأسفل object-bottom.
            --}}
            <img src="{{ $image }}" alt="{{ $name }}" 
                 class="w-full h-full object-contain object-bottom transition-transform duration-500 ease-in-out hover:scale-110" />
        </div>

        {{-- Name --}}
        <h3 class="text-xl font-semibold mb-2 text-gray-900">{{ $name }}</h3>
    </div>
</div>

