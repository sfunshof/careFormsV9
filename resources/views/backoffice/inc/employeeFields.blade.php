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
                if ($user){
                    $firstName=$user->firstName;
                    $middleName=$user->middleName;
                    $lastName=$user->lastName;   
                    $email=$user->email;
                    $mobile=$user->tel;
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
                    <input name="email"  type="text" class="form-control" id="emailID" placeholder="Email" value="{{ $email}}" required>
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