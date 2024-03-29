@extends('backoffice.layouts.layout')
@section('title')
    Spot Check Dashboard
@endsection
@section('contents')
    <div id="dashboardContentID">
        @include('backoffice.pages.spotcheck_component_dashboard') 
    </div>    
     
@endsection

@section('jscontents')
     <script>
        let token = "{{ csrf_token() }}";
        let update_spotcheckDashboardDataURL= "{{ url('backoffice/spotcheck_dashboard')}}"; 
     </script>    
     <script src="{{asset('custom/js/backoffice/spotcheck_dashboard.js')}}"></script>
@endsection 

