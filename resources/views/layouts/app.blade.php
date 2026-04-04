<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
    data-theme="emerald">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AYCCL</title>
    <link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}">


    <!-- Fonts -->
    <!-- <link rel="preconnect" href="https://fonts.bunny.net"> -->
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="/vendor/flag-icon-css/css/flag-icon.min.css" />
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        {{-- @vite(['resources/sass/app.scss']) --}}
    @endif
    @livewireStyles
    {{-- @vite(['resources/css/owl.defaultcarousel.css', 'resources/css/owl.carousel.css']) --}}

    {{-- <style>
        
        .btn .btn-ghost {
            @apply transition delay-150 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110 hover:bg-indigo-500;
            } 
        </style> --}}
    {{-- {{-- <!-- jQuery (must be first) --> --}}
    
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" />
    <style>
        /*
         .pattern {
            background-color: #dedede;
            /* background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' version='1.1' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:svgjs='http://svgjs.dev/svgjs' width='800' height='250' preserveAspectRatio='none' viewBox='0 0 800 250'%3e%3cg mask='url(%26quot%3b%23SvgjsMask4448%26quot%3b)' fill='none'%3e%3crect width='800' height='250' x='0' y='0' fill='url(%26quot%3b%23SvgjsLinearGradient4449%26quot%3b)'%3e%3c/rect%3e%3cpath d='M12 250L262 0L415 0L165 250z' fill='url(%26quot%3b%23SvgjsLinearGradient4450%26quot%3b)'%3e%3c/path%3e%3cpath d='M189 250L439 0L574 0L324 250z' fill='url(%26quot%3b%23SvgjsLinearGradient4450%26quot%3b)'%3e%3c/path%3e%3cpath d='M379 250L629 0L888.5 0L638.5 250z' fill='url(%26quot%3b%23SvgjsLinearGradient4450%26quot%3b)'%3e%3c/path%3e%3cpath d='M772 250L522 0L325.5 0L575.5 250z' fill='url(%26quot%3b%23SvgjsLinearGradient4451%26quot%3b)'%3e%3c/path%3e%3cpath d='M618 250L368 0L195.5 0L445.5 250z' fill='url(%26quot%3b%23SvgjsLinearGradient4451%26quot%3b)'%3e%3c/path%3e%3cpath d='M426 250L176 0L54.5 0L304.5 250z' fill='url(%26quot%3b%23SvgjsLinearGradient4451%26quot%3b)'%3e%3c/path%3e%3cpath d='M610.4469702576497 250L800 60.446970257649724L800 250z' fill='url(%26quot%3b%23SvgjsLinearGradient4450%26quot%3b)'%3e%3c/path%3e%3cpath d='M0 250L189.55302974235028 250L 0 60.446970257649724z' fill='url(%26quot%3b%23SvgjsLinearGradient4451%26quot%3b)'%3e%3c/path%3e%3c/g%3e%3cdefs%3e%3cmask id='SvgjsMask4448'%3e%3crect width='800' height='250' fill='white'%3e%3c/rect%3e%3c/mask%3e%3clinearGradient x1='0%25' y1='50%25' x2='100%25' y2='50%25' gradientUnits='userSpaceOnUse' id='SvgjsLinearGradient4449'%3e%3cstop stop-color='rgba(216%2c 255%2c 211%2c 0.11)' offset='0'%3e%3c/stop%3e%3cstop stop-color='rgba(0%2c 62%2c 22%2c 0.05)' offset='0'%3e%3c/stop%3e%3cstop stop-color='rgba(0%2c 62%2c 22%2c 0.05)' offset='1'%3e%3c/stop%3e%3c/linearGradient%3e%3clinearGradient x1='0%25' y1='100%25' x2='100%25' y2='0%25' id='SvgjsLinearGradient4450'%3e%3cstop stop-color='rgba(22%2c 153%2c 74%2c 0.33)' offset='0'%3e%3c/stop%3e%3cstop stop-opacity='0' stop-color='rgba(22%2c 153%2c 74%2c 0.33)' offset='0.66'%3e%3c/stop%3e%3c/linearGradient%3e%3clinearGradient x1='100%25' y1='100%25' x2='0%25' y2='0%25' id='SvgjsLinearGradient4451'%3e%3cstop stop-color='rgba(22%2c 153%2c 74%2c 0.33)' offset='0'%3e%3c/stop%3e%3cstop stop-opacity='0' stop-color='rgba(22%2c 153%2c 74%2c 0.33)' offset='0.66'%3e%3c/stop%3e%3c/linearGradient%3e%3c/defs%3e%3c/svg%3e"); 
            background-image: url("data:image/svg+xml,<svg id='patternId' width='100%' height='100%' xmlns='http://www.w3.org/2000/svg'><defs><pattern id='a' patternUnits='userSpaceOnUse' width='80' height='167.624' patternTransform='scale(2) rotate(0)'><rect x='0' y='0' width='100%' height='100%' fill='%23ffffff00'/><path d='m25.394 135.647-6.349 14.817L40 199.375l20.954-48.91-6.348-14.818L40 169.74zM40 32.786 31.742 52.06 40 71.335l8.258-19.275zM25.394 66.879 40 100.972 54.606 66.88l-6.348-14.82L40 71.335 31.742 52.06zm29.212-29.638L48.258 52.06l6.348 14.82 6.348-14.82zM25.394 66.88l6.348-14.819-6.348-14.819-6.349 14.819zm-6.349-14.82 6.349-14.819-6.349-14.818-6.348 14.82zm35.561-14.819 6.348 14.819 6.35-14.817-6.35-14.82zM25.394 66.88l-6.349 14.818L40 130.608l20.954-48.911-6.348-14.818L40 100.972zm0 0-6.349-14.82-6.348 14.818 6.348 14.819zm29.212 0 6.348 14.818 6.35-14.819-6.35-14.818zm12.697-29.636L60.954 52.06l6.35 14.818L73.65 52.06zm0 0 6.348 14.817L80 37.241l-6.348-14.818zm0 29.635-6.349 14.819 6.35 14.818 6.348-14.818zm0 0 6.349 14.819L80 66.879 73.65 52.06zm0 29.637 6.348 14.82L80 96.514l-6.348-14.818zm-41.91 39.357-6.348-14.819-6.348 14.82 6.348 14.817zm29.213 0 6.348 14.818 6.35-14.817-6.35-14.82zm12.697.001 6.348 14.817L80 135.872l-6.348-14.819zM25.393-31.75l-6.348 14.817L40 31.977l20.954-48.91-6.348-14.818L40 2.342zM12.697 66.88l6.348-14.819-6.348-14.817L6.348 52.06zm0-29.636-6.35-14.82L0 37.24l6.348 14.82zm0 59.272 6.348-14.818-6.348-14.819-6.35 14.819zm0-29.637L6.348 52.06 0 66.88l6.348 14.817zm0 29.637-6.35-14.818L0 96.515l6.348 14.82zm0 39.358-6.35-14.82L0 135.872l6.348 14.818z'  stroke-linecap='square' stroke-width='1' stroke='%2316791976' fill='none'/></pattern></defs><rect width='800%' height='800%' transform='translate(0,-100.496)' fill='url(%23a)'/></svg>");
            background-attachment: fixed;
            background-size: cover;
        } */
        ul.menu li {
    padding: 0 !important;
    margin: 0 !important;
}
    .special_font_nasser {
        font-family: 'Changa', sans-serif !important;
    }
    body {
        font-family: 'Changa', sans-serif !important;
    }
    </style>
    @yield('css')
</head>

<body>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            // duration: 800, // animation duration
            once: true // animation only once when scrolling down
        });
    </script>

    @livewireScripts
    @yield('jsbefore')
    {{-- <div id="app"> --}}
        @include( 'daisyUI.navbar-upper-2')
        @include( 'daisyUI.floating-button')
    <main>

        @yield('content')
        
    </main>

    @include('daisyUI.footer')
    {{-- </div> --}}
    @yield('jsafter')
    
   
    
</body>

</html>
