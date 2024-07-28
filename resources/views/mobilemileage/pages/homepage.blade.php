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
        .scrollable-form-container {
            position: fixed;
            top: 70px; /* Adjust based on your header height */
            bottom: 70px; /* Adjust based on your footer height */
            left: 0;
            right: 0;
            overflow-y: auto;
            padding: 20px;
        }
        .form-floating > label {
            z-index: 2;
        }
        .form-floating {
            box-shadow: none !important; /* Remove Bootstrap shadow */
        }
        [v-cloak] {
            display: none;
        }
        .content {
            min-height: 50vh; /* Adjust height as needed */
        }
        .modal-full {
           min-width: 100%;
           margin: 0;
        }

        .modal-full .modal-content {
            min-height: 100vh;
        }

        .scrollable-table-container {
            max-height: 60vh; /* Set the height of the container */
            overflow-y: auto; /* Enable vertical scrolling */
            /* border: 1px solid #ccc;  Optional: Add border */
            position: relative;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            position: sticky;
            top: 0;
            background: #f2f2f2;
            z-index: 1;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #spinner {
            display: none;
            position: fixed; /* or 'absolute' if you want it to be relative to a parent */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999; /* Ensures it's on top of other elements */
        }
     
    </style>
    <link href="{{asset('custom/css/mobilespotcheck/mycss.css')}}"  rel="stylesheet">
    
@endsection

@section('title')
    Mileage   
@endsection

@section('header-contents')
   @include('mobilemileage.inc.header') 
@endsection

@section('contents') 
 
    <div id="app"  v-cloak>
        
        <div id="spinner">
            <div class="text-center mt-5">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
         
        
         
        <p class="fs-5 mb-0">No of visits made today :  <span class="fw-bold"   id="countVisitID">  {{ $countVisit }}  </span>   </p>
                 

        <div v-show="showLocationButtonsPage">
            @include('mobilemileage.fakecomponents.locationButtons')
            @include('mobilemileage.fakecomponents.modaltemplate')
        </div>
                       
        <div v-show="showMileageReportPage">
            @include('mobilemileage.fakecomponents.report')
        </div>
     </div>

@endsection
@section('footer-contents')
   @include('mobilemileage.inc.footer')
@endsection 
 
@push('scripts')
    <script>
        let token = "{{ csrf_token() }}";
        let saveURL="{{ route('mileagesave') }}";
        let getPostcodeURL="{{ route('getPostcodeFromDatabase')}}";
        let getDistanceURL="{{ route('postCodeDistance')}}";
        /* let loginURL="{{ route('compliancelogin') }}"; */
        let menuURL="{{ route('compliancemenu') }}";
        let hereApiKey = "{{ config('care.here_api_key') }}";
    </script>
    <script src="{{asset('custom/js/mobilemileage/app.js')}}"></script>
    <script src="{{asset('custom/js/mobilespotcheck/common.js')}}"></script>
    <script src="{{ asset('custom/js/mobilemileage/myjs.js') }}"></script>
@endpush   