<!DOCTYPE html>
<html lang="en">

<head>
    @section('title')
       {{env('APP_NAME')}} Sign In
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
        @include('home.inc.loginbread')
       <!-- End Breadcrumbs -->
   
       <!-- ======= Register Section ======= -->
       <section id="signin" class="signin">
             @include('home.inc.login')
       </section><!-- End Register Section -->

   
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
       @include('home.inc.footer')    
  </footer><!-- End Footer -->

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>
       @include('home.inc.jsfooter')
       <!-- Go top -->
<script>
    window.scrollTo(0, 0);
</script>    
</body>
    
</html>