<div class="relative bg-transparent rounded-xl overflow-hidden  max-w-xs " data-aos="fade-up"
    data-aos-once="true" data-aos-duration="700">
    {{-- Gray bottom background layer (half card height) --}}
    <div class="absolute bottom-0 left-0 w-full h-1/2 z-0"></div>

    {{-- Inner wrapper for hover transition --}}
    <div
        class="relative z-10 flex flex-col items-center text-center p-4 transition-transform duration-500 ease-in-out hover:scale-105">
        {{-- Image section: stands out, partially over gray --}}
        <div class="w-full h-56 mb-4 flex justify-center items-end">
            <img src="{{ $image }}" alt="{{ $name }}" class="max-h-full object-contain" />
        </div>

        {{-- Name --}}
        {{-- Button --}}
        {{-- @livewire('products-page') --}}
        <h3 class="text-xl font-semibold mb-2 text-gray-900">{{ $name }}</h3>
    </div>
</div>
