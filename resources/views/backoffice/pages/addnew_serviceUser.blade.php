@extends('backoffice.layouts.layout')
@section('title')
    Add a new service user
@endsection
@section('contents')

    <section class="section">
        <div class="row">
            <div class="col-lg-9">
                @include('backoffice.inc.serviceUserFields')              
            </div>
        </div>
    </section>        
    <script>
        let token = "{{ csrf_token() }}";
        let save_serviceUserURL= "{{ url('serviceUser/save')}}"; 
        let companyID={{ $company_settings[0]->companyID }};
    </script>   
@endsection    

@section('jscontents')
  {{--  This js file is used to add and update so it is callled save --}}
  <script src="{{asset('custom/js/backoffice/save_serviceUser.js')}}"></script>
@endsection   
