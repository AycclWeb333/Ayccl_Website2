@extends('layouts.app')

@section('content')
    <x-hero title="{{ __('adminlte::landingpage.management') }}" description="" img="{{ asset($page->background) }}" />
    <div class="w-[95%] mx-auto " data-aos="fade-up" data-aos-once="false"
        data-aos-duration="700">
        <x-divider>
            {{ __('adminlte::landingpage.managementspeach') }}
        </x-divider>
        <div class="w-[95%] mx-auto m-20 h-auto">
            <div class="card lg:card-side " style="overflow: hidden;">
                {{-- <figure> --}}

                @if (isset($posts[0]) && $posts[0]->mediaOne)
                    <img src="{{ asset($posts[0]->mediaOne->filepath) }}"
                        class="rounded-ss-2xl border-2 border-green-800 box-decoration-slice size-80 mx-auto lg:size-130" alt="Album" />
                @endif

                {{-- </figure> --}}
                <div class="card-body leading-relaxed h-fit my-auto text-black font-bold bg-contain "style="background-image: url('{{ asset('images/backgrounds/subtle-prism.svg') }}');"
                    data-aos="fade-right" data-aos-once="false" data-aos-duration="700">
                    @if (isset($posts[0]))
                        <p class="text-base leading-relaxed md:text-sm " data-aos="fade-up" data-aos-once="false"
                            data-aos-duration="1000">
                            {!! $posts[0]->postDetailOne->content !!}
                        </p>
                        <p class= " text-lg text-center ">{!! $posts[0]->postDetailOne->title !!}</p>
                        <p class= " text-base text-center">{!! $posts[0]->description !!}</p>
                    @endif

                </div>
            </div>
        </div>
        <x-divider>
            {{ __('adminlte::landingpage.management') }}
        </x-divider>

        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 justify-items-center px-4">
            @foreach ($posts->skip(1) as $post)
                <x-management-card :id="$post->id" :name="$post->postDetailOne->title" :work="$post->postDetailOne->content" :image="$post->mediaOne ? asset($post->mediaOne->filepath) : asset('images/default-avatar.png')" />
            @endforeach
        </div>
    </div>
@endsection
@section('jsafter')
    {{-- <livewire:carousel />  --}}
@endsection
{{--     
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 justify-items-center px-4">
        @foreach ($workers as $worker)
            <x-management-card 
            :id="$worker->id" 
            :name="$worker->pname" 
            :work="$worker->work" 
            :image="asset($worker->pimage)" 
             />
        @endforeach
    </div> --}}
