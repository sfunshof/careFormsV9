@extends('backoffice.layouts.layout')
@section('title')
    Browse Assessments
@endsection
@section('contents')
    <section class="section">
        <div class="row">
            @if (count($serviceUsers)==0)
                <h4 class="text-danger">
                    There are no assessments registered on the system
                </h4>
                @if ($exist==1)
                     @include('backoffice.pages.checkbox_serviceUser_component')
                 @endif
            @else    
                <div class="col-lg-12">   
                    @include('backoffice.pages.checkbox_serviceUser_component')
                    
                    <hr class="bg-primary border-2 border-top border-primary">
                    
                    <table class="table table-striped"   id="serviceUserBrowseTableID" >
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Mobile No</th>
                                <th>Post Code</th>
                                <th>Assess. Date </th>
                                <th> Date of birth </th>
                                <th>Action </th>   
                            </tr>
                        </thead>
                        <tbody>  
                            @foreach($serviceUsers as $serviceUser)
                                <?php
                                    $textColor="";
                                    $proxyTel="";
                                    $convertColor="text-warning";
                                    $convertStatus="";
                                    //Already converted
                                    if ($serviceUser->isProspect==0){
                                        $convertColor="text-secondary";
                                        $convertStatus="disabled";
                                    }
                                    if ($serviceUser->proxy==1){
                                        $textColor="text-warning";
                                        $proxyTel="3rd Party Mobile Number";
                                    } 
                                    $disableFont="";
                                    if ($serviceUser->isDisable==1){
                                        $disableFont="text-decoration-line-through";
                                    }
                                    $jsonData=$serviceUser->prospectJSON;
                                    // Decode the JSON field to an associative array
                                    $details = json_decode($jsonData, true);
                                    // Extract the DOB key
                                    $dob = $details['DOB'];
                                ?> 
                                <tr>
                                    <td><span class="{{$disableFont}}"> {{ $serviceUser->fullName }} </span>  </td>
                                    <td><span data-bs-toggle="tooltip" data-bs-placement="top"   title="{{$proxyTel}}"  class="{{$textColor}} {{$disableFont}}    "> {{ $serviceUser->tel }} </span></td>
                                    <td><span class="{{$disableFont}}"> {{ $serviceUser->address }} </span>  </td>
                                    <td><span class="{{$disableFont}}"> {{ $serviceUser->createdDate}} </span>  </td>
                                    <td><span class="{{$disableFont}}"> {{ $dob }} </span>  </td>
                                   
                                    <td> 
                                        <div class="row">
                                            <div class="col"> <i  role="button"    data-bs-toggle="tooltip" data-bs-placement="top"   title="Update"   class =" ri-edit-2-fill  text-success"  onClick="serviceUserdetailsUpdateFunc({{$serviceUser->userID}},1,0)" ></i>   </div>
                                            @if ($disableFont)
                                                <div class="col"> <i  role="button"  data-bs-toggle="tooltip"  data-bs-placement="top" title="Restore"    class = "bx bxs-analyse text-primary "  onClick="serviceUserEnableFunc({{$serviceUser->userID}})"></i> </div>
                                            @else
                                                <div class="col"> <i  role="button"  data-bs-toggle="tooltip"  data-bs-placement="top" title="Disable"     class = "ri-forbid-line text-danger "  onClick="serviceUserDisableFunc({{$serviceUser->userID}}, '{{ $serviceUser->fullName }}')"></i> </div>
                                            @endif
                                            <div class="col"> <i  role="button"    data-bs-toggle="tooltip" data-bs-placement="top"   title="Print"   class =" fas fa-print "  onClick="serviceUserdetailsUpdateFunc({{$serviceUser->userID}},1,1)" ></i>   </div>
                                            <div class="col"> <i  role="button"    data-bs-toggle="tooltip" data-bs-placement="top"   title="Convert to Client"   class =" fas fa-angle-double-up {{$convertColor}} {{$convertStatus}} "  onClick="prospectConvertFunc({{$serviceUser->userID}}, '{{ $serviceUser->fullName }}' )" ></i>   </div>
                                        </div>
                                    </td>
                                </tr>   
                            @endforeach
                        </tbody>
                    </table>          
                
                </div>
             @endif   
        </div>
    </section>     
    <script>
        let token = "{{ csrf_token() }}";
        let get_serviceUserDetailsURL= "{{ url('prospect/get_details')}}"; 
        let save_serviceUserURL= "{{ url('serviceUser/save')}}"; 
        let disable_serviceUserURL= "{{ url('serviceUser/disable') }}";
        let enable_serviceUserURL= "{{ url('serviceUser/enable') }}";
        let browse_serviceUsersURL="{{ url('prospect/browse') }}";
        let browse_all_serviceUsersURL="{{ url('prospect/browse_all') }}";
        let convert_prospectURL="{{ url('prospect/convert') }}";
        let submit_prospectQuesURL="{{ route('submitprospect') }}";
        let pdf_prospectURL="{{ route('printPdfProspect') }}";
        //adjust the datatable 's page
        let pageNo="{{ $pageNo }}";
        if (pageNo> 0){
            dataTable.page(pageNo);
        }
        let companyID={{$company_settings[0]->companyID}};//to be used by save_serviceUser.js
    </script> 
       
@endsection    
@section('jscontents')
    <script src="{{asset('custom/js/backoffice/browse_serviceUsers.js')}}"></script>
    {{--  Put in here because of modal's update. Addnew also uses it --}}
    <script src="{{asset('custom/js/backoffice/save_serviceUser.js')}}"></script>
     <script src="{{asset('custom/js/mobilespotcheck/common.js')}}"></script>

@endsection


