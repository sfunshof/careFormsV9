<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
   
      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="{{asset('assets/img/profile-img.jpg')}}" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2">{{auth()->user()->email}}</span>
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{auth()->user()->email}}</h6>
            <span>Company Admin</span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
             <a class="dropdown-item d-flex align-items-center"   href="{{ url('/backoffice/companyprofile')}}" onclick="show_spinner()">>
              <i class="bi bi-person"></i>
              <span>Company Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          {{--
          <li>
            <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
              <i class="bi bi-gear"></i>
              <span>Account Settings</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
              <i class="bi bi-question-circle"></i>
              <span>Need Help?</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          --}}

          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{route('logout')}}"  
              onclick="event.preventDefault();
              document.getElementById('logout-form').submit();"  >
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
            <form id="logout-form" action="{{route('logout')}}" method="Post" style="display:none;">
               @csrf
            </form> 
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->