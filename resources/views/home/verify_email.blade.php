<!DOCTYPE html>
<html lang="en">

<head>
    @section('title')
        {{env('APP_NAME')}}  Verify Email
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
        @include('home.inc.verify_emailbread')
       <!-- End Breadcrumbs -->
   
       <!-- ======= Verify Email Section ======= -->
       <section id="verifyEmail" class="verifyEmail">
             @include('home.inc.verify_email')
       </section><!-- End Verify Email Section -->

   
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