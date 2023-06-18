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

  <section id="hero-animated" class="hero-animated d-flex align-items-center">
      @include('home.inc.hero')
  </section>

  <main id="main">

    <!-- ======= Featured Services Section ======= -->
    <section id="featured-services" class="featured-services">
        @include('home.inc.featured_services')
    </section><!-- End Featured Services Section -->

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
         @include('home.inc.about')
    </section><!-- End About Section -->

    <!-- ======= Clients Section ======= -->
    <section id="clients" class="clients">
         @include('home.inc.clients')
    </section><!-- End Clients Section -->

    <!-- ======= Call To Action Section ======= -->
    <section id="cta" class="cta">
        @include('home.inc.cta')
    </section><!-- End Call To Action Section -->
    
    
    <!-- ======= On Focus Section ======= -->
    <section id="onfocus" class="onfocus">
        @include('home.inc.onfocus')
    </section><!-- End On Focus Section -->

    <!-- ======= Features Section ======= -->
    <section id="features" class="features">
        @include('home.inc.features')
   </section><!-- End Features Section -->
   
   <!-- ======= Services Section ======= -->
   <section id="services" class="services">
        @include('home.inc.services')
   </section><!-- End Services Section -->
    
   
   <!-- ======= Testimonials Section ======= -->
   <section id="testimonials" class="testimonials">
       @include('home.inc.testimonials')
   </section><!-- End Testimonials Section -->

   <!-- ======= Pricing Section ======= -->
   <section id="pricing" class="pricing">
       @include('home.inc.pricing')
   </section><!-- End Pricing Section -->
   
   <!-- ======= F.A.Q Section ======= -->
   <section id="faq" class="faq">
       @include('home.inc.faq')
   </section><!-- End F.A.Q Section -->
   
   <!-- ======= Contact Section ======= -->
   <section id="contact" class="contact">
       @include('home.inc.contact')
   </section><!-- End Contact Section -->
   
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