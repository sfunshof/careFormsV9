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
      
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#dashboard-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Dashboards</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="dashboard-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ url('/backoffice/feedback_dashboard')}}">
              <i class="bi bi-circle"></i><span>Feedback Dashboard</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/backoffice/spotcheck_dashboard')}}">
               <i class="bi bi-circle"></i><span>SpotCheck Dashboard</span>
            </a>
          </li>
        </ul>
      </li>   
      {{-- End Dashboard Nav --}}

      
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#serviceuser-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Service Users</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="serviceuser-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ url('/serviceUser/addnew')}}">
              <i class="bi bi-circle"></i><span>Add New Service User</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/serviceUser/browse')}}">
              <i class="bi bi-circle"></i><span>Browse Service Users</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/serviceUser/browse_surveyfeedback')}}">
              <i class="bi bi-circle"></i><span>Service User Survey</span>
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

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#employee-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Employees</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="employee-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ url('/employee/addnew')}}">
              <i class="bi bi-circle"></i><span>Add New Employee</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/employee/browse')}}">
              <i class="bi bi-circle"></i><span>Browse Employees</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/employee/browse_surveyfeedback')}}">
              <i class="bi bi-circle"></i><span>Employee Survey</span>
            </a>
          </li>
          <li>
            <a href="{{ url('/employee/browse_spotcheck')}}">
              <i class="bi bi-circle"></i><span>Employee Spot Check</span>
            </a>
          </li>
        </ul>
      </li>
      {{-- End employee Nav --}}

      {{-- BuildForms --}}
      <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-journal-text"></i><span>Build Forms</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                  <a href="{{ url('/buildforms/serviceUserFeedback')}}">
                    <i class="bi bi-circle"></i><span>Service user feedback</span>
                  </a>
              </li>
              <li>
                  <a href="{{ url('/buildforms/employeeFeedback')}}">
                    <i class="bi bi-circle"></i><span>Employee Feedback</span>
                  </a>
              </li>
              <li>
                <a href="{{ url('/buildforms/spotCheck')}}">
                  <i class="bi bi-circle"></i><span>Spot Checks</span>
                </a>
            </li>
          </ul>
      </li>
      {{-- End Forms Nav --}}

      {{--  Company Profile --}}
      <li class="nav-item">
        <a class="nav-link " href="{{ url('/backoffice/companyprofile')}}">
          <i class="bi bi-person"></i>
          <span>Company Profile</span>
        </a>
      {{--  End company Profile --}}

    </ul>

  </aside>