<nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
    <div class="container-fluid justify-content-between mx-0 px-3">
        <!-- Left section with previous button -->
        <div class="d-flex align-items-center">
            <button id="prevId" class="navbar-btn btn btn-link text-white d-none"   onClick="prevIconFunc()" >
                <i class="fas fa-arrow-circle-left fa-2x"></i>
            </button>
        </div>

        <!-- Centered company name -->
        <span class="navbar-brand text-center mx-auto">{{$companyName}}</span>

        <!-- Right section with next button -->
        <div class="d-flex align-items-center">
            <button id="nextId" class="navbar-btn btn btn-link text-white d-none" onClick="nextIconFunc()" >
                <i class="fas fa-arrow-circle-right fa-2x"></i>
            </button>
        </div>
    </div>
</nav>