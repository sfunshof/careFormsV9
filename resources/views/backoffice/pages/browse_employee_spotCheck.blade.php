@extends('backoffice.layouts.layout')
@section('title')
    Carers Spot Checks
@endsection
    @section('contents')
    <div id="browse_employeeContentID">
        @include('backoffice.pages.browse_employee_spotCheck_component') 
    </div>    
  
@endsection    
@section('jscontents')
    <script>
        let token = "{{ csrf_token() }}"; 
        let browse_employee_spotcheckURL="{{url('employee/browse_spotcheck')}}"; 
        let view_employee_spotcheckURL="{{url('employee/view_spotcheck')}}";
        let edit_employee_spotcheckURL="{{url('employee/edit_spotcheck')}}";
        let save_employee_spotcheckURL="{{url('employee/save_spotcheck')}}";
        let pdf_employee_spotcheckURL="{{url('employee/pdf_spotcheck')}}";
        let email_employee_spotcheckURL="{{url('employee/email_spotcheck')}}"; 
    </script>
    <script src="{{asset('custom/js/backoffice/browse_employee_spotCheck.js')}}"></script>
@endsection