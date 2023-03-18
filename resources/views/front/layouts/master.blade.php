<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @yield('meta')

        <title>Zgubione/znalezione tablice rejestracyjne</title>

        @vite(['resources/scss/front/master.scss'])

        <link rel="preload" href="{{ Vite::asset('resources/fonts/BowlbyOneSC-Regular.ttf') }}" as="font" crossorigin="*">
        <link rel="preload" href="{{ Vite::asset('resources/fonts/Montserrat-Regular.ttf') }}" as="font" crossorigin="*">
        <link rel="preload" href="{{ Vite::asset('resources/fonts/Montserrat-Bold.ttf') }}" as="font" crossorigin="*">

        @if (app()->isProduction())
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{ config('services.google.tag_manager_container_id') }}');</script>
            <!-- End Google Tag Manager -->
        @endif
    </head>
    <body class=" @yield('body-class') ">
        @if (app()->isProduction())
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('services.google.tag_manager_container_id') }}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
        @endif
        <div id="lost-plates" class="lost-plates min-h-screen flex flex-col">
            @include('front.partials.header')
            @include('front.partials.sidebar')

            <div class="mt-14">
                @yield('content')
            </div>

            <gallery-preview></gallery-preview>
        </div>

        <script>
            window.globals = {
                googleCloudApiKey: '{{ config('services.google.cloud_api_key') }}',
                locale: '{{ app()->getLocale() }}',
            };
        </script>
        @vite(['resources/js/front/master.js'])
        @yield('scripts')
    </body>
</html>