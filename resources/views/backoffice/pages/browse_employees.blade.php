@extends('backoffice.layouts.layout')
@section('title')
    Browse Employees
@endsection
    @section('contents')
    <section class="section">
        <div class="row">
            @if (count($employees) ==0)
                <h4 class="text-danger">
                    There are no employee records registered on the system
                </h4>
                @if ($exist==1)
                    @include('backoffice.pages.checkbox_employee_component')
                @endif
            @else
                <div class="col-lg-12">   
                    @include('backoffice.pages.checkbox_employee_component')                    
                    <hr class="bg-primary border-2 border-top border-primary">
                    
                    <table class="table table-striped"   id="employeeBrowseTableID" >
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Mobile No</th>
                                <th>Action </th>   
                            </tr>
                        </thead>
                        <tbody>  
                            @foreach($employees as $employee)
                                <?php
                                    $textColor="";
                                    $disableFont="";
                                    if ($employee->isDisable==1){
                                        $disableFont="text-decoration-line-through";
                                    }
                                ?> 
                                <tr>
                                    <td> <span class="{{$disableFont}}">{{ $employee->firstName }} </span> </td>
                                    <td><span class="{{$disableFont}}"> {{ $employee->lastName }} </span>  </td>
                                    <td><span class="{{$disableFont}}"> {{ $employee->email }} </span>  </td>
                                    <td><span class="{{$disableFont}}"> {{ $employee->tel }} </span>  </td>
                                    <td> 
                                        <div class="row">
                                            <div class="col"> <i  role="button"    data-bs-toggle="tooltip" data-bs-placement="top"   title="Update"   class =" ri-edit-2-fill  text-success"  onClick="employeedetailsUpdateFunc({{$employee->userID}})" ></i>   </div>
                                            @if ($disableFont)
                                                <div class="col"> <i  role="button"  data-bs-toggle="tooltip"  data-bs-placement="top" title="Restore"    class = "bx bxs-analyse text-primary "  onClick="employeeEnableFunc({{$employee->userID}})"></i> </div>
                                            @else
                                                <div class="col"> <i  role="button"  data-bs-toggle="tooltip"  data-bs-placement="top" title="Disable"     class = "ri-forbid-line text-danger "  onClick="employeeDisableFunc({{$employee->userID}}, '{{ $employee->fullName }}')"></i> </div>
                                            @endif
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
        let get_employeeDetailsURL= "{{ url('employee/get_details')}}"; 
        let save_employeeURL= "{{ url('employee/save')}}"; 
        let disable_employeeURL= "{{ url('employee/disable') }}";
        let enable_employeeURL= "{{ url('employee/enable') }}";
        let browse_employeesURL="{{ url('employee/browse') }}";
        let browse_all_employeesURL="{{ url('employee/browse_all') }}";

         //adjust the datatable 's page
        let pageNo="{{ $pageNo }}";
        if (pageNo> 0){
            dataTable.page(pageNo);
        }
        //!! there is no need for this b/c the controller can call it
        let companyID={{$company_settings[0]->companyID}};//to be used by save_serviceUser.js
    </script> 
       
@endsection    
@section('jscontents')
    <script src="{{asset('custom/js/backoffice/browse_employees.js')}}"></script>
    {{--  Put in here because of modal's update. Addnew also uses it --}}
    <script src="{{asset('custom/js/backoffice/save_employee.js')}}"></script>
@endsection


