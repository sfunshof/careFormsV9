@extends('mobile.layouts.layout_plain')
@section('title')
    Home
@endsection
@section('contents')
    <div class="container pb-3 border-top">
        <div class="alert alert-danger mt-2" role="alert" id="errMsgID">
            Error: {{ $userType}} not found
        </div>
    </div> 
     
@endsection