<!DOCTYPE html>
<html lang="en"  class="h-100">
    <head>
        {{-- Required Meta Tags --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="" />
        <meta name="keywords" content="">
        <meta name="author" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        
        {{-- CSS Header --}}
        @include('mobile.inc.cssheader')
        {{--- End CSS Header --}}
            
       
        <title>
            @yield('title')
        </title>
        
        {{-- Favicon --}}
        <link rel="icon" type="image/png" href="{{asset('assets/img/favicon.png')}}">
    </head>
    <body class="d-flex flex-column h-100 h-sbar" > 

        <div class="container-fluid rounded-top rounded-bottom mt-1">
            {{-- Header --}}
            <div class="header bg-primary text-white text-center text-lg-start pt-2 pb-2 ">
                @include ('mobile.inc.header')   
            </div>   
            {{-- End Header --}}

            {{-- Page Content --}}
            @yield('contents')
            {{-- End Page Content --}}
        </div>
        <footer class="footer mt-auto  bg-white ">
            <div class="container">
                <div class="row">
                    {{-- Footer Area --}}
                    @include('mobile.inc.footer')
                    {{-- End Footer Area --}}
                </div>
            </div>      
        </footer>   


        {{-- JS Ordinary  --}} 
        @include('mobile.inc.jsfooter')
        {{-- End JS ordinary --}}
        
    </body>
</html>