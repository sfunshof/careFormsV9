<div class="container-fluid bg-success text-light d-flex align-items-center 
        justify-content-center py-2 fixed-top">
    <span>Main Menu</span>
</div>
{{--
{{ route('route1') }}"
--}}

<div class="main-container">
    <div class="boxes-container">
        <a href="{{route('spotcheckhome')}}" class="box" onClick="showSpinner()">
            <i class="fas fa-2x fa-check"></i>
            <span class= "text-success">Spot Checks</span>
        </a>
        <a href="{{route('prospecthome')}}" class="box" onClick="showSpinner()">
            <i class="fas fa-2x fa-star"></i>
            <span class= "text-success"> Assessment</span>
        </a>
    </div>   
</div>

<div class="footerx">
    <button class="btn btn-danger w-100 fixed-bottom" onClick="logoutFunc()">
        <i class="fas fa-sign-out-alt"></i> Logout
    </button>
</div>
