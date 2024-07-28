<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            <span class="muted"> All fields are required </span>
        </h5>
        <!-- Floating Labels Form -->
        <form id= "addnew_formID"  class="row g-3 needs-validation" novalidate>
            @csrf
            <?php 
                $firstName='';
                $middleName='';
                $lastName='';
                $email='';
                $mobile='';
                $isCOS=0;
                $appDate='';
                $interDate='';
                $COSdate='';
                $arrDate='';
                $DBSdate='';
                $startDate='';
                $style='';
                $officePostcode='';
                if ($user){
                    $firstName=$user->firstName;
                    $middleName=$user->middleName;
                    $lastName=$user->lastName;   
                    $email=$user->email;
                    $mobile=$user->tel;
                    $officePostcode=$user->officePostcode;
                    $isCOS=$user->isCOS;
                    if ($isCOS === 1) {
                        $style = 'display: block; overflow: visible;';
                        $jsonData = json_decode($user->COSdates);
                       
                        //if (is_array($jsonData)) {
                            // Access data using array keys
                            $appDate =  $jsonData->appDate;
                            $interDate = $jsonData->interDate;
                            $COSdate = $jsonData->COSdate;
                            $arrDate = $jsonData->arrDate;
                            $DBSdate = $jsonData->DBSdate;
                            $startDate = $jsonData->startDate;

                        //} 
                           
                    }    
                }
                
            ?>
            <div class="col-md-4">
                <div class="form-floating">
                    <input  name="firstName"   type="text" class="form-control" id="firstNameID" placeholder="First Name"  value="{{$firstName}}"  required>
                    <label for="firstNameID">First Name</label>
                </div>
                <span class="text-danger firstName_err"></span>   
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                    <input  name="middleName"   type="text" class="form-control" id="middleNameID" placeholder="First Name"  value="{{$middleName}}"  required>
                    <label for="middleNameID">Middle Name</label>
                </div>
                <span class="text-danger middleName_err"></span>   
            </div>
            <div class="col-md-5">
                <div class="form-floating">
                    <input name="lastName"   type="text" class="form-control" id="lastNameID" placeholder="Last Name" value="{{$lastName}}" required>
                    <label for="lasttNameID">Last Name</label>
                </div>
                <span class="text-danger lastName_err"></span>   
            </div>
            
            <div class="col-md-5"> 
                <div class="form-floating">
                    <input name="email"  type="text" 
                     class="form-control @if ($user) disabled @endif"
                     id="emailID"    placeholder="Email" value="{{ $email}}" required>
                    <label for="email">Email</label>
                </div>
                <span class="text-danger email_err"></span>   
            </div>
            
            <div class="col-md-4">
                <div class="form-floating">
                    <input name="mobile" type="text" class="form-control" id="phoneID" placeholder="Mobile Number" value="{{$mobile}}" required>
                    <label for="phoneID">Mobile Number (inc +44)</label>
                </div>
                <span class="text-danger mobile_err"></span>   
            </div>
            
            <div class="col-md-3">
                <div class="form-floating">
                    <div class="form-floating mb-3">
                        <select name="jobFunction"  class="form-select" id="selectJobID" aria-label="Job Functiom">
                            <?php 
                                $jobArray=['Front Line', 'Back Ofice'];
                                foreach($jobArray as $key=>$job){
                                    $selected="";
                                    if ($user){
                                        $selected = $user->jobFunction == $key ? ' selected' : '';
                                    }
                                    echo '<option value="' . $key .  '"' . $selected . '>'. $job  .'</option>'."\n";
                                }

                            ?>
                        </select>
                        <label for="selectJobID">Job Function</label> 
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-1">
                <div class="form-floating">
                    <input  name="officePostcode"   type="text" class="form-control" id="officePostcodeID" placeholder="Office Postcode"  value=  "{{$officePostcode}}" >
                    <label for="officePostcodeID">Office Postcode </label>
                </div>
                <span class="text-danger officePostcode_err"></span>   
            </div>

            <div class="form-check form-switch text-right d-none">
                <input class="form-check-input" type="checkbox" id="COSswitchID" 
                @if ($isCOS)
                    checked
                @endif
                onchange="show_COSFunc()">
                <label class="form-check-label" for="COSswitchID">Employee on COS</label>
            </div>
            
            <div class="COSshowID"  style="{{ $style }}" >

                <div class="row ">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input  name="appDate"   type="text" class="form-control" id="appDateID" placeholder="Application Date"  value=  "{{$appDate}}"  data-datepicker>
                            <label for="appDateID">Application Date</label>
                        </div>
                        <span class="text-danger appDate_err"></span>   
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input  name="interDate"   type="text" class="form-control" id="interDateID" placeholder="Interview Date"  value="{{$interDate}}" data-datepicker>
                            <label for="interDateID">Interview Date</label>
                        </div>
                        <span class="text-danger interDate_err"></span>   
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input name="COSdate"   type="text" class="form-control" id="COSdateID" placeholder="COS Issue Date" value="{{$COSdate}}" data-datepicker>
                            <label for="COSdateID">COS Issue Date</label>
                        </div>
                        <span class="text-danger COSdate_err"></span>   
                    </div>
                </div>   
                <div class="row"> 
                    <div class="col-md-6">&nbsp;</div>
                     <div class="col-md-6">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input name="arrDate"   type="text" class="form-control" id="arrDateID" placeholder="Arrival Date in UK" value="{{$arrDate}}" data-datepicker>
                            <label for="arrDateID">Arrival Date in UK </label>
                        </div>
                        <span class="text-danger arrDate_err"></span>   
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input name="DBSdate"   type="text" class="form-control" id="DBSdateID" placeholder="DBS Issue Date" value="{{$DBSdate}}"  data-datepicker >
                            <label for="DBSdateID">DBS Issue Date</label>
                        </div>
                        <span class="text-danger DBSdate_err"></span>   
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input name="startDate"   type="text" class="form-control" id="startDateID" placeholder="Contract Start Date" value="{{$startDate}}" data-datepicker>
                            <label for="startDateID">Contract Start Date </label>
                        </div>
                        <span class="text-danger startDate_err"></span>   
                    </div>

                </div>
                <hr class="my-horizontal-line">
            </div>
          

        
        
            <div class="text-right">
                @if (!$user)
                   <button type="button" class="btn btn-primary"  onClick="addnew_employeeFunc()" >Save</button>
                @endif    
            </div>
        </form><!-- End floating Labels Form -->
    </div>   

</div> 
{{--  After Updating we simulate a click here so that browsing gets reloaded  --}}
<a id="simulateLink" class="d-none" href="{{ url('/employee/browse')}}"> Update  </a>

