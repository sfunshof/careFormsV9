<div class="card">
    <div class="card-body">
        {{--  
        <h6 class="card-title"> {{$companyName}} : Spot Checks</h6>
        --}}
        <h6 class="card-subtitle mb-2 text-muted">Carer: {{ $carerName }}    Client: {{ $serviceUserName }}  </h6>
        <h6 class="card-subtitle mb-2 text-muted">Assessor: {{ $supervisor}}  Date: {{ $date_issue }}    </h6>
        <hr>
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
                                
                                //This is for selected value 
                                $selectTemp=$responses_array[$i];
                                $selectedValue=implode(',', $selectTemp);
                                if (strpos($selectedValue, "Others:,Others") !== false) {
                                    // Replace it with "Others:"
                                    $choosenString = str_replace("Others:,Others", "Others", $selectedValue);
                                } else {
                                    // Otherwise, keep the original string
                                    $choosenString = $selectedValue;
                                }
                            @endphp
                    
                            @if ($strLength==0) {{--  Empty string length --}}
                                {{--  <li></li> --}}
                            @else
                                @foreach ($quesOptions_inner_array as $item)
                                    <li class="d-flex align-items-center">
                                        {{-- <span class="me-2 fs-4">&bull;</span>--}} 
                                        {{ $item }}
                                </li>
                                @endforeach
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
                
                    <span class="stars-landing" style="--rating: {{ $rating }};" aria-label="Average carer rating is {{ $rating }} out of 5.">★★★★★</span>
                </div>
            </div>
            <hr>
        </form>
    </div>
</div>   