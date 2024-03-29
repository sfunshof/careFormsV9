<div class="container custom-box mt-4">
    <h5 class="text-center">Here are the selected information</h5>
    <div class="row">
        <div class="col-6">
            <span class="label-font">Carer:</span>
        </div>
        <div class="col-6">
            <span class="value-font">@{{ selectedCarer }}</span>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <span class="label-font">Service User:</span>
        </div>
        <div class="col-6">
            <span class="value-font">@{{ selectedServiceUser }}</span>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <span class="label-font">Date and Time:</span>
        </div>
        <div class="col-6">
            <span class="value-font"> {{ now()->toDateTimeString() }}</span>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <span class="label-font">Accessed by:</span>
        </div>
        <div class="col-6">
            <span class="value-font">{{ session('userName') }}</span>
        </div>
    </div>
    
</div>