@extends('backoffice.layouts.layout')
@section('cssCustom')
    <style>
        #rowContainer {
            max-height: 50vh; /* Adjust the height as needed */
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 1px;
            position: relative;
        }
        .row-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .row-container .form-control {
            flex: 1;
        }
        .row-container .icon-container {
            display: flex;
            gap: 10px;
        }
        .row-container:first-child .delete-icon {
            visibility: hidden;
        }
        .sticky-button {
            position: fixed;
            bottom: 0;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        .error_class {
            color: red;
            margin-top: 5px;
        }
        .input-container {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
    </style>
@endsection
@section('title')
   Mileage Visits
@endsection
@section('contents')
    <section class="section">
        <div class="row">
            <div class="col-md-3" >
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Select Dates </h6>
                         <div id="client_mileage_date">
                            @include('backoffice.fakecomponents.mileage_date_component',['data' => $data])   
                         </div>  
                        <div class="">
                            <button type="button" class="btn btn-primary"  onClick="reload_client_mileageFunc()" >Apply</button>
                        </div>   
                    </div>
                </div>         
            </div>
            <div class="col-md-5" >
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">View Mileage Summary </h6>
                        <div id="client_mileage_sum">
                            @include('backoffice.fakecomponents.client_mileage_sum_component', ['data' => $data])   
                        </div>                        
                    </div>
                </div>         
            </div>
            <div class="col-md-4" >
                <div class="card">
                    <div class="card-body">
                        <div id="client_mileage_daily">
                            @include('backoffice.fakecomponents.client_mileage_daily_component', ['data' => $data])                     
                        </div>   
                    </div>
                </div>         
            </div>
        </div>
    </section>   
 
@endsection

<script>
    let token = "{{ csrf_token() }}";
    let sumRptURL="{{route('clientSummaryReport')}}";
    let check_postcodeValidityURL="{{route('check_postcodeValidity')}}";
    let update_dailyPostcodesURL= "{{ route('update_dailyPostcodes') }}" ;
    let set_dailyPostcodesURL= "{{ route('set_dailyPostcodes') }}" ;
    let reload_client_mileageURL= "{{ route('reload_client_mileage') }}" ;
</script>

@section('jscontents')
   {{-- @include('backoffice.fakecomponents.ques_component') --}}
   <script src="{{asset('custom/js/backoffice/mileage_client.js')}}"></script>
@endsection    
