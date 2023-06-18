@extends('mobile.layouts.layout_plain')
@section('title')
    Home
@endsection
@section('contents')
   <div class="container pb-3 border-top">
        <div class="alert alert-danger mt-3" role="alert" id="errMsgID">
            Error: You cannot proceed because the {{ $userType }} information needed: <br>
            Has not yet been prepared <br>
            Has already been submitted <br>
         </div>
    </div> 
     
@endsection