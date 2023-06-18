<!DOCTYPE html>
<html lang="en">

<head>
    @section('title')
       {{env('APP_NAME')}} forgot Password
    @endsection
    @include('home.inc.cssheader')
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top" data-scrollto-offset="0">
      @include('home.inc.menuheader')
  </header><!-- End Header -->
   <main id="main">
       <!-- ======= Breadcrumbs ======= -->
        @include('home.inc.forgot_passwordbread')
       <!-- End Breadcrumbs -->
   
       <!-- ======= Register Section ======= -->
       <section id="forgot" class="forgot">
             @include('home.inc.forgot_password')
       </section><!-- End Register Section -->

   
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
       @include('home.inc.footer')    
  </footer><!-- End Footer -->

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>
       @include('home.inc.jsfooter')
</body>

</html>