@extends('mobilecompliance.layouts.layout')

@section('css-custom')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Simulated status bar styles */
        .navbar {
            background-color: #000; /* Set your desired background color */
            color: #fff; /* Set your desired text color */
            height:'auto';
        }
    </style>
    <link href="{{asset('custom/css/mobilespotcheck/mycss.css')}}"  rel="stylesheet">

@endsection

@section('title')
    Spot Check Home    
@endsection

@section('header-contents')
   @include('mobilespotcheck.inc.header')
@endsection

@section('contents')
    <div id="spinner" style="display: none;">
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
     
    <div id="app"  v-cloak>
        <div v-show="showSelectCarersPage">
             @include('mobilespotcheck.fakecomponents.selectcarers')
        </div>
        <div v-show="showSelectServiceUsersPage">
            @include('mobilespotcheck.fakecomponents.selectserviceusers')
            <div v-show="showDisplaySelectedInfoPage">
                @include('mobilespotcheck.fakecomponents.displayselectedinfo')
            </div>    
        </div>
        <div v-show="showSpotCheckPage">
            @include('mobilespotcheck.fakecomponents.spotcheckques')
            <div v-show="showSuccessSavedPage">
                 @include('mobilespotcheck.fakecomponents.successsaved')    
            </div>    
        </div>
        
        <div>
            @include('mobilespotcheck.fakecomponents.report')
        </div>
    </div>

@endsection
@section('footer-contents')
   @include('mobilespotcheck.inc.footer')
@endsection 
 
@push('scripts')
    <script>
        let save_mobileSpotCheckURL= "{{ url('/spotcheck/mobileSave')}}"; 
        let show_mobileSpotCheckDataURL="{{ url('/spotcheck/mobileHome') }}";
        let token = "{{ csrf_token() }}";
        let loginURL="{{ route('compliancelogin') }}";
        let menuURL="{{ route('compliancemenu') }}";
        var my2AssociativeArray = @json($records);
       // alert(JSON.stringify(my2AssociativeArray));
    </script>
    <script src="{{asset('custom/js/mobilespotcheck/app.js')}}"></script>  
    <script src="{{asset('custom/js/mobilespotcheck/common.js')}}"></script>
    <script src="{{asset('custom/js/mobilespotcheck/myjs.js')}}"></script>
@endpush   
