<x-divider>{{ __('adminlte::landingpage.socialmediainfo') }}</x-divider>

<div class="flex flex-col sm:flex-row w-full gap-4 justify-center">

    {{-- <!-- Facebook --><i class="fa-brands fa-facebook fa-flip"></i> --}}
    <div class="flex flex-col sm:flex-row space-x-4 space-y-5 h-fit max-h-full transition-all duration-500"
        id="social-accordion">
        <!-- Facebook -->
        <details open
            class="group transition-all duration-500 border border-base-300 rounded-box bg-blue-500
                         overflow-hidden w-full sm:w-20 open:sm:w-96 h-full">
            <summary
                class="py-3 p-2 flex justify-center h-full sm:justify-center transition-all duration-500 cursor-pointer list-none">
                <i class="fa-brands fa-facebook-square text-4xl text-white"></i>
            </summary>
            <div class="p-0 sm:p-2 transition-all duration-500">

                <iframe
                    src="{{ $socialMediaPages[1]->postDetailOne->content }}"
                    class="w-auto mx-auto h-120 sm:h-126" style="border:none;overflow:hidden" scrolling="no"
                    frameborder="0" allowfullscreen="true"
                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                </iframe>
            </div>
        </details>

        <!-- YouTube -->
        <details
            class="group transition-all duration-500 border border-base-300 rounded-box bg-red-700 overflow-hidden w-full sm:w-20 open:sm:w-96 h-full">
            <summary
                class="py-3 p-2 flex justify-center h-full sm:justify-center transition-all duration-500 cursor-pointer list-none">
                <i class="fa-brands fa-youtube text-4xl text-white"></i>
            </summary>
            <div class="grid grid-cols-1 transition-all duration-500 my-5">
                @for ($i = 1; $i <= 2; $i++)
                    <div class=" w-full">
                        <iframe class="w-full h-60  p-5 rounded-4xl"
                            src="https://www.youtube.com/embed?listType=playlist&list=U{{ $socialMediaPages[2]->postDetailOne->content }}&index={{ $i }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                       
                    </div>
                @endfor
            </div>
        </details>


        <!-- Instagram -->
        <details
            class="group transition-all duration-500 border border-base-300 rounded-box bg-gradient-to-t from-pink-500 via-red-500 to-yellow-500 overflow-hidden w-full sm:w-20 open:sm:w-96 h-full">
            <summary
                class="py-3 p-2 flex justify-center h-full sm:justify-center transition-all duration-500 cursor-pointer list-none">
                <i class="fa-brands fa-instagram text-4xl  text-white"></i>
            </summary>
            <div class="grid grid-cols-1 transition-all duration-500">
                <div class="w-auto lg:w-auto mx-auto transition-all duration-500">
                    <!-- Instagram Embed -->
                    <div class="instagram-wrapper">
                        <blockquote class="instagram-media" style="height:500px;"
                            data-instgrm-permalink="{{ $socialMediaPages[3]->postDetailOne->content }}"
                            data-instgrm-version="14">
                        </blockquote>
                    </div>
                    <script async src="//www.instagram.com/embed.js"></script>
                </div>
            </div>
        </details>


        <!-- Twitter -->
        <details
            class="group transition-all duration-500 border border-base-300 rounded-box bg-gray-800 overflow-hidden w-full sm:w-20 open:sm:w-96 h-full">
            <summary
                class="py-3 p-2 flex justify-center h-full sm:justify-center transition-all duration-500 cursor-pointer list-none">
                <i class="fa-brands fa-x-twitter text-4xl text-white"></i>
            </summary>
            <div class="flex justify-center transition-all duration-500">
                <div class="mx-3 sm:mx-auto my-5 h-115 bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                    <blockquote class="twitter-tweet" >
                        <a href="{{ $socialMediaPages[4]->postDetailOne->content }}">December 20, 2022</a></blockquote> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                </div>
            </div>
        </details>


        <!-- Tiktok -->
        <details
    class="group border border-base-300 rounded-box bg-gray-800 overflow-hidden
           w-full sm:w-20 open:sm:w-96 h-full transition-[width] duration-500">

    <summary
        class="py-3 p-2 flex justify-center h-full cursor-pointer list-none">
        <i class="fa-brands fa-tiktok text-4xl text-white transition group-hover:scale-110"></i>
    </summary>

    <div class="flex justify-center w-full">
        <div
            class="mx-3 sm:mx-auto my-5 w-full max-w-[360px]
                   h-[500px] bg-white rounded-lg shadow-md
                   border border-gray-200 overflow-hidden">

            <blockquote
                class="tiktok-embed"
                cite="https://www.tiktok.com/@aycclyemen?_r=1&_t=ZS-93pxA8snfy4"
                data-unique-id="aycclyemen"
                data-embed-type="creator"
                style="max-width: 360px; min-width: 288px;">
                <section>
                    <a target="_blank"
                       title="@aycclyemen"
                       href="https://www.tiktok.com/@aycclyemen?_r=1&_t=ZS-93pxA8snfy4">
                        @aycclyemen
                    </a>
                </section>
            </blockquote>

        </div>
    </div>
</details>
<script async src="https://www.tiktok.com/embed.js" charset="utf-8"></script>


    </div>
</div>
@section('jsafter')
    <script>
        const accordion = document.getElementById('social-accordion');
        const details = accordion.querySelectorAll('details');

        // Ensure only one open at a time
        details.forEach((detail) => {
            const summary = detail.querySelector('summary');
            const iframe = detail.querySelector('iframe');

            detail.addEventListener('toggle', function() {
                if (this.open) {
                    details.forEach((other) => {
                        if (other !== this) other.open = false;
                    });

                    // Expand summary to full height
                    summary.classList.remove('h-full');

                    // Lazy-load iframe
                    if (iframe && !iframe.src) {
                        iframe.src = iframe.dataset.src;
                    }
                } else {
                    // Reset summary height when closed
                    summary.classList.add('h-full'); // default height
                    // if (iframe) {
                    //     iframe.src = '';
                    // }
                }
            });
        });


        // Close all when clicking outside
        // document.addEventListener('click', (e) => {
        //     if (!accordion.contains(e.target) || !details.contains(e.target)) {
        //         details.forEach((d) => (d.open = false));
        //     }
        // });

        document.addEventListener('click', (e) => {
            // Check if the click happened inside any <details>
            const clickedInsideDetails = Array.from(details).some(d => d.contains(e.target));

            if (!clickedInsideDetails) {
                details.forEach(d => (d.open = false));
            }
        });
    </script>
@endsection
