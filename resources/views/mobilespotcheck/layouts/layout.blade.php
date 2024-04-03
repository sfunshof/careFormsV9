<!DOCTYPE html>
<html lang="en"  class="h-100">
    <head>
        {{-- Required Meta Tags --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- PWA  --}}
        <meta name="theme-color" content="#6777ef"/>
        <meta name="apple-mobile-web-app-status-bar" content="#6777ef"/>
        <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
        <link rel="manifest" href="{{ asset('/manifest.json') }}">
        <link rel="icon" type="image/png" href="{{asset('logo.png')}}">
        {{--  End PWA --}}
        <meta name="description" content="" />
        <meta name="keywords" content="">
        <meta name="author" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
              
        @include('mobilespotcheck.inc.cssheader')
        {{-- CSS Custom --}}
        @yield('css-custom')
        {{-- End CSS Custom --}}
               
        <title>
            @yield('title')
        </title>
        {{--
           <script src="https://cdn.jsdelivr.net/npm/vue@3.3.8/dist/vue.global.prod.js"></script>
        --}} 
        <script src="https://unpkg.com/vue@3.2.5/dist/vue.global.js"></script>  
    </head>  
    <body class="p-3 parent"> 
        {{--  Include any navigation --}}
        <header>
            @yield('header-contents') 
        </header>
        {{--  End navigation --}}    

        {{--  Put the contents here --}}
        <div class="child mt-5">
            @yield('contents')
        </div>    
        {{--  End the contents here --}}

        <footer class="fixed-bottom bg-dark text-white text-center">
            @yield('footer-contents')      
        </footer>   
        @include('mobilespotcheck.inc.jscustom')
        @stack('scripts')
        {{-- End JSCustoms --}}
        

    </body>
</html>
