<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center @if ($isPrint === 1) d-none @endif ">
            <h5 class="card-title mb-0">
                <span class="muted">All fields are required</span>
            </h5>
            @if ($isProspect==1)
                <div class="radio-box d-none">
                    <div class="form-check form-check-inline mb-0">
                        <input class="form-check-input" type="radio" name="prospectRadio" id="basicInfoID"   {{ $isView === 0 ? '' : 'disabled' }}   checked onchange="show_basicFormFunc()"> 
                        <label class="form-check-label" for="basicInfoID">Personal Info</label>
                    </div>
                    <div class="form-check form-check-inline mb-0">
                        <input class="form-check-input" type="radio" name="prospectRadio" id="assessInfoID"   disabled onchange="show_assessFormFunc()"> 
                        <label class="form-check-label" for="assessInfoID">Assessment Info </label>
                    </div>
                </div>
             @endif   
        </div>
        <!-- Floating Labels Form -->
        @php
            $isView = $isView ?? 0;
            $firstName='';
            $lastName='';
            $postCode='';
            $mobile='';
            $email='';
            $NiN='';
            $NhsN='';
            $DOB='';
            $address='';
            $userReligion=0;
            $userGender=0;
        
            if ($user){
                $firstName=$user->firstName;
                $lastName=$user->lastName;   
                $postCode=$user->address;  //watch it
                $mobile=$user->tel;
                $email=$user->email;
                if ($isProspect==1){
                    $jsonData=$user->prospectJSON;
                    // Decode the JSON field to an associative array
                    $details = json_decode($jsonData, true);
                    $NiN = $details['Nin'];
                    $NhsN = $details['Nhs'];
                    $DOB = $details['DOB'];
                    $address=$details['address']; //** watch it
                    $userGender =   $details['gender'];
                    $userReligion = $details['religion'];
                }
            }
        @endphp 
        <div class="named-line">
            <span class="line"></span>
            <span class="name">Basic Information</span>
            <span class="line"></span>
        </div>
        <form id= "addnew_formID"  class="row g-3 needs-validation" novalidate>
            @csrf
            <div class="row g-3">
                <div class="col-md-2">
                    <div class="form-floating">
                        <div class="form-floating mb-3">
                            <select  name="title"   class="form-select prepopulated" id="selectTitleID" aria-label="Title"  @if ($isPrint === 1) data-print @endif  >
                                <?php 
                                    $titleArray=['Mr','Mrs', 'Ms', 'Miss','Dr'];
                                    foreach($titleArray as $title){
                                        $selected="";
                                        if ($user){
                                            $selected = $user->title == $title ? ' selected' : '';
                                        }
                                        echo '<option value="' . $title .  '"' . $selected . '>'. $title  .'</option>'."\n";
                                    }

                                ?>
                            </select>
                            <label for="selectTitleID">Title</label>
                        </div>
                    </div>
                </div>
            
                
                <input type="hidden" name="isProspect" value="{{$isProspect}}">
                <input type="hidden" name="prospectRandomNo"  >
                <input type="hidden" name="count" value="{{ $count}}">
                {{--  UserID --}}
                <input type="hidden" name="userID" value="{{$userID}}">
             
                <div class="col-md-3">
                    <div class="form-floating">
                        <input  name="firstName"   type="text" class="form-control prepopulated" id="suFirstNameID" placeholder="First Name"  value="{{$firstName}}"  @if ($isPrint === 1) data-print @endif >
                        <label for="suFirstNameID">First Name</label>
                    </div>
                    <span class="text-danger firstName_err"></span>   
                </div>
            
                <div class="col-md-4">
                    <div class="form-floating">
                        <input name="lastName"   type="text" class="form-control" id="suLastNameID" placeholder="Last Name" value="{{$lastName}}" @if ($isPrint === 1) data-print @endif >
                        <label for="suLasttNameID">Last Name</label>
                    </div>
                    <span class="text-danger lastName_err"></span>   
                </div>
            
                <div class="col-md-3">
                    <div class="form-floating">
                        <input name="postCode"  type="text" class="form-control" id="postCodeID" placeholder="Post Code" value="{{ $postCode}}"  @if ($isPrint === 1) data-print @endif >
                        <label for="postCodeID">Post Code</label>
                    </div>
                    <span class="text-danger postCode_err"></span>   
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-floating">
                        <input name="mobile" type="text" class="form-control" id="phoneID" placeholder="Mobile Number" value="{{$mobile}}" @if ($isPrint === 1) data-print @endif >
                        <label for="phoneID">Mobile Number (inc +44)</label>
                    </div>
                    <span class="text-danger mobile_err"></span>   
                </div>
                
                <div class="col-md-5">
                    <div class="form-floating">
                        <input name="email"  type="text" class="form-control" id="emailID" placeholder="Email" value="{{ $email}}" @if ($isPrint === 1) data-print @endif >
                        <label for="emailID">Email</label>
                    </div>
                    <span class="text-danger email_err"></span>   
                </div>
                

                <div class="col-md-3">
                    <div class="form-floating">
                        <div class="form-floating mb-3">
                            <select name="proxy"  class="form-select" id="selectOwnerID" aria-label="Whose mobile number" @if ($isPrint === 1) data-print @endif >
                                <?php 
                                    $proxyArray=['Service User', '3rd Party'];
                                    foreach($proxyArray as $key=>$proxy){
                                        $selected="";
                                        if ($user){
                                            $selected = $user->proxy == $key ? ' selected' : '';
                                        }
                                        echo '<option value="' . $key .  '"' . $selected . '>'. $proxy  .'</option>'."\n";
                                    }

                                ?>
                            </select>
                            <label for="selectOwnerID">Whose Mobile Number</label>
                        </div>
                    </div>
                </div>
            </div>   
            <!-- Prospects -->
            <div class="row g-3 {{ $isProspect ? 0 : 'hidden' }}">
                <div class="named-line">
                    <span class="line"></span>
                    <span class="name">Assessment</span>
                    <span class="line"></span>
                </div>
                <!-- Assessments -->
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input name="NiN"  type="text" class="form-control" id="NiNID" placeholder="NI Number" value="{{ $NiN}}"   @if ($isPrint === 1) data-print @endif >
                            <label for="NiNID">NI Number</label>
                        </div>
                        <span class="text-danger NiN_err"></span>   
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input name="NhsN"  type="text" class="form-control" id="NhsNID" placeholder="NHS Number" value="{{ $NhsN}}" @if ($isPrint === 1) data-print @endif  >
                            <label for="NhsNID">Nhs Number</label>
                        </div>
                        <span class="text-danger NhsN_err"></span>   
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input name="DOB"  type="text" class="form-control" id="DOBID" placeholder="Date Of Birth" value="{{ $DOB}}"  data-datepicker @if ($isPrint === 1) data-print @endif >
                            <label for="DOBID">Date of Birth</label>
                        </div>
                        <span class="text-danger DOB_err"></span>   
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <div class="form-floating mb-3">
                                <select name="religion"  class="form-select" id="selectReligionID" aria-label="Select the Religion" @if ($isPrint === 1) data-print @endif >
                                    <?php 
                                        //$religionArray=['Christianit (Protestants)','Christianity (Roman Catholicism)','Christianity (Others)',
                                        //                'Islam','Hinduism','Sikhism','Judaism','Buddhism','Other religions','Secular and non-religious'];
                                        foreach($religionArray as $key=>$religion){
                                            $selected="";
                                            if (isset($userReligion) && $userReligion == $key) {
                                                $selected = ' selected';
                                            }
                                            echo '<option value="' . $key .  '"' . $selected . '>'. $religion  .'</option>'."\n";
                                        }

                                    ?>
                                </select>
                                <label for="selectReligionID">Select the Religion</label>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-3">
                        <div class="form-floating">
                            <div class="form-floating mb-3">
                                <select name="gender"  class="form-select" id="selectGenderID" aria-label="Select the Gender" @if ($isPrint === 1) data-print @endif >
                                    <?php 
                                        $genderArray=['Male', 'Female'];
                                        foreach($genderArray as $key=>$gender){
                                            $selected="";
                                            if (isset($userGender) && $userGender == $key) {
                                                $selected = ' selected';
                                            }
                                            echo '<option value="' . $key .  '"' . $selected . '>'. $gender  .'</option>'."\n";
                                        }

                                    ?>
                                </select>
                                <label for="selectGenderID">Select the Gender</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-floating">
                            <textarea name="address" rows="3" class="form-control" id="addressID" placeholder="address"  @if ($isPrint === 1) data-print @endif   >{{ $address }}</textarea>
                            <label for="addressID">1st Line of Address</label>
                        </div>
                        <span class="text-danger address_err"></span>
                    </div>
                </div>   
            </div>    
            
        
            <div class="text-right">
                @if (!$user)
                   <button type="button" class="btn btn-primary"  onClick="addnew_serviceUserFunc()" >Save</button>
                @endif    
            </div>
        </form><!-- End floating Labels Form -->
        
        <!-- The assessment starts here -->
        <!-- Floating Labels Form -->
        <form id= "prospect_formID"  class="row g-3 needs-validation novalidate  {{ $isView ? 0 : 'hidden' }}"   >
            @csrf
            <h5 class="text-center text-success"> Now fill in the Assessment Questionnaires </h5>
            
            <!--  isView=AddNew, Update, View, Print -->
            @if ($isView==0)
                @include('mobilespotcheck.fakecomponents.quesTemplate') 
            @elseif (($isView==1) && ($isPrint==0))
                @include('backoffice.pages.edit_quesTemplate') 
            @elseif (($isView==1) && ($isPrint==1))
                @include('backoffice.pages.edit_quesTemplate')     
            @endif
            <div class="text-right">
                @if (!$user)
                <button type="button" class="btn btn-primary"  onClick="save_prospectQuesFunc(-1)" >Save</button>
                @endif    
            </div>
        </form>    
        @if (($user) && ($isProspect==1))
            <p class="fst-italic text-end">
                Accessed by: {{ $accessByArray->first()->supervisor}}  &nbsp; Date: {{ $accessByArray->first()->date_issue }} 
            </p>
         @endif    
     </div>   

</div> 

{{--  After Updating we simulate a click here so that browsing gets reloaded  --}}
<a id="simulateLink" class="d-none" href="{{ url('/serviceUser/browse')}}"> Update  </a>
<a id="simulateLink_isProspect" class="d-none" href="{{ url('/prospect/browse')}}"> Update  </a>
<!-- this is for loading the styles via js  or running extrernl functions-->
<button id="externalFuncBtnID" type="button"  class="d-none"  onClick="externalFunc()"> style </button>

<script>
    let isProspect={{$isProspect}};
</script>     