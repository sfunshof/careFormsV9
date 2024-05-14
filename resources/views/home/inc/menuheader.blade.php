<div class="container-fluid d-flex align-items-center justify-content-between">

    <a href="{{ url('/')}}" class="logo d-flex align-items-center scrollto me-auto me-lg-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="{{asset('home/assets/img/logo.png')}}" alt=""> 
        <h1>Care Trail<span>.</span></h1>
    </a>

    <nav id="navbar" class="navbar">
        <ul>
            <li><a class="nav-link scrollto" href="{{url('/#hero-animated')}}">Home</a></li>  
            <li><a class="nav-link scrollto" href="{{url('/#problem')}}">Challenges</a></li>
            <li><a class="nav-link scrollto" href="{{url('/#featured-services')}}">Benefits</a></li>
            {{--
            <li><a class="nav-link scrollto" href="{{url('/#about')}}">About</a></li>
            <li><a class="nav-link scrollto" href="{{url('/#services')}}">Services</a></li>
            --}}
            <li><a class="nav-link scrollto" href="{{url('/#contact')}}">Contact</a></li>
            <li><a class="nav-link scrollto" style="font-weight: bold;"  href="{{url('/login/#signin')}}">Sign In</a></li>
       </ul>
      
       <i class="bi bi-list mobile-nav-toggle d-none"></i>
    </nav><!-- .navbar -->
    <a class="btn-getstarted scrollto" href="{{url('/register')}}">Get Started</a>

</div>