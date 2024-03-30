{{--
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid justify-content-between mx-0 px-3">
        <!-- Left section with previous button -->
        <button id="prevId" class="navbar-btn btn btn-link text-white" style="width: 120px;" onClick="prevIconFunc()">
            <i class="fas fa-arrow-circle-left fa-2x"></i> <!-- Larger icon -->
        </button>

        <!-- Centered company name -->
        <span class="navbar-brand text-center">{{$companyName}}</span>

        <!-- Right section with next button -->
        <button id="nextId" class="navbar-btn btn btn-link text-white" style="width: 120px;" onClick="nextIconFunc()">
            <i class="fas fa-arrow-circle-right fa-2x"></i> <!-- Larger icon -->
        </button>
    </div>
</nav>   
--}}
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid mx-0 px-3">
        <div class="row justify-content-between align-items-center w-100"> <!-- Use a row to contain the buttons -->
            <!-- Left section with previous button -->
            <div class="col-auto"> <!-- Use col-auto to allow the column to size based on content -->
                <button id="prevId" class="navbar-btn btn btn-link text-white" style="width: 120px;" onClick="prevIconFunc()">
                    <i class="fas fa-arrow-circle-left fa-2x"></i> <!-- Larger icon -->
                </button>
            </div>

            <!-- Centered company name -->
            <div class="col-auto"> <!-- Use col-auto to allow the column to size based on content -->
                <span class="navbar-brand text-center">{{$companyName}}</span>
            </div>

            <!-- Right section with next button -->
            <div class="col-auto"> <!-- Use col-auto to allow the column to size based on content -->
                <button id="nextId" class="navbar-btn btn btn-link text-white" style="width: 120px;" onClick="nextIconFunc()">
                    <i class="fas fa-arrow-circle-right fa-2x"></i> <!-- Larger icon -->
                </button>
            </div>
        </div>
    </div>
</nav>