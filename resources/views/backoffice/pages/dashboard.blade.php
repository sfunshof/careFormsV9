@extends('backoffice.layouts.layout')
@section('title')
    Dashboard
@endsection
@section('contents')
    <section class="section dashboard">
        <?php 
            $quesTypeIDS= [];
            $CQCidS= [];
            $quesNameS=[];
            $quesOptionS=[];
            $CQCidS_su= [];
            $quesNameS_su=[];
            $quesOptionS_su=[];
            $quesTypeIDS_su=[];
            $CQCidS_emp= [];
            $quesNameS_emp=[];
            $quesOptionS_emp=[];
            $quesTypeIDS_emp=[];
      
            $quesTypeIDS_prev= [];
            $CQCidS_prev= [];
            $quesNameS_prev=[];
            $quesOptionS_prev=[]
    ?>
        @for ($j=1;$j<3;$j++)
            <?php 
                $status=0;
                      
                $chartDateArray= $chartDateArray_su;
                $dashboard_date= $dashboard_date_su;
                $dashboard_date_prev=$dashboard_date_prev_su;
                $respCountArray= $respCountArray_su;
                $postedCountArray= $postedCountArray_su;
                $quesOptions = $quesOptions_su; 
                $CQCids = $CQCids_su;
                $quesTypeIDs =$quesTypeIDs_su; 
                $quesNames = $quesNames_su;
                $employeeDates = $employeeDates_su;
                $responseKeyArray = $responseKeyArray_su;
                $responseValueArray= $responseValueArray_su;
                $userType="_su_";
                $userTypeName="service user";
                $MnNo=$MnNo_su; 
                $YrNo=$YrNo_su;     
                
                if ($j==2){
                    $chartDateArray= $chartDateArray_emp;
                    $dashboard_date= $dashboard_date_emp;
                    $dashboard_date_prev=$dashboard_date_prev_emp;
                    $respCountArray= $respCountArray_emp;
                    $postedCountArray= $postedCountArray_emp;
                    $quesOptions = $quesOptions_emp; 
                    $CQCids = $CQCids_emp;
                    $quesTypeIDs =$quesTypeIDs_emp; 
                    $quesNames = $quesNames_emp;
                    $employeeDates = $employeeDates_emp;
                    $responseKeyArray = $responseKeyArray_emp;
                    $responseValueArray= $responseValueArray_emp;
                    $userType="_emp_";
                    $userTypeName="employee";
                    $MnNo=$MnNo_emp; 
                    $YrNo=$YrNo_emp;   
                }
                    
                if (!isset($chartDateArray[0])) {
                    //Undefined latest date

                }else{
                    $status=1;
                    $latest_date= $chartDateArray[0];
                    
                    $quesTypeIDS= $quesTypeIDs[$latest_date];
                    $CQCidS= $CQCids[$latest_date];
                    $quesNameS=$quesNames[$latest_date];
                    $quesOptionS=$quesOptions[$latest_date];
                    if (!isset($chartDateArray[1])) {
                        //undefined prev date
                    }else{
                        $prev_date= $chartDateArray[0];
                        $quesTypeIDS_prev= $quesTypeIDs[$prev_date];
                        $CQCidS_prev= $CQCids[$prev_date];
                        $quesNameS_prev=$quesNames[$prev_date];
                        $quesOptionS_prev=$quesOptions[$prev_date];
                    }    
                }
               
            ?>
            <div class="row">
                @if ($status==0)
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">No Active Records</h6>
                                <div class="row">
                                    <p class="text-danger">
                                        There has been no records submitted by the {{$userTypeName}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>                
                @elseif ($status==1)
                    {{--  Begin the show  --}}
                    <?php 
                        $status2=1;
                        $posted=0;
                        $resp=0;
                        $per=0;
                        $survey_sent=" Survey posted to " .  $userTypeName . "s";
                        $survey_resp=" Survey responded to";
                        $respRate= " Response rate";
                        $date=get_arrayElement($chartDateArray,1);
                        if ($date=="undefined"){
                            $status2=0;
                        }else{
                            $posted=get_arrayElement($postedCountArray,$date);
                            $resp=get_arrayElement($respCountArray,$date);
                            $per=100*($resp/$posted);
                            $per=number_format($per, 0, '.', '' );
                        }
                        
                    ?>
                    <div  class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Current Data <span>|{{ $dashboard_date}}</span></h6>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people text-success"></i>
                                    </div>
                                    <div class="ps-3"> 
                                        <h5> <strong> {{ $postedCountArray[$chartDateArray[0]]}} </strong> <span> {{$survey_sent}} </span> </h5> 
                                        <h5> <strong> {{ $respCountArray[$chartDateArray[0]]}} </strong>  <span> {{ $survey_resp }} </span> </h5>  
                                        <h5> <strong> {{ number_format(100* $respCountArray[$chartDateArray[0]]/($postedCountArray[$chartDateArray[0]]), 0, '.', '' )  }}%   </strong> <span> {{ $respRate}}  </span> <h5>                         
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <div  class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                   $date_prev= $dashboard_date_prev;
                                   if ($date_prev=="") $date_prev="No Data Available";
                                ?>
                                <h6 class="card-title">Previous Data | <span>{{$date_prev}} </span>  </h6>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people text-primary"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h5><strong> {{$posted}} </strong> <span> {{$survey_sent}} </span> </h5> 
                                        <h5><strong> {{$resp}} </strong> <span> {{ $survey_resp }} </span> </h5>  
                                        <h5><strong> {{$per}}%</strong>  <span> {{ $respRate}}  </span> <h5>   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                    @foreach ($quesTypeIDS as $queTypeID)
                        @if (($quesTypeIDS[$loop->index]==2) && ($CQCidS[$loop->index] >0))
                            <?php
                                $idUserType=  $userType  . $loop->index;
                                $idYear="year" . $idUserType;
                                $idMonth="month" . $idUserType;
                                $idChart="chart" . $idUserType;
                                $idTable="table" . $idUserType;
                            ?>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">{{$CQCArray[$CQCidS[$loop->index]]  }}</h6>
                                        <div class="row">
                                            <label for="inputText"   style= "white-space:nowrap; overflow:hidden;text-overflow:ellipsis;"        class="col-md-8 col-form-label"> {{ $quesNameS[$loop->index]}} </label>
                                            <div class="form-floating mb-3 col-md-2">
                                                <select class="form-select" id="{{$idMonth}}"     aria-label="Floating label select example"  onChange="monthChangeFunc('{{$loop->index}}', '{{ $userType }}')" >
                                                    @for ($i = 0; $i < 12; $i++)
                                                        <option value="{{ $i+1 }}" @if (($i+1)==$MnNo) selected @endif    > 
                                                            {{ $months[$i] }}  
                                                        </option>
                                                    @endfor
                                                </select>
                                                <label for="{{$idMonth}}"> Select  </label>
                                            </div>
                                            <div class="form-floating mb-3 col-md-2">
                                                <select class="form-select" id="{{$idYear}}"  aria-label="Floating label select example"  onChange="yearChangeFunc('{{$loop->index}}', '{{ $userType}}')">
                                                    @for ($i = 0; $i < 3; $i++)
                                                        <option value="{{ $years[$i] }}" @if (($years[$i])==$YrNo) selected @endif > 
                                                            {{ $years[$i] }}  
                                                        </option>
                                                    @endfor
                                                </select>
                                                <label for="{{$idYear}}"> Select  </label>
                                            </div>
                                        </div> {{--  End  Questionaire Row --}}
                                        <div class="d-flex md-col-12">
                                            <div class= "d-inline-block w-50 " >
                                                {{-- Pie Chart --}} 
                                                <div class="chart-container" style="position: relative; height:40vh;width:80vw">
                                                    <div id="{{ $idChart }}"> </div>
                                                </div>
                                            </div>
                                            <div class="d-inline-block w-50 ">
                                                <table  id="{{ $idTable }}"   class="table table-sm caption-top table-striped table-bordered ">
                                                    <caption>Comparing Current data  with Previous cases</caption>
                                                    <thead>
                                                        <tr>
                                                            <th>Response</th>
                                                            <th>Current Data</th>
                                                            <th>Previous Data</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                
                                                    </tbody>   
                                                </table>                
                                            </div>
                                        </div>   
                                    </div>{{-- End card body --}}
                                </div> {{--  End Card --}}
                            </div> {{--  End md-12 Column --}}
                        @endif
                        <?php 
                            if ($j==1){
                                $CQCidS_su=$CQCidS;
                                $quesTypeIDS_su=$quesTypeIDS;
                                $quesOptionS_su=$quesOptionS;
                                $quesTypeIDS_su=$quesTypeIDS;
                            }elseif ($j==2){
                                $CQCidS_emp=$CQCidS;
                                $quesTypeIDS_emp=$quesTypeIDS;
                                $quesOptionS_emp=$quesOptionS;
                                $quesTypeIDS_emp=$quesTypeIDS;
                            }
                        ?>
                     @endforeach    
                @endif                           
            </div>{{--  End of Main row --}}
        @endfor
    </section>
    <script>
        let responseKeyArray_emp=@json($responseKeyArray_emp);
        let responseValueArray_emp=@json($responseValueArray_emp);
        let chartDateArray_emp=@json($chartDateArray_emp);
        let CQCArray_emp=@json($CQCidS_emp);
        let quesTypeID_emp=@json($quesTypeIDS_emp);
        let quesOptions_emp=@json($quesOptionS_emp);
        let quesOptionsArray_emp=@json($quesOptions_emp);

        let responseKeyArray_su=@json($responseKeyArray_su);
        let responseValueArray_su=@json($responseValueArray_su);
        let chartDateArray_su=@json($chartDateArray_su);
        let CQCArray_su=@json($CQCidS_su);
        let quesTypeID_su=@json($quesTypeIDS_su);
        let quesOptions_su=@json($quesOptionS_su);
        let quesOptionsArray_su=@json($quesOptions_su);
    </script>
@endsection

@section('jscontents')
    {{--  --
     <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js" integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    --}}
    <script src="{{asset('custom/js/backoffice/dashboard.js')}}"></script>
@endsection 










