  {{-- start page title --}}
<div class="pagetitle">
    <h1>@yield('title')</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href= "{{ url('dashboard')}}"  >   Home</a></li>
        <li class="breadcrumb-item active">@yield('title')</li>
      </ol>
    </nav>
  </div>
  {{-- end page title --}}