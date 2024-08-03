@extends('backoffice.layouts.layout')
@section('cssCustom')
    <style>
        .accordion {
            --bs-accordion-border-color: rgba(0,0,0,.125);
            --bs-accordion-border-width: 1px;
            --bs-accordion-border-radius: 0.5rem;
            --bs-accordion-inner-border-radius: calc(0.5rem - 1px);
            --bs-accordion-btn-padding-x: 1.25rem;
            --bs-accordion-btn-padding-y: 0.5rem; /* Reduced from 1rem */
            --bs-accordion-btn-color: #212529;
            --bs-accordion-btn-bg: #f8f9fa;
            --bs-accordion-btn-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23212529'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            --bs-accordion-btn-icon-width: 1.25rem;
            --bs-accordion-btn-icon-transform: rotate(-180deg);
            --bs-accordion-btn-icon-transition: transform 0.2s ease-in-out;
            --bs-accordion-btn-active-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230c63e4'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            --bs-accordion-btn-focus-border-color: #86b7fe;
            --bs-accordion-btn-focus-box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            --bs-accordion-body-padding-x: 1.25rem;
            --bs-accordion-body-padding-y: 1rem;
            --bs-accordion-active-color: #0c63e4;
            --bs-accordion-active-bg: #e7f1ff;
        }

        .accordion-item {
            background-color: #fff;
            border: var(--bs-accordion-border-width) solid var(--bs-accordion-border-color);
            margin-bottom: 0.5rem; /* Reduced from 1rem */
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease-in-out;
        }

        .accordion-item:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .accordion-button {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
            padding: var(--bs-accordion-btn-padding-y) var(--bs-accordion-btn-padding-x);
            font-size: 1rem; /* Reduced from 1.1rem */
            color: var(--bs-accordion-btn-color);
            text-align: left;
            background-color: var(--bs-accordion-btn-bg);
            border: 0;
            border-radius: 0;
            overflow-anchor: none;
            transition: all 0.3s ease-in-out;
        }

        .accordion-button:not(.collapsed) {
            color: var(--bs-accordion-active-color);
            background-color: var(--bs-accordion-active-bg);
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.125);
            padding-top: 1rem; /* Increased padding for selected pane */
            padding-bottom: 1rem;
            font-size: 1.1rem; /* Larger font for selected pane */
            font-weight: bold;
        }

        .accordion-button:focus {
            z-index: 3;
            border-color: var(--bs-accordion-btn-focus-border-color);
            outline: 0;
            box-shadow: var(--bs-accordion-btn-focus-box-shadow);
        }

        .accordion-button::after {
            flex-shrink: 0;
            width: var(--bs-accordion-btn-icon-width);
            height: var(--bs-accordion-btn-icon-width);
            margin-left: auto;
            content: "";
            background-image: var(--bs-accordion-btn-icon);
            background-repeat: no-repeat;
            background-size: var(--bs-accordion-btn-icon-width);
            transition: var(--bs-accordion-btn-icon-transition);
        }

        .accordion-button:not(.collapsed)::after {
            background-image: var(--bs-accordion-btn-active-icon);
            transform: var(--bs-accordion-btn-icon-transform);
        }

        .accordion-body {
            padding: var(--bs-accordion-body-padding-y) var(--bs-accordion-body-padding-x);
        }

        /* Custom colors for each pane */
        .accordion-item:nth-child(1) .accordion-button { background-color: #e9ecef; }
        .accordion-item:nth-child(2) .accordion-button { background-color: #e2e3e5; }
        .accordion-item:nth-child(3) .accordion-button { background-color: #d9d9d9; }

        .accordion-item:nth-child(1) .accordion-button:not(.collapsed) { background-color: #dde1e3; }
        .accordion-item:nth-child(2) .accordion-button:not(.collapsed) { background-color: #d6d8db; }
        .accordion-item:nth-child(3) .accordion-button:not(.collapsed) { background-color: #cccccc; }

        /* Additional styles for collapsed state */
        .accordion-button.collapsed {
            padding-top: 0.3rem; /* Further reduced padding for unselected panes */
            padding-bottom: 0.3rem;
        }

        .accordion-item .accordion-collapse {
            transition: all 0.3s ease-in-out;
        }

        .accordion-item .accordion-collapse.collapse:not(.show) {
            display: block;
            height: 0;
            overflow: hidden;
        }
    </style>
@endsection
@section('title')
   Careworkers' Mileage 
@endsection
@section('contents')
   <input type="hidden" name="userIDField" value="">
    <section class="section">
        <div class="row">
            <div class="col-md-4" >
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Select Dates </h6>
                         <div id="admin_mileage_date">
                            @include('backoffice.fakecomponents.mileage_date_component',['data' => $data])   
                         </div>  
                        <div class="">
                            <button type="button" class="btn btn-primary"  onClick="reload_admin_mileageFunc()" >Apply</button>
                        </div>   
                    </div>
                </div>         
            </div>
            <div class="col-md-8" >
                <div class="card">
                    <div class="card-body " >

                        <div class="accordion" id="reportAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Mileage Summary 
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#reportAccordion">
                                    <div class="accordion-body">
                                        <!-- Generation -->
                                        <div id="admin_mileage_sum">
                                            @include('backoffice.fakecomponents.admin_mileage_sum_component', ['data' => $data])   
                                        </div>    
                                    </div>
                                </div>
                            </div>
                                                        

                            <div class="accordion-item" id="detailsDateID" style="display:none">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <span id="level1_headID">
                                            Level 1 Report
                                        </span>   
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#reportAccordion">
                                    <div class="accordion-body">
                                        @php
                                          $data['dates'] = isset($data['dates']) && count($data['dates']) > 0 ? $data['dates'] : [];
                                        @endphp
                                       
                                        <div id="level1_componentID">
                                            @include('backoffice.fakecomponents.admin_mileage_details_date_component', ['data' => $data])   
                                        </div>    
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item" id="detailsPostcodesID" style="display:none">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <span id="level2_headID">
                                            Level 2 Report
                                        </span>   
                                    </button>
                                </h2>
                               <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#reportAccordion">
                                    <div class="accordion-body">
                                        @php
                                          $data['postcodes'] = isset($data['postcodes']) && count($data['postcodes']) > 0 ? $data['postcodes'] : [];
                                        @endphp
                                       
                                        <div id="level2_componentID">
                                            @include('backoffice.fakecomponents.admin_mileage_details_postcodes_component', ['data' => $data])   
                                        </div>    
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    
                    </div>
                </div>         
            </div>
        </div>
    </section>   
 
@endsection



@section('jscontents')
    <script>
        let token = "{{ csrf_token() }}";
        let get_detailsLevel1URL="{{ route('adminLevel1') }}";
        let get_detailsLevel2URL="{{ route('adminLevel2') }}";
        let reload_admin_mileageURL="{{ route('reload_admin_mileage')}}";
    </script>
   <script src="{{asset('custom/js/backoffice/mileage_admin.js')}}"></script>
@endsection    
