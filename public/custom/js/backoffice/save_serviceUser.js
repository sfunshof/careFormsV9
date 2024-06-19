"use strict";
let  update_serviceUserFunc= function(){};
let  addnew_serviceUserFunc=function(){};
let  show_basicFormFunc=function(){}
let  show_assessFormFunc=function(){}
let  save_prospectQuesFunc=function(){}
let  randomDigits

function ready(callbackFunc) {
    if (document.readyState !== 'loading') {
        // Document is already ready, call the callback directly
        callbackFunc();
    } else if (document.addEventListener) {
        // All modern browsers to register DOMContentLoaded
        document.addEventListener('DOMContentLoaded', callbackFunc);
    } else {
        // Old IE browsers
      document.attachEvent('onreadystatechange', function() {
        if (document.readyState === 'complete') {
            callbackFunc();
         }
      })
    }
}
  
ready(function() {

    (function () {
           
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')
    
        // Loop over them and prevent submission
        
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                }
    
                form.classList.add('was-validated')
            }, false)
           })
           
    })()
    
    let save_serviceUserFunc= function(userID) {
        //Begin the spinner
        let spinnerStatus =0
        if (userID >=0)  spinnerStatus=1
        show_spinner(spinnerStatus)
        
        //Clear previous error
        const errClass =  document.querySelectorAll(".text-danger");
        errClass.forEach(errClass => {
           errClass.innerHTML="";
        });
        let isProspect = document.querySelector('input[name="isProspect"]').value;
        
        //All the fields that need storing
        const formData = {
            'title': document.querySelector('select[name="title"]').value,
            'firstName': document.querySelector('input[name="firstName"]').value,
            'lastName' : document.querySelector('input[name="lastName"]').value,
            'postCode':  document.querySelector('input[name="postCode"]').value,
            'mobile':   document.querySelector('input[name="mobile"]').value,
            'email':    document.querySelector('input[name="email"]').value,
            'proxy':    document.querySelector('select[name="proxy"]').value,
            'companyID': companyID,
            'userID' : userID,
            'isProspect': isProspect,
            'NiN': document.querySelector('input[name="NiN"]').value,
            'NhsN': document.querySelector('input[name="NhsN"]').value,
            'DOB':document.querySelector('input[name="DOB"]').value,
            'religion': document.querySelector('select[name="religion"]').value,
            'gender':document.querySelector('select[name="gender"]').value,
            'address':document.querySelector('textarea[name="address"]').value,
            'prospectRandomNo' : document.querySelector('input[name="prospectRandomNo"]').value
        }
        let URLpath=save_serviceUserURL;
        const asyncPostCall = async () => {
            
            try {
                const response = await fetch(URLpath, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        'Access-Control-Allow-Origin': '*',
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify(formData)
                });
                const data = await response.json();
                //alert(userID + '   pro ' + isProspect)
                //alert(JSON.stringify(data))

                let status=data["status"]
                randomDigits=data["randomNo"]
                //alert(randomDigits)
                if (status==1){
                    let msg="Success: Data info successfully added" 
                    function callBack(){
                        
                        if ((userID < 0) && (isProspect==0)){
                            msg="Success: Service User successfully added"
                            document.getElementById("addnew_formID").reset();
                        }   
                        if ((userID ==-1) && (isProspect==1)){
                            //save the random no unto a ytext field
                            //this will cater for updating it while still doing the questionnaires
                            const textField = document.querySelector('input[name="prospectRandomNo"]');
                            textField.setAttribute('value', randomDigits);

                            const radioElement = document.getElementById("assessInfoID");
                            radioElement.disabled=false
                            radioElement.checked=true
                            msg="Success: Assessment basic info successfully added"
                            show_assessFormFunc()
                        
                        }
                        if ((userID >=0) && (isProspect==0)){
                            msg="Success: Service User successfully updated"
                            document.getElementById('simulateLink').click();
                        }    
                    }
                    if ((isProspect== 0)|| (userID < 0)){ //for new assessment use thhis
                        show_alertInfo(msg, callBack())
                    }    
                    
                    if ((userID >=0) && (isProspect==1)){
                        save_prospectQuesFunc(userID)
                    }
                    hide_spinner(spinnerStatus)
                }else if(status==-1) {
                    show_alertDanger("Database problem please try again later")  
                }else if(status==0){
                    let errors =data["error"];
                    for (let el in errors){
                        let classX="." + el  + "_err";
                        //alert (el + ' hhhh ' + errors[el])
                        document.querySelector(classX).textContent=errors[el]; 
                    }
                    hide_spinner(spinnerStatus)
                }
               
            } catch(error) {
                    // enter your logic for when there is an error (ex. error toast)
                    console.log(error)
                    alert(error);
                    hide_spinner(spinnerStatus)
                    
            } 
        }    
        asyncPostCall()
    }
    
    save_prospectQuesFunc= function(userID) {
        //Begin the spinner
        let spinnerStatus =0
        if (userID >=0)  spinnerStatus=1
         show_spinner(spinnerStatus)
        let errorStatus=0
        let count= document.querySelector('input[name="count"]').value;
               
        check_assessment(count)
                
        for (let i=0 ; i < count; i++) {
            let id=i+1
            if (typeof spotCheckResult[i] === 'undefined') {
                errorStatus=1
                document.getElementById("err_"+ id).style.display="block"
            } else{
                document.getElementById("err_"+ id).style.display="none"
            }    
        } 
        if (errorStatus==1){
            hide_spinner(spinnerStatus)
            show_alertDanger("Error: Plese review and answer all the questionnaires")  
            return 0   
        } 
        const post_data={
            prospectData: spotCheckResult,
            randomDigits: randomDigits, //not needed for update we use userID instead
            userID: userID, // -ve =insert +ve update
        };
                
        const asyncPostCall = async () => {
            try {
                const response = await fetch(submit_prospectQuesURL, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        'Access-Control-Allow-Origin': '*',
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify(post_data)
                });
                const data = await response.json();
                //alert(JSON.stringify(data))
                let status=data["status"]
                 let msg="Success: Assessment  added"
                if (status==1){
                    let  callBack = function(){
                        if (userID < 0) {
                            document.getElementById("addnew_formID").reset();
                            document.getElementById("prospect_formID").reset();
                            const radioElement = document.getElementById("assessInfoID");
                            radioElement.disabled=true
                            msg="Success: Assessment info successfully added"
                            show_basicFormFunc()
                        }
                    }
                    
                    if (userID >=0){
                        msg="Success: Assessment info successfully updated"
                        callBack= function(){
                            document.getElementById('simulateLink_isProspect').click();
                        }       
                    }
                    show_alertInfo(msg, callBack())
                }else if(status==-1) {
                    show_alertDanger("Database problem please try again later")  
                }else if(status==0){
                    let errors =data["error"];
                    for (let el in errors){
                        let classX="." + el  + "_err";
                        //alert (el + ' hhhh ' + errors[el])
                        document.querySelector(classX).textContent=errors[el]; 
                    }
                }
                hide_spinner(spinnerStatus)
                
            } catch(error) {
                // enter your logic for when there is an error (ex. error toast)
                console.log(error)
                alert(error);
                hide_spinner(spinnerStatus)
                    
            } 
        }    
        asyncPostCall()
    }

    let check_assessment=function(count){
        // Function to check the existence of an element by ID
        function elementExists(id) {
            return document.getElementById(id) !== null;
        }

        // Function to check if an element is displayed (not hidden)
        function isElementDisplayed(id) {
            const element = document.getElementById(id);
            if (element) {
                const style = window.getComputedStyle(element);
                return style.display !== 'none';
            }
            return false;
        }
        function elementExistsByName(name) {
            return document.getElementsByName(name).length > 0;
        }
        
        function getCheckedValue(name) {
            const radios = document.getElementsByName(name);
            for (let i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    return radios[i].value;
                }
            }
            return null; // Return null if no radio button is checked
        }
        
        function getTextAreaValue(id) {
            const textArea = document.getElementById(id);
            if (textArea) {
                return textArea.value;
            }
            return null; // Return null if the text area does not exist
        }

        for (let i=0; i < count; i++){
            let value=""
            if (isElementDisplayed('box' + i)){
                value= "Others:,Others:" + getTextAreaValue('other' +i)
            }else if (elementExistsByName('radio' +i )){
                value= getCheckedValue('radio' + i)
            }else if( elementExists('text' + i)){
                value= getTextAreaValue('text' +i)
            }
            spotCheckResult[i]=[value] 
        }
   }
    
    addnew_serviceUserFunc=function(){
        save_serviceUserFunc(-1)
    }
    update_serviceUserFunc=function(userID){
        save_serviceUserFunc(userID)
    }
   
    const formOne = document.getElementById('addnew_formID');
    const formTwo = document.getElementById('prospect_formID');
    // Function to slide in form one from the left and show it
    function showFormOne() {
        formOne.style.transition = 'transform 0.3s ease-in-out';
        formOne.style.transform = 'translateX(0)';
        formOne.style.display='block';
    }
    
    // Function to slide out form two to the right and hide it
    function hideFormTwo() {
        formTwo.style.transition = 'transform 0.3s ease-in-out';
        formTwo.style.transform = 'translateX(100%)';
        formTwo.style.display='none';
    }
    
    // Function to slide in form two from the right and show it
    function showFormTwo() {
        formTwo.style.transition = 'transform 0.3s ease-in-out';
        formTwo.style.transform = 'translateX(0)';
        formTwo.style.display='block';
    }
   
    // Function to slide out form one to the left and hide it
    function hideFormOne() {
        formOne.style.transition = 'transform 0.3s ease-in-out';
        formOne.style.transform = 'translateX(-100%)';
        formOne.style.display='none';
    }
    show_basicFormFunc=function(){
        showFormOne()
        hideFormTwo()
        const radioElement = document.getElementById("basicInfoID");
        radioElement.checked=true
    }
    show_assessFormFunc=function(){
        showFormTwo()
        hideFormOne()
        const radioElement = document.getElementById("assessInfoID");
        radioElement.checked=true
    }

    //Prospects ***
    document.querySelectorAll('[data-datepicker]').forEach(function (element) {
        flatpickr(element, {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            allowInput: false
        });
    });

})