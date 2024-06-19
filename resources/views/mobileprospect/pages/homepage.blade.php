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
    </style>
    <link href="{{asset('custom/css/mobilespotcheck/mycss.css')}}"  rel="stylesheet">

@endsection

@section('title')
    Assessment   
@endsection

@section('header-contents')
   @include('mobileprospect.inc.header') 
@endsection

@section('contents') 
 
    <div id="app"  v-cloak>
        <div v-show="showProspectEntryPage">
            @include('mobileprospect.fakecomponents.prospectentry')
        </div>
        
        <div v-show="showProspectQuesPage">
            @include('mobileprospect.fakecomponents.prospectques')
            <div v-show="showSuccessSavedPage">
                 @include('mobilespotcheck.fakecomponents.successsaved')    
            </div>    
        </div>
        

        <div id="spinner" style="display: none;">
            <div class="text-center mt-5">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footer-contents')
   @include('mobileprospect.inc.footer')
@endsection 
 
@push('scripts')
    <script>
        let token = "{{ csrf_token() }}";
        let saveURL="{{ route('prospectsave') }}";
        let submitURL="{{ route('prospectsubmit') }}";
        let loginURL="{{ route('compliancelogin') }}";
        let menuURL="{{ route('compliancemenu') }}";
    </script>
    <script src="{{asset('custom/js/mobileprospect/app.js')}}"></script>
    <script src="{{asset('custom/js/mobilespotcheck/common.js')}}"></script>
    <script src="{{ asset('custom/js/mobileprospect/myjs.js') }}"></script>
@endpush   
