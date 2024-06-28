@php
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

<div class="scrollable-form-container">
    <form method="POST"  class="row g-3 needs-validation"   id="addnew_formID"  novalidate  >
        @csrf <!-- CSRF token -->
        
        <input  name="userID"   type="hidden"  value=-1   >

        <h4 class="mb-4 text-center">Please fill all these fields</h4>
        <div class="mb-3">
            <div class="form-floating col-8">
                <select  name="title"   class="form-select" id="selectTitleID" aria-label="Title">
                    <?php 
                       // $titleArray=['Mr','Mrs', 'Ms', 'Miss','Dr'];
                        foreach($titleArray as $title){
                            $selected="";
                            if ($user){
                               $selected = $user->title == $title ? ' selected' : '';
                            }
                            echo '<option value="' . $title .  '"' . $selected . '>'. $title  .'</option>'."\n";
                        }

                    ?>
                </select>
                <label for="selectTitleID">Select the Title</label>
            </div>
        </div>
        <div class="mb-3">
            <div class="form-floating col-10 ">
                <input  name="firstName"   type="text" class="form-control" id="suFirstNameID" placeholder="First Name"  value="{{$firstName}}"   >
                <label for="suFirstNameID">First Name</label>
            </div>
            <span class="text-danger firstName_err"></span>     
        </div>
        <div class="mb-3">
            <div class="form-floating col-10">
                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" value="{{$firstName}}">
                <label for="lastName">Last Name</label>
            </div>
            <span class="text-danger lastName_err"></span> 
        </div>
        <div class="mb-3">
            <div class="form-floating col-10">
                <input name="DOB"  type="text" class="form-control" id="DOBID" placeholder="Date Of Birth" value="{{ $DOB}}" data-datepicker>
                <label for="DOBID">Date of Birth</label>
            </div>
            <span class="text-danger DOB_err"></span>   
        </div>
        <div class="mb-3">
            <div class="form-floating col-6">
                <select name="gender"  class="form-select" id="selectGenderID" aria-label="Select the Gender">
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
        <div class="mb-3">
            <div class="form-floating col-10">
                <input name="mobile" type="text" class="form-control" id="phoneID" placeholder="Mobile Number" value="{{$mobile}}"  >
                <label for="phoneID">Mobile Number (please inc +44)</label>
            </div>
            <span class="text-danger mobile_err"></span> 
        </div>
        <div class="mb-3">
            <div class="form-floating col-12">
                <input name="email"  type="text" class="form-control" id="emailID" placeholder="Email" value="{{ $email}}"  >
                <label for="emailID">Email</label>
            </div>
            <span class="text-danger email_err"></span>  
        </div>
        <div class="mb-3">
            <div class="form-floating col-6">
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
        <div class="mb-3">
            <div class="form-floating col-10">
                <input name="NiN"  type="text" class="form-control" id="NiNID" placeholder="NI Number" value="{{ $NiN}}">
                <label for="NiNID">NI Number</label>
            </div>
            <span class="text-danger NiN_err"></span>   
        </div>
        <div class="mb-3">
            <div class="form-floating col-10">
                <input name="NhsN"  type="text" class="form-control" id="NhsNID" placeholder="NHS Number" value="{{ $NhsN}}" >
                <label for="NhsNID">Nhs Number</label>
            </div>
            <span class="text-danger NhsN_err"></span>  
        </div>
        <div class="mb-3">
            <div class="form-floating col-8">
                <select name="religion"  class="form-select" id="selectReligionID" aria-label="Select the Religion">
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
        <div class="mb-3">
            <div class="form-floating col-12">
                <textarea name="address" class="form-control" id="addressID" placeholder="address">{{ $address }}</textarea>
                <label for="addressID">1st Line of Address</label>
            </div>
            <span class="text-danger address_err"></span>
        </div>
        <div class="mb-3">
            <div class="form-floating col-6">
                <input name="postCode"  type="text" class="form-control" id="postCodeID" placeholder="Post Code" value="{{ $postCode}}"  >
                <label for="postCodeID">Post Code</label>
            </div>
            <span class="text-danger postCode_err"></span> 
        </div>
        <div class="d-grid">
            <button type="button" class="btn btn-primary w-100" onClick="save_prospectFunc()">Submit</button>
        </div>
    </form>
</div>