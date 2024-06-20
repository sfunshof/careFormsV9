<div class="card">
    <div class="card-body">
        <form class="row g-3">  
            <div class="col-12">  
                @php
                    $quesNo=0;
                @endphp
                @for ($i=0;$i<$count;$i++) 
                    @php
                        $quesNo_str='';
                    @endphp
                    @if ($quesTypeID_array[$i]>0)
                        @php
                            $quesNo_str='';
                            $quesNo++;
                            $quesNo_str=$quesNo . '.';
                        @endphp
                    @endif
                    <p>
                        <span> {{$quesNo_str}} </span> <span>{{ $quesNames_array[$i]}} </span> 
                        <ul class="list-unstyled mb-0">
                            @php
                                $str="krr"; //for test purposes
                                //the inner one is a string. Needs to be convetred
                                //"[\"Yes\",\"No\"]";
                                $quesOptions_inner_array=json_decode($quesOptions_array[$i]);
                                $strLength =count($quesOptions_inner_array);
                                
                                $radioName="radio". $i;
                                $textName="text" . $i;
                                $textAreaId="textArea" . $i;         
                                $otherStyle="display:none";
                                
                                //This is for selected value 
                                $selectTemp=$responses_array[$i];
                                $selectedValue=implode(',', $selectTemp);

                                $radioMatchedvalue=$selectedValue;
                                if (strpos($selectedValue, "Others:,Others") !== false) {
                                    // Replace it with "Others:"
                                    $choosenString = str_replace("Others:,Others", "Others", $selectedValue);
                                    $otherStyle="display:block";
                                    $radioMatchedvalue="Others";
                                } else {
                                    // Otherwise, keep the original string
                                    $choosenString = $selectedValue;
                                }
                            @endphp
                            
                          
                            @if ($strLength==0) {{--  Empty string length --}}
                                {{--  <li></li> --}}
                                @if (!empty($selectedValue))
                                    <textarea  rows="5" id="{{ $textAreaId}}"   class="form-control custom-textarea">{{ $selectedValue }}</textarea>
                                @endif
                            @else
                                @foreach ($quesOptions_inner_array as $item)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="{{$radioName}}{{ $loop->index }}" name="{{ $radioName }}" 
                                        value="{{ $item }}"  onClick= "showOtherTextFunc('{{$item}}', {{ $i }})"
                                        @checked($radioMatchedvalue === $item)
                                        >
                                        <label class="form-check-label" for="{{$radioName}}{{ $loop->index }}">
                                            {{ $item }}
                                        </label>
                                    </div>
                                @endforeach
                                @php
                                    $textOthers= str_replace("Others:,Others", "", $selectedValue);
                              @endphp
                                <input type="text" id="{{ $textName}}"  name="{{$textName}}"    value="{{ $textOthers}}" style="{{ $otherStyle }};"   class="form-control">

                            @endif    
                            @if (!empty($choosenString))
                                <span class="d-flex align-items-center position-relative ms-4">
                                    <span class="me-2 fs-4">&#42;</span> 
                                    <span class="fst-italic"> <strong>{{ $choosenString }}</strong> </span>
                                </span>
                            @endif    
                        </ul>
                    </p>
                    <div class="text-center my-4"> <!-- Centered text with top and bottom margin -->
                        <span class="d-block">{{ $i+1 }} out of {{ $count }}</span> <!-- Displayed text -->
                        <hr class="my-2"> <!-- Horizontal line -->
                    </div>
                @endfor
                <!-- This is where you draw the star -->
                <div class="centered-span">
                    <p>
                        Please rate the overall performance of the staff
                    </p>    
                   
                    
                    <div class="star-rating">
                        <i class="star fas fa-star" data-rating="1" onClick="setRatingFunc(1)"></i>
                        <i class="star fas fa-star" data-rating="2" onClick="setRatingFunc(2)"></i>
                        <i class="star fas fa-star" data-rating="3" onClick="setRatingFunc(3)"></i>
                        <i class="star fas fa-star" data-rating="4" onClick="setRatingFunc(4)"></i>
                        <i class="star fas fa-star" data-rating="5" onClick="setRatingFunc(5)"></i>
                        <input type="hidden" id="selected-rating" value="{{ $rating }}">
                    </div>
                   
                    <span class="d-flex align-items-center position-relative ms-4">
                        <span class="me-2 fs-4">&#42;</span> 
                        <span class="fst-italic"> <strong>{{ $rating}}</strong> Star Rating</span>
                    </span>

                </div>
            </div>
            <input type="text" value="{{ $count }}"  id ="countID"  class="d-none">
            <input type="text"  id ="inputKeyID"   class="d-none">
        </form>
    </div>
</div>               

<script>
    //This must be o a single line
    let myTitle = ` <h4> <span class="text-center"> {{ $companyName }} Spot Checks </span> </h4><h5>  <span class="text-center">  Carer: {{ $carerName }} Service user: {{ $serviceUserName }} </span> </h5><h5> <span class="text-center">  Accessor: {{ $supervisor }} Date: {{ $date_issue }} </span> </h5>`;
 </script>