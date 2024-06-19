<!DOCTYPE html>
<html lang="en">

<head>
    
    @section('title')
       {{env('APP_NAME')}} Home
    @endsection
    @include('home.inc.cssheader')
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top" data-scrollto-offset="0">
      @include('home.inc.menuheader')
  </header><!-- End Header -->

  <main id="main">
    
    <section id="hero-animated" class="hero-animated d-flex align-items-center">
       @include('home.inc.hero')
    </section>

    <!-- ======= Problem Section ======= -->
    <section id="problem" class="about">
        @include('home.inc.problem')
    </section><!-- End About Section -->
  
    <!-- ======= Featured Services Section ======= -->
    <section id="featured-services" class="featured-services">
        @include('home.inc.featured_services')
    </section><!-- End Featured Services Section -->

    <!-- ======= Testimonials Section ======= -->
    <section id="testimonials" class="testimonials">
        @include('home.inc.testimonials')
    </section><!-- End Testimonials Section -->
    
    <!-- ======= Features Section ======= -->
    <section id="features" class="features">
        @include('home.inc.features')
    </section><!-- End Features Section -->

    <!-- ======= F.A.Q Section ======= -->
   <section id="faq" class="faq">
         @include('home.inc.faq')
   </section><!-- End F.A.Q Section -->

    <!-- ======= Call To Action Section ======= -->
       <section id="cta" class="cta">
        @include('home.inc.cta')
    </section><!-- End Call To Action Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
        @include('home.inc.contact')
    </section><!-- End Contact Section -->    

    {{--
    <!-- ======= About Section ======= -->
    <section id="about" class="about">
         @include('home.inc.about')
    </section><!-- End About Section -->
    
    
   

    
    
    <!-- ======= On Focus Section ======= -->
    <section id="onfocus" class="onfocus">
        @include('home.inc.onfocus')
    </section><!-- End On Focus Section -->
    

   
    <!-- ======= Services Section ======= -->
    <section id="services" class="services">
        @include('home.inc.services')
    </section><!-- End Services Section -->
    
   
  

   <!-- ======= Pricing Section ======= -->
   <section id="pricing" class="pricing">
       @include('home.inc.pricing')
   </section><!-- End Pricing Section -->
   


   --}}

   <div class="modal modal-frame" data-mdb-sticky-init data-mdb-sticky-position="bottom">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cookie Consent</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>We use cookies to enhance your experience on our website. By continuing to browse, you consent to our use of cookies.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="setCookie('consent', 'true', 365);">Accept</button>
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Customize</button>
            </div>
        </div>
    </div>
</div>
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