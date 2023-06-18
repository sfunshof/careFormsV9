@extends('backoffice.layouts.layout')
@section('title')
    Add a new employee
@endsection
@section('contents')

    <section class="section">
        <div class="row">
            <div class="col-lg-9">
                @include('backoffice.inc.employeeFields')              
            </div>
        </div>
    </section>        
    <script>
        let token = "{{ csrf_token() }}";
        let save_employeeURL= "{{ url('employee/save')}}"; 
        let companyID={{ $company_settings[0]->companyID }};
    </script>   
@endsection    

@section('jscontents')
  {{--  This js file is used to add and update so it is callled save --}}
  <script src="{{asset('custom/js/backoffice/save_employee.js')}}"></script>
@endsection 