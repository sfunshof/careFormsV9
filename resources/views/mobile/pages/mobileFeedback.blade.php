@extends('mobile.layouts.layout')
@section('title')
    Home    
@endsection
@section('contents')
    <div class="container pb-3 border-top">
        <div class="row">

            <h5  class="text-danger m-3 fs-5 text-center" id="errMsgID" style="display:none">
                Error: Please fill this question
            </h5>
            <?php
                $quesTypeIDArray=[]; 
                $CQCidArray=[];
                $quesIDArray=[]; 
                $quesNameArray=[];
                $quesOptionsArray=[];
                $hidden='';
                $quesNo=0; //Counts the right questionanire
                $pageNo=0;
                $pageNostr="";
                $successURL= 'user/successSaved/' . $mobile_companyName[0]->companyID;
                
                //Now add the thank you. B/c the last questionnaire needs to be pushed
                $thanksData = [
                    'responseTypeID' => 2,
                    'CQCid' => 0,
                    'quesTypeID' => 0,
                    'quesName' => " That is all. Please click the submit button to complete this survey. Thank you ",
                    'quesAttrib' =>[],
                    'quesID' => -5
                ];
                $quesCount++;
                array_push($quesForm, (object)$thanksData);
                 
            ?>
            @foreach($quesForm as $ques)   
                <?php 
                    
                    //** Better to use objects 
                    // array to hold  QuesTypeID  to be used by JS
                    //if ($ques->quesTypeID> 0 ){
                        array_push($quesTypeIDArray, $ques->quesTypeID); 
                        array_push($CQCidArray, $ques->CQCid);
                        //array to hold  QuesID to be used later by JS
                        array_push($quesIDArray, $ques->quesID); 
                        //array to hold Quesname
                        array_push($quesNameArray, $ques->quesName);
                        //array to hold the Options
                        array_push($quesOptionsArray, $ques->quesAttrib); 
                    //}    
                    
                    if ($ques->quesTypeID > 0){
                           $quesNo++;
                           $pageNoStr=$quesNo .  " .";
                        }elseif ($ques->quesTypeID == 0){
                           $pageNoStr="";
                    }    
                    
                    $parentIndex=$loop->index;
                    $hidden2="";
                    if ($ques->quesID==-5) $hidden2='style=display:none';
                    if ($parentIndex==1) $hidden='style=display:none';//hide all the other pages except page 1
                    $pageNo=$parentIndex+1;
                ?>
                <div id="div{{$loop->index}}"  {{ $hidden }} > {{--  We use this instead of quesID b/c quesID may start from 20: many clients --}}
                    <h5  class="text-secondary m-3 fs-5"> {{ $pageNoStr }}  {{$ques->quesName}}</h5>
    
                    @if ($ques->quesTypeID >0)
                        {{-- radio options --}}
                        @if ($ques->quesTypeID ==2)
                        <?php 
                            $options= json_decode($ques->quesAttrib)
                        ?>
                        
                        @foreach($options as $option)
                            <label class="form-control  border border-0 ">
                                <input type="radio"  name="radio{{$parentIndex}}" value="{{$option}}"   id= "radio{{$parentIndex}}{{$loop->index}}id"     onClick="radioClickFunc('radio{{$parentIndex}}{{$loop->index}}id',  'otherDiv{{$parentIndex}}ID',  'otherText{{$parentIndex}}ID'    )" />
                                {{ $option}}
                            </label>
                        @endforeach
                        {{--  In case there is an others here we simply show them this text --}}
                         <div class="form-floating ms-2 mt-3" id="otherDiv{{$parentIndex}}ID" style="display:none">
                            <textarea id="otherText{{$parentIndex}}ID" class="form-control p-2 border border-success"></textarea>
                            <label for="otherText{{$parentIndex}}ID">...</label>
                        </div>

                        {{-- text box --}}
                        @elseif ($ques->quesTypeID==1)
                            <div class="form-floating m-3"   name="text{{$loop->index}} ">
                                <textarea id="text{{$loop->index}}id" class="form-control border border-success"  oninput="textAreaFunc('text{{$loop->index}}id', '{{$parentIndex}}')" ></textarea>
                                <label for="text{{$loop->index}}id">...</label>
                            </div>
                        @endif
                    @endif  
                    <div> 
                        <p  {{$hidden2}} class="fw-normal text-center mt-2">{{ $pageNo}} out of {{ $quesCount -1}}   </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
          
         
        


        {{--  fake ids to be used in js --}}
        @for ($i = $quesCount; $i < 51; $i++)
            <div id="div{{ $i}}" class="d-none" > </div>
        @endfor
        <script>    
            let CQCidArray=@json($CQCidArray); //0, 2, 4
            let quesTypeIDArray= @json($quesTypeIDArray); // 0,   1,  1,   1,  2,  0
            let quesIDArray= @json($quesIDArray);         // 40, 45,  49,  54, 56, 67
            let quesNameArray=@json($quesNameArray);      // how, What, how, ...
            let quesOptionsArray=@json($quesOptionsArray); //[good,better,best], [ok,fair,not], [yes,no]
            
            let unique_value= "{{ $unique_value}}";
            let responseTypeID={{$responseTypeID}};
            let userID={{ $userID }};
            let user_saveFeedbackURL= "{{ url('user/save_feedback')}}"; 
            let user_successSaveURL= "{{ url($successURL)}}";
            let token = "{{ csrf_token() }}";
            
            //alert(JSON.stringify(quesOptionsArray));

            //Put js definattion from php here 
            //var get_pastInvoicesURL= "{{ url('invoice/get_pastinvoices')}}"; 
           
        </script>

@endsection