@extends('mobilecompliance.layouts.layout')

@section('css-custom')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{asset('custom/css/mobilecompliance/mycss.css')}}"  rel="stylesheet">
@endsection

@section('title')
    Compliance Menu  
@endsection

@section('contents')
    <div id="spinner" style="display: none;">
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    @include('mobilecompliance.inc.menu_contents')
@endsection

@push('scripts')
    <script src="{{asset('custom/js/mobilecompliance/myjs.js')}}"></script>
    <script>
        let loginURL="{{ route('compliancelogin') }}";
     </script>
@endpush   