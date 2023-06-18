@extends('backoffice.layouts.layout')
@section('title')
    Browse Service Users
@endsection
    @section('contents')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">   
                    <?php 
                        $isChecked="";
                        if ($isDisabledFlag==0) $isChecked="checked='checked'";
                    ?>
                
                    <div class="form-check form-switch form-switch-md">
                        <input class="form-check-input" type="checkbox" id="showDisabledUsersID"   onClick="browse_all_serviceUsersFunc()"  {{ $isChecked }}>
                        <label class="form-check-label" style="padding-left:.5rem;padding-top:.2rem;" for="showDisabledUsersID">Show Disengaged Users</label>
                    </div>
                
                <hr class="bg-primary border-2 border-top border-primary">
                
                <table class="table table-striped"   id="serviceUserBrowseTableID" >
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Post Code</th>
                            <th>Mobile No</th>
                            <th>Action </th>   
                        </tr>
                    </thead>
                    <tbody>  
                        @foreach($serviceUsers as $serviceUser)
                            <?php
                                $textColor="";
                                $proxyTel="";
                                if ($serviceUser->proxy==1){
                                    $textColor="text-warning";
                                    $proxyTel="3rd Party Mobile Number";
                                } 
                                $disableFont="";
                                if ($serviceUser->isDisable==1){
                                    $disableFont="text-decoration-line-through";
                                }
                            ?> 
                            <tr>
                                <td><span class="{{$disableFont}}"> {{ $serviceUser->title }} </span>   </td>
                                <td> <span class="{{$disableFont}}">{{ $serviceUser->firstName }} </span> </td>
                                <td><span class="{{$disableFont}}"> {{ $serviceUser->lastName }} </span>  </td>
                                <td><span class="{{$disableFont}}"> {{ $serviceUser->address }} </span>  </td>
                                <td><span data-bs-toggle="tooltip" data-bs-placement="top"   title="{{$proxyTel}}"  class="{{$textColor}} {{$disableFont}}    "> {{ $serviceUser->tel }} </span></td>
                                <td> 
                                    <div class="row">
                                        <div class="col"> <i  role="button"    data-bs-toggle="tooltip" data-bs-placement="top"   title="Update"   class =" ri-edit-2-fill  text-success"  onClick="serviceUserdetailsUpdateFunc({{$serviceUser->userID}})" ></i>   </div>
                                        @if ($disableFont)
                                            <div class="col"> <i  role="button"  data-bs-toggle="tooltip"  data-bs-placement="top" title="Restore"    class = "bx bxs-analyse text-primary "  onClick="serviceUserEnableFunc({{$serviceUser->userID}})"></i> </div>
                                        @else
                                            <div class="col"> <i  role="button"  data-bs-toggle="tooltip"  data-bs-placement="top" title="Disable"     class = "ri-forbid-line text-danger "  onClick="serviceUserDisableFunc({{$serviceUser->userID}}, '{{ $serviceUser->fullName }}')"></i> </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>   
                        @endforeach
                    </tbody>
                </table>          
             
            </div>
        </div>
    </section>     
    <script>
        let token = "{{ csrf_token() }}";
        let get_serviceUserDetailsURL= "{{ url('serviceUser/get_details')}}"; 
        let save_serviceUserURL= "{{ url('serviceUser/save')}}"; 
        let disable_serviceUserURL= "{{ url('serviceUser/disable') }}";
        let enable_serviceUserURL= "{{ url('serviceUser/enable') }}";
        let browse_serviceUsersURL="{{ url('serviceUser/browse') }}";
        let browse_all_serviceUsersURL="{{ url('serviceUser/browse_all') }}";

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
@endsection


