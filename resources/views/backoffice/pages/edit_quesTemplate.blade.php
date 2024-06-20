@php 
    $quesNo=0;
    //converts
    //[ ["A"], ["B"], ["C"], ["D"] ] to   ["A", "B", "C", "D"]
    //$responseArray = array_map(fn($subArray) => $subArray[0], $responsesArray);
      
    //This is for selected value 
    
@endphp

@for ($i =0; $i < $count; $i++)
    @php 
        $quesTypeID=$spotCheckQues[$i]['quesTypeID'];
        $quesName=$spotCheckQues[$i]['quesName'];
        $attrib=$spotCheckQues[$i]['quesAttrib'];
        $style="block";

        //Paginition removed for desktop
        $pageCount=1;
        if ($i>0) $style="none";
        if (isset($showAll)){
            $style="block";
            $pageCount=0;
        }
                
        $options=[];
        if ( $quesTypeID > 0){
            $quesNo++;
            $pageNoStr=$quesNo .  " .";
            if ($quesTypeID ==2){ 
                $options= json_decode($attrib);
            }
            //$optionString=implode(',', $options);
        }elseif ($spotCheckQues[$i]['quesTypeID'] == 0){
            $pageNoStr="";
        }
        
        $selectTemp=$responsesArray[$i];
        $selectedValue=implode(',', $selectTemp);
        $printedString= $selectedValue;
        $otherStyle="none";
        $otherString="";
        if (substr_count($selectedValue, "Others:") >= 2){
            // Replace it with "Others:"
            $otherString = str_replace("Others:,Others:", "", $selectedValue);
            $selectedValue="Others";
            $otherStyle="block";
            //$printedString=$otherString
            $printedString= str_replace('\\', '', $otherString);
        }


    @endphp    
    <div class="mb-3" id="div{{$i}}" style="display:{{ $style }}">  
        <div class="showBottom">
            <label for="radio{{$i}}" class="form-label"> {{ $pageNoStr}}    {{ $quesName}} </label>
            @if ($quesTypeID==2 )
                
               @foreach($options as $option)
                    <div class="form-check m-4">
                        <input class="form-check-input"   onClick="radioClickFunc({{$i}}, '{{$option}}')"   
                        id="{{$option}}{{$i}}"  type= "radio"  name="radio{{$i}}" value="{{ $option}}" 
                        @if ($selectedValue !== $option && $isPrint === 1)
                            data-Print
                        @endif
                        @checked($selectedValue === $option)>
                        <label class="form-check-label"  for="{{$option}}{{$i}}">{{ $option}}</label>
                    </div>    
                @endforeach
                
                <div id="box{{$i}}" style="display:{{$otherStyle}}">
                    <label  class="form-label" for="other{{$i}}"> What are the other points?</label>
                    <textarea  rows="5" onBlur="otherTextAreaClickFunc({{$i}})"    placeholder="Add them here ..."  id="other{{$i}}" class="form-control p-2 border border-success custom-textarea">{{$otherString}}</textarea>
                </div>
            @elseif ($quesTypeID==1) 
                <label class="form-label" for="text{{$i}}">Add them here </label>
                <textarea rows="5" onInput="textAreaClickFunc({{$i}})"     placeholder="..."  id="text{{$i}}" class="form-control p-2 border border-success custom-textarea">{{$printedString}}</textarea>      
            @endif
            @if (!empty($printedString))
                <span class="d-flex align-items-center position-relative ms-4">
                    <span class="me-2 fs-4">&#42;</span> 
                    <span class="fst-italic"> <strong>{{ $printedString }}</strong> </span>
                </span>
            @endif    
            <!-- Never shown but used by desktop version -->
            <span class="clone_danger"  id="err_{{$quesNo}}" style="display:none"> Please respond to Ques.  {{$quesNo}}  </span>   
        </div>   
        <p class="text-center"> {{ $i+1}}  out of  {{$count + $pageCount }} </p> 
    </div>                      
@endfor
   
