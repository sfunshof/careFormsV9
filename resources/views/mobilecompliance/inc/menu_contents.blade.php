<div class="container-fluid bg-success text-light d-flex align-items-center 
        justify-content-center py-2 fixed-top">
    <span>Main Menu</span>
</div>
{{--
{{ route('route1') }}"
--}}

<div class="main-container">
    <div class="boxes-container">
        <a href="#" class="box" onClick="showSpinner();pageLoader(spotCheckURL,[])">
           <i class="fas  fa-2x fa-user-check"></i>
            <span class= "text-success">Spot Checks</span>
        </a>
        <a href="#" class="box" onClick="showSpinner();pageLoader(prospectURL,[])">
            <i class="fas fa-2x fa-address-card"></i>
            <span class= "text-success"> Assessment</span>
        </a>
    </div>   
</div>


<button class="btn btn-danger w-100 fixed-bottom" onClick="logoutFunc()">
   <i class="fas fa-sign-out-alt"></i> Logout
</button>

