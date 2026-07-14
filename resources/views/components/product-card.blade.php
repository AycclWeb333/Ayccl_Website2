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

<!-- كرت المنتج -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
    
    <!-- 1. حاوية الصورة - تحدد نسبة العرض إلى الارتفاع وتمنع خروج أي جزء زائد -->
    <div class="relative w-full aspect-square bg-gray-100 overflow-hidden">
        
        <!-- 2. عنصر الصورة - يملأ المساحة بالكامل بذكاء -->
        <img 
            src="{{ asset('storage/' . $product->image) }}" 
            alt="{{ $product->name }}" 
            class="absolute inset-0 w-full h-full object-contain transition-transform duration-300 hover:scale-105"
            loading="lazy"
        />
        
    </div>

    <!-- تفاصيل المنتج (الاسم والسعر) -->
    <div class="p-4 flex flex-col flex-grow">
        <h3 class="text-gray-800 font-semibold text-sm line-clamp-2 mb-2">
            {{ $product->name }}
        </h3>
        <div class="mt-auto">
            <span class="text-primary font-bold">{{ $product->price }} ر.ي</span>
        </div>
    </div>
</div>
