@extends('backoffice.layouts.layout')
@section('title')
    <?php 
        $title="Browse Employees Survey Feedback";
        $address_or_emailLabel="Email";
        if ($isServiceUser==1){
            $title="Browse Service Users Survey Feedback";
            $address_or_emailLabel="Email" ;  //"Post Code";
        }
   
    ?>
    {{ $title }}

@endsection
@section('contents')
<section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Select the criteria</h5> 

                <!-- Floating Labels Form -->
                <form class="row g-3">
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="selectMonthID" aria-label="Month"  onchange="selectMonthFunc()">
                                <?php
                                    if ($month){
                                        $selected_month=$month;
                                    }else{    
                                        $selected_month = date('m'); //current month
                                    }
                                    for ($i_month = 1; $i_month <= 12; $i_month++) { 
                                        $selected = $selected_month == $i_month ? ' selected' : '';
                                        echo '<option value="'.$i_month.'"'. $selected. '>'. date('F', mktime(0,0,0,$i_month)).'</option>'."\n";
                                    }
                                ?>
                            </select>
                            <label for="selectMonthID">Month</label>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        &nbsp;
                    </div>   

                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="selectYearID" aria-label="Year"   onchange="selectYearFunc()">
                               <?php 
                                    $year_start  = 2020;
                                    $year_end =  date('Y'); 
                                    if ($year){
                                        $selected_year=$year;
                                    }else {
                                        $selected_year = $year_end; // current Year
                                    }
                                   
                                    for ($i_year = $year_start; $i_year <= $year_end; $i_year++) {
                                        $selected = $selected_year == $i_year ? ' selected' : '';
                                        echo '<option value="'. $i_year  .'"'.$selected.'>'.$i_year.'</option>'."\n";
                                    }
                               ?>

                            </select>
                            <label for="selectYearID">Year</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        &nbsp;
                    </div>   
                    {{--  Table is shown here --}}
                    <div class="col-md-12 mt-2 border border-top">
                        <table   class="table table-striped"   id="surveyFeedbackTableID">
                            <thead>
                                <tr>
                                    <th> Name </th>
                                    <th> {{ $address_or_emailLabel }} </th>
                                    <th> Telephone </th>
                                    <th> Status -Action</th>
                                    <th> Delivery</th>
                                </tr>
                            </thead>
                            <?php 
                                $userNameArray=[];
                                $userAddress_or_emailArray=[];
                                $userTelArray=[];
                                $userProxyArray=[];
                                $company_setting=$company_settings[0]; 
                                $preTextEmp=$company_setting->smsPreTextEmp;
                                $preTextSu=$company_setting->smsPreTextSu;

                                foreach($usersDetails as $userDetails){
                                    if ($isServiceUser==1){
                                        $userNameArray[$userDetails->userID]= $userDetails->title . ' ' . $userDetails->firstName . ' ' . $userDetails->lastName;
                                        $userAddress_or_emailArray[$userDetails->userID]=$userDetails->email;    //->address;
                                        $userProxyArray[$userDetails->userID]=$userDetails->proxy;
                                    }else if ($isServiceUser==0){
                                        $userNameArray[$userDetails->userID]= $userDetails->firstName . ' ' . substr($userDetails->middleName,0,1) . ' ' . $userDetails->lastName;
                                        $userAddress_or_emailArray[$userDetails->userID]=$userDetails->email;
                                        $userProxyArray[$userDetails->userID]=0;
                                    }    
                                    $userTelArray[$userDetails->userID]=$userDetails->tel;
                                }
                            ?>
                            <tbody>
                                @foreach($responseStatus as $response)
                                    <tr>
                                        <?php 
                                            //1. Just created   2. Not yet received  3. Received 
                                            $status="";
                                            $btn_color="";
                                            $btn_icon="";
                                            $statusID=0;
                                            if ($response->date_posted == null){
                                                $status="Created - now send";
                                                $btn_color="text-primary";
                                                $btn_icon="bi bi-send";
                                                $statusID=1;
                                            }elseif ($response->date_received == null){
                                                $status= "Posted - may resend";
                                                $btn_color="text-warning";
                                                $btn_icon="bi bi-send-plus-fill";
                                                $statusID=2;
                                            }else {
                                                $status= "Replied - may view";
                                                $btn_color="text-success";
                                                $btn_icon="bi bi-eye";
                                                $statusID=3;                       
                                            } 
                                            //If there is proxy, warn on the telephone
                                            $proxyColor="";
                                            if ($userProxyArray[$response->userID]==1){
                                                $proxyColor="text-warning";
                                            }
                                            //This is used to group check boxes for delivery
                                            $unique_value=$response->unique_value;
                                            if (is_null($response->unique_value)){
                                                $unique_value=substr(md5(uniqid(rand(), true)),0,7);
                                            }
                                            $checked1="";
                                            $checked2="";
                                            if ($response->sendByEmail==1){
                                                $checked2="checked";
                                            }else if ($response->sendByEmail==0){
                                                $checked1="checked";
                                            }
                                           
                                        ?>
                                        
                                        <td> {{$userNameArray[$response->userID]}} </td>
                                        <td> {{$userAddress_or_emailArray[$response->userID]}} </td>
                                        <td> 
                                            <span class="{{$proxyColor}}">  {{$userTelArray[$response->userID]}} </span>   
                                        </td>
                                        <td> 
                                           <span style="cursor:pointer" onClick="surveyFunc({{$response->userID}},{{$statusID}}, {{$response->responseTypeID}},   '{{$unique_value}}',   {{$response->sentCount}}, {{$response->sentEmailCount}},   '{{$userTelArray[$response->userID]}}' )">   {{$status}}  <i class="{{$btn_icon}}  {{$btn_color}}"></i> </span>
                                        </td>
                                        <td> 
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="{{$unique_value}}" id="{{$unique_value}}1" value="option1"  {{$checked1}} >
                                                <label class="form-check-label" for="{{$unique_value}}1">SMS</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="{{$unique_value}}" id="{{$unique_value}}2" value="option2"  {{$checked2}} >
                                                <label class="form-check-label" for="{{$unique_value}}2">Email</label>
                                             </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>   
                    </div>    

                </form>
            </div>
        </div>  
        <script>
            let token = "{{ csrf_token() }}";
            let _sendSMSURL= "{{ url('utility/user_sendsms')}}"; 
            let URLbase="{{ url('')}}";
            let smsPreTextSu= @json($preTextSu);
            let smsPreTextEmp= @json($preTextEmp);
            let user_viewURL= "{{ url('user/view_feedback')}}";
            let isServiceUser={{ $isServiceUser }};
            //adjust the datatable 's page
            let pageNo="{{ $pageNo }}";
            if (pageNo> 0){
                dataTable.page(pageNo);
            }
            let dateFlag={{$dateFlag }};
        </script>

    @endsection    
    @section('jscontents')
        <script src="{{asset('custom/js/backoffice/browse_surveyFeedback.js')}}"></script>
    @endsection     