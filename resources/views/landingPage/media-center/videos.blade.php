@extends('layouts.app')

@section('css')
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mixitup/3.3.1/mixitup.min.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
@endsection

@section('content')
    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

    <div class="w-[95%] md:w-[90%] lg:w-[95%] mx-auto my-20">

        <div class="tabs tabs-boxed bg-base-200/90 w-fit space-x-2">
            <a class="tab bg-emerald-300 text-black font-bold" data-filter="all">{{ __('adminlte::landingpage.all') }}</a>
            @foreach ($categories as $category)
                <a class="tab bg-transparent text-black" data-filter=".{{ str_replace(' ', '_', $category->name) }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
        <div id="videos-grid" class="my-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6 justify-items-center-safe">
            @foreach ($posts as $post)
                <div
                    class="mix {{ str_replace(' ', '_', $post->postDetailOne->category->name) }} relative size-fit space-y-3 shadow-md hover:shadow-2xl transition-all duration-300 rounded-4xl">
                    <div class="relative ">
                        @if($post->mediaOne)
                            <img src="{{ asset($post->mediaOne->thumbnailpath) }}" alt="{{ $post->mediaOne->alt }}"
                                class="size-fit rounded-xl shadow-lg " />
                        @endif

                        {{-- Play button --}}
                        <button id="open-video-modal" type="button"
                            class="open-video-modal btn absolute top-0 h-full w-full flex items-center justify-center text-white text-2xl bg-transparent 
                        hover:bg-black/50 transition-all duration-300 group"
                            data-videoid="{{ $post->mediaOne->filepath ?? '' }}">
                            <x-heroicon-m-play class="w-10 h-10 opacity-0 group-hover:opacity-100" />
                        </button>
                    </div>
                    @isset($post->postDetailOne)
                        <div class="card-body space-y-1">
                            <h2 class="card-title justify-center text-lg sm:text-2xl text-emerald-800 mt-0">
                                {{ $post->postDetailOne->title }}
                            </h2>
                            <h2 class="justify-center m-0 p-2 w-fit bg-emerald-800  rounded-2xl text-white font-bold">
                                {{ $post->postDetailOne->category->name }}
                            </h2>
                            @if(!empty($post->date))
                                <h2 class="justify-start font-bold">
                                    {{ $post->date }}
                                </h2>
                            @endif
                            <!-- Album Details -->
                            @if(!empty($post->postDetailOne->content))
                                <div class="font-bold">
                                    <p>{!! ($post->postDetailOne->content) ?? __('adminlte::landingpage.nodescription') !!}</p>
                                </div>
                            @endif
                        </div>
                    @endisset
                </div>
            @endforeach
        </div>

        {{-- Modal --}}
        <dialog id="video_modal" class="modal modal-bottom sm:modal-middle">
            <div class="modal-action absolute top-2 right-2 z-50">
                <form method="dialog">
                    <button class="btn text-2xl btn-circle bg-red-900/75 hover:bg-red-600/90 border-0 text-white">✕</button>
                </form>
            </div>

            <div class="modal-box relative sm:!w-[90vw] sm:!max-w-6xl !h-[80vh] p-0">
                <div id="player" class="w-full h-full"></div>
            </div>
        </dialog>

    </div>
@endsection

@section('jsafter')

    {{-- Mixitup Filter --}}
    <script>
        // MixItUp filter initialization
        var containerEl = document.querySelector('#videos-grid');
        var mixer = mixitup(containerEl, {
            selectors: {
                target: '.mix'
            },
            animation: {
                duration: 300
            }
        });

        // Tabs active state
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(item => {
                        item.classList.remove('bg-emerald-900', 'text-white');
                        item.classList.add('bg-transparent', 'text-black');
                    });
                    this.classList.remove('bg-transparent', 'text-black');
                    this.classList.add('bg-emerald-900', 'text-white');
                });
            });
        });
    </script>

    {{-- Youtube video modal  --}}
    <script>
        let player;
        let videoId = '';
        const modal = document.getElementById('video_modal');
        const playerContainer = document.getElementById('player');

        // Click handler for all video buttons
        document.querySelectorAll('.open-video-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                videoId = btn.dataset.videoid;
                modal.showModal();
                createPlayer();
            });
        });
        modal.addEventListener('click', (event) => {
            const modalBox = modal.querySelector('.modal-box');
            if (!modalBox.contains(event.target)) {
                modal.close();
            }
        });

        // Create YouTube player
        function createPlayer() {
            if (typeof YT !== 'undefined' && YT.Player) {
                // Clear previous iframe if any
                playerContainer.innerHTML = '';
                player = new YT.Player('player', {
                    videoId: videoId,
                    playerVars: {
                        autoplay: 1,
                        playsinline: 1
                    }
                });
            }
        }

        // Stop and destroy video on close
        modal.addEventListener('close', () => {
            if (player) {
                player.stopVideo();
                player.destroy();
                player = null;
            }
        });

        // Required by YT API
        window.onYouTubeIframeAPIReady = function() {
            // Player will be created when modal opens
        };
    </script>
@endsection
