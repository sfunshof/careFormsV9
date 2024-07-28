@extends('mobilecompliance.layouts.layout')

@section('css-custom')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{asset('custom/css/mobilespotcheck/mycss.css')}}"  rel="stylesheet"> 
    <style> 
        #spinner {
            display: none;
            position: fixed; /* or 'absolute' if you want it to be relative to a parent */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999; /* Ensures it's on top of other elements */
        }
    </style>
@endsection

@section('title')
    Midnight   
@endsection

@section('header-contents')
   @include('mobilenight.inc.header') 
@endsection

@section('contents') 
    <h1> No night calls </h1>    
    <div id="spinner">
        <div class="text-center mt-5">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
@endsection
@section('footer-contents')
   @include('mobilenight.inc.footer')
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
<script src="{{asset('custom/js/mobilespotcheck/common.js')}}"></script>
<script src="{{ asset('custom/js/mobilenight/myjs.js') }}"></script>
@endpush   