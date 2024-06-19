@php 
    $quesNo=0;  
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

    @endphp    
    <div class="mb-3" id="div{{$i}}" style="display:{{ $style }}">  
        <div class="showBottom">
            <label for="radio{{$i}}" class="form-label"> {{ $pageNoStr}}    {{ $quesName}} </label>
            @if ($quesTypeID==2 )
                @foreach($options as $option)
                    <div class="form-check m-4">
                        <input class="form-check-input"   onClick="radioClickFunc({{$i}}, '{{$option}}')"   id="{{$option}}{{$i}}"  type= "radio"  name="radio{{$i}}" value="{{ $option}}">
                        <label class="form-check-label"  for="{{$option}}{{$i}}">{{ $option}}</label>
                    </div>    
                @endforeach
                <div id="box{{$i}}" style="display:none">
                    <label  class="form-label" for="other{{$i}}"> What are the other points?</label>
                    <textarea  onBlur="otherTextAreaClickFunc({{$i}})"    placeholder="Add them here ..."  id="other{{$i}}" class="form-control p-2 border border-success"></textarea>
                </div>
            @elseif ($quesTypeID==1) 
                <label class="form-label" for="text{{$i}}">Add them here </label>
                <textarea onInput="textAreaClickFunc({{$i}})"     placeholder="..."  id="text{{$i}}" class="form-control p-2 border border-success"></textarea>      
            @endif
            <!-- Never shown but used by desktop version -->
            <span class="clone_danger"  id="err_{{$quesNo}}" style="display:none"> Please respond to Ques.  {{$quesNo}}  </span>   
        </div>   
        <p class="text-center"> {{ $i+1}}  out of  {{$count + $pageCount }} </p> 
    </div>                      
@endfor
