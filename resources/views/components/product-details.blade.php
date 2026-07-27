<div class="max-w-6xl mx-auto px-4 py-10 grid grid-cols-1 lg:grid-cols-2 gap-10 m-10">
    {{-- Left: Product Image --}}
    <div class="flex justify-center items-center bg-base-100 h-full">
        <img src="{{ asset($image) }}" alt="{{ $title }}" class="w-full max-w-md rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
    </div>

    {{-- Right: Product Info --}}
    <div class="space-y-4 ">
        <h1 class="text-3xl font-bold text-gray-800">{{ $title }}</h1>
        <div class="product-description text-gray-700 text-lg leading-relaxed">{!! $description !!}</div>

        @if($specs)
        <div class="mt-4">
            <h2 class="text-xl font-semibold text-gray-700 mb-2">المواصفات:</h2>
            <ul class="list-disc pl-5 text-gray-600 space-y-1">
                @foreach($specs as $spec)
                    <li class="ms-10">{{ $spec }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="mt-6">
            <a href="{{ $downloadLink }}" class="btn btn-info">تحميل المواصفات</a>
        </div>
    </div>
</div>
