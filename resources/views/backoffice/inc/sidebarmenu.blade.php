<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      {{--
      <li class="nav-item">
        <a class="nav-link " href="{{ url('backoffice/dashboard')}}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
      --}}
      
      <li class="nav-item  {{ session('is_admin') == 1 ? '' : 'd-none' }}  " >
        <a class="nav-link collapsed" data-bs-target="#dashboard-nav" data-bs-toggle="collapse" href="#">
          <i class="fab fa-connectdevelop fs-6"></i><span>Dashboards</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="dashboard-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ url('/backoffice/feedback_dashboard')}}"  onclick="show_spinner()"> 
              <i class="bi bi-clipboard2-data-fill fs-6"></i><span>Feedback Dashboard</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/backoffice/spotcheck_dashboard')}}" onclick="show_spinner()">
              <i class="bi bi-clipboard2-check-fill fs-6"></i><span>SpotCheck Dashboard</span>
            </a>
          </li>
        </ul>
      </li>   
      {{-- End Dashboard Nav --}}

      
      <li class="nav-item  {{ session('is_admin') == 1 ? '' : 'd-none' }}  ">
        <a class="nav-link collapsed" data-bs-target="#serviceuser-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-ui-radios fs-6"></i><span>Service Users</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="serviceuser-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ url('/serviceUser/addnew')}}" onclick="show_spinner()">
              <i class="bi bi-person-plus-fill fs-6"></i></i><span>Add New Service User</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/serviceUser/browse')}}" onclick="show_spinner()">
              <i class="bi bi-person-vcard fs-6"></i><span>Browse Service Users</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/serviceUser/browse_surveyfeedback')}}" onclick="show_spinner()">
              <i class="bi bi-person-bounding-box fs-6"></i><span>Service User Survey</span>
            </a>
          </li>
          
          {{--
          <li>
            <a href="{{ url('/serviceUser/show_complaints')}}">
              <i class="bi bi-circle"></i><span> Complaints feedback</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/serviceUser/show_compliments')}}">
              <i class="bi bi-circle"></i><span> Compliments  Slip </span>
            </a>
          </li>
          --}}   
        </ul>
      </li> 
      {{-- End Service user Nav --}}

      <li class="nav-item  {{ session('is_admin') == 1 ? '' : 'd-none' }} ">   
        <a class="nav-link collapsed" data-bs-target="#employee-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-ui-checks fs-6"></i><span>Employees</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="employee-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ url('/employee/addnew')}}" onclick="show_spinner()">
              <i class="bi bi-person-fill-add fs-6"></i><span>Add New Employee</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/employee/browse')}}" onclick="show_spinner()">
               <i class="bi bi-person-lines-fill fs-6"></i><span>Browse Employees</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/employee/browse_surveyfeedback')}}" onclick="show_spinner()">
              <i class="bi bi-person-workspace fs-6"></i><span>Employee Survey</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/employee/browse_spotcheck')}}" onclick="show_spinner()">
              <i class="bi bi-person-fill-check fs-6"></i><span>Employee Spot Check</span>
            </a>
          </li>
        </ul>
      </li>
      {{-- End employee Nav --}}
     
      {{--  Start of Assessment --}}
      <li class="nav-item  {{ session('is_admin') == 1 ? '' : 'd-none' }}   ">
        <a class="nav-link collapsed" data-bs-target="#prospect-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Assessment</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="prospect-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ url('/prospect/addnew')}}" onclick="show_spinner()">
              <i class="bi bi-file-earmark-person-fill fs-6"></i><span>Add New Assessment</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/prospect/browse')}}" onclick="show_spinner()">
              <i class="bi bi-list-columns-reverse fs-6"></i></i><span>Browse Assessment</span>
            </a>
          </li>
        </ul>
      </li>
      {{-- End Assesment Nav --}}
     


      {{-- BuildForms --}} 
      <li class="nav-item {{ session('is_admin') == 1 ? '' : 'd-none' }}  ">
          <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="fab fa-wpforms fs-6"></i>Build Forms</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                  <a href="{{ url('/buildforms/serviceUserFeedback')}}" onclick="show_spinner()">
                    <i class="bi bi-person-vcard fs-6"></i>Service user feedback</span>
                  </a>
              </li>
              <li>
                  <a href="{{ url('/buildforms/employeeFeedback')}}" onclick="show_spinner()">
                    <i class="bi bi-person-video2 fs-6"></i><span>Employee Feedback</span>
                  </a>
              </li>
              <li>
                <a href="{{ url('/buildforms/spotCheck')}}" onclick="show_spinner()">
                  <i class="bi bi-person-check-fill fs-6"></i><span>Spot Checks</span>
                </a>
            </li>
              <a href="{{ url('/buildforms/prospect')}}" onclick="show_spinner()">
                <i class="far fa-address-book fs-6 "></i><span>Assessment</span>
              </a>
          </ul>
      </li>
      {{-- End Forms Nav --}}

      {{--  Company Profile --}}
      <li class="nav-item  {{ session('is_admin') == 1 ? '' : 'd-none' }}">
        <a class="nav-link " href="{{ url('/backoffice/companyprofile')}}" onclick="show_spinner()">
          <i class="bi bi-building fs-6"></i>
          <span>Company Profile</span>
        </a>
      </li> 
      {{--  End company Profile --}}

      {{--  Mikeage Admin--}}
      <li class="nav-item  {{ session('is_admin') == 1 ? '' : 'd-none' }}">
        <a class="nav-link " href="{{ route('adminMileage')}}" onclick="show_spinner()">
          <i class="bi bi-building fs-6"></i>
          <span>Mileage</span>
        </a>
      </li> 
      {{--  End Mikeage Admin --}}
      

      {{--  Mileage Client--}}
      <li class="nav-item  {{ session('is_admin') == 0 ? '' : 'd-none' }}">
        <a class="nav-link " href="{{ route('clientMileage')}}" onclick="show_spinner()">
          <i class="bi bi-building fs-6"></i>
          <span>Mileage</span>
        </a>
      </li> 
      {{--  End Mileage Client --}}

    </ul>

  </aside>