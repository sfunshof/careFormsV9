<div class="card">
    <div class="card-body">
        @if($pdf_print==1)
            <span class="card-title">
               <span> <strong> {{$companyName}} </strong> :    &nbsp; Spot Checks <span> <br>
               <span> <strong>  Carer </strong> :   {{ $carerName}}: &nbsp; <strong> Client </strong> :  {{ $serviceUserName }} <br>
                <span> <strong> Accessor </strong> :  {{ $supervisor}} <strong> Date </strong>  : {{ $date_issue}} 
            </span>
        @endif
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
                   
                    @if($pdf_print==0)
                        <span class="stars-landing" style="--rating: {{ $rating }};" aria-label="Average carer rating is {{ $rating }} out of 5.">★★★★★</span>
                    @else                                    
                        <div>
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $rating)
                                    <!-- Gold star -->
                                    <span style="color: gold; font-size:45px; font-weight: bold;">*</span>
                                @else
                                    <!-- Grey star -->
                                    <span style="color: grey; font-size:45px; font-weight: bold;">*</span>
                                @endif
                            @endfor
                        </div>
                    @endif   
                                       

                </div>
            </div>
        </form>
    </div>
</div>               

<script>
    //This must be o a single line
    let myTitle = ` <h4> <span class="text-center"> {{ $companyName }} Spot Checks </span> </h4><h5>  <span class="text-center">  Carer: {{ $carerName }} Service user: {{ $serviceUserName }} </span> </h5><h5> <span class="text-center">  Accessor: {{ $supervisor }} Date: {{ $date_issue }} </span> </h5>`;
</script>    