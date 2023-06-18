<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            <span class="muted"> All fields are required </span>
        </h5>
        <!-- Floating Labels Form -->
        <form id= "addnew_formID"  class="row g-3 needs-validation" novalidate>
            @csrf
            <div class="col-md-2">
                <div class="form-floating">
                    <div class="form-floating mb-3">
                        <select  name="title"   class="form-select" id="selectTitleID" aria-label="Title">
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
            <?php 
                $firstName='';
                $lastName='';
                $postCode='';
                $mobile='';
                if ($user){
                    $firstName=$user->firstName;
                    $lastName=$user->lastName;   
                    $postCode=$user->address;
                    $mobile=$user->tel;
                }
            ?>
            <div class="col-md-5">
                <div class="form-floating">
                    <input  name="firstName"   type="text" class="form-control" id="suFirstNameID" placeholder="First Name"  value="{{$firstName}}"  required>
                    <label for="suFirstNameID">First Name</label>
                </div>
                <span class="text-danger firstName_err"></span>   
            </div>
        
            <div class="col-md-5">
                <div class="form-floating">
                    <input name="lastName"   type="text" class="form-control" id="suLastNameID" placeholder="Last Name" value="{{$lastName}}" required>
                    <label for="suLasttNameID">Last Name</label>
                </div>
                <span class="text-danger lastName_err"></span>   
            </div>
            
            <div class="col-md-4">
                <div class="form-floating">
                    <input name="postCode"  type="text" class="form-control" id="postCodeID" placeholder="Post Code" value="{{ $postCode}}" required>
                    <label for="postCodeID">Post Code</label>
                </div>
                <span class="text-danger postCode_err"></span>   
            </div>
            
            <div class="col-md-4">
                <div class="form-floating">
                    <input name="mobile" type="text" class="form-control" id="phoneID" placeholder="Mobile Number" value="{{$mobile}}" required>
                    <label for="phoneID">Mobile Number</label>
                </div>
                <span class="text-danger mobile_err"></span>   
            </div>
            
            <div class="col-md-4">
                <div class="form-floating">
                    <div class="form-floating mb-3">
                        <select name="proxy"  class="form-select" id="selectOwnerID" aria-label="Whose mobile number">
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
        
            <div class="text-right">
                @if (!$user)
                   <button type="button" class="btn btn-primary"  onClick="addnew_serviceUserFunc()" >Save</button>
                @endif    
            </div>
        </form><!-- End floating Labels Form -->
    </div>   

</div> 

