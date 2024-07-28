<div class="container-fluid bg-success text-light d-flex align-items-center 
        justify-content-center py-2 fixed-top">
    <span>Main Menu</span>
</div>
{{--
{{ route('route1') }}"
--}}


<div class="main-container">
    <div class="boxes-container">
        <a href="#" class="box  {{ session('careWorkerLoginID') == -1 ? '' : 'd-none' }} " onClick="showSpinner();pageLoader(spotCheckURL,[])">
           <i class="fas  fa-2x fa-user-check"></i>
            <span class= "text-success">Spot Checks</span>
        </a>
        <a href="#" class="box  {{ session('careWorkerLoginID') == -1 ? '' : 'd-none' }} " onClick="showSpinner();pageLoader(prospectURL,[])">
            <i class="fas fa-2x fa-address-card"></i>
            <span class= "text-success"> Assessment</span>
        </a>
        <a href="#" class="box  {{ session('careWorkerLoginID') > 0 ? '' : 'd-none' }}" onClick="showSpinner();pageLoader(mileageURL,[])">
            <i class="fas  fa-2x fa-car-side"></i>
             <span class= "text-success">Mileage Capture</span>
         </a>
         <a href="#" class="box  {{ session('careWorkerLoginID') > 0 ? '' : 'd-none' }}" onClick="showSpinner();pageLoader(nightURL,[])">
             <i class="fas fa-2x fa-procedures"></i>
             <span class= "text-success"> Night Awarness</span>
         </a>
    </div>   
</div>


<button class="btn btn-danger w-100 fixed-bottom btn-lg" onClick="logoutFunc()">
   <i class="fas fa-sign-out-alt"></i> Logout
</button>

