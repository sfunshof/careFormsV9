"use strict";
let  update_employeeFunc= function(){};
let  addnew_employeeFunc=function(){};
let  show_COSFunc=function(){};

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
    
    let save_employeeFunc= function(userID) {
        //Begin the spinner
        let spinner_status=0
        if (userID >=0) spinner_status=1 
        show_spinner(spinner_status)
        
        //Clear previous error
        const errClass =  document.querySelectorAll(".text-danger");
        errClass.forEach(errClass => {
           errClass.innerHTML="";
        });
        
        //All the fields that need storing
        const formData = {
            'firstName': document.querySelector('input[name="firstName"]').value,
            'middletName': document.querySelector('input[name="middleName"]').value,
            'lastName' : document.querySelector('input[name="lastName"]').value,
            'email':  document.querySelector('input[name="email"]').value,
            'mobile':   document.querySelector('input[name="mobile"]').value,
            'job':    document.querySelector('select[name="jobFunction"]').value,
            'officePostcode':  document.querySelector('input[name="officePostcode"]').value,
            'companyID': companyID,
            'userID' : userID,
            'isCOS': document.getElementById('COSswitchID').checked  ? 1 : 0,
            'appDate': document.querySelector('input[name="appDate"]').value,
            'interDate': document.querySelector('input[name="interDate"]').value,
            'COSdate': document.querySelector('input[name="COSdate"]').value,
            'arrDate': document.querySelector('input[name="arrDate"]').value,
            'DBSdate': document.querySelector('input[name="DBSdate"]').value,
            'startDate': document.querySelector('input[name="startDate"]').value,

        }
  
        let URLpath=save_employeeURL;;
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
                //alert(JSON.stringify(data))                               
                let status=data["status"]
                if (status==1){
                    show_alertInfo("Records Successfully Saved")
                    if (userID < 0) document.getElementById("addnew_formID").reset();
                    if (userID >=0)document.getElementById('simulateLink').click();
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
                hide_spinner(spinner_status)
                
            } catch(error) {
                    // enter your logic for when there is an error (ex. error toast)
                    console.log(error)
                    alert(error);
                    hide_spinner(spinner_status)
                    
            } 
        }    
        asyncPostCall()
    }
    addnew_employeeFunc=function(){
        save_employeeFunc(-1)
    }
    update_employeeFunc=function(userID){
        save_employeeFunc(userID)
    }
    show_COSFunc=function(){
        document.querySelectorAll('[data-datepicker]').forEach(function (element) {
            flatpickr(element, {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "F j, Y",
                allowInput: false
            });
        });

        const isChecked = document.getElementById("COSswitchID").checked;
        var slideShowElement = document.querySelector('.COSshowID');
        if (isChecked) {
            // Slide up and make visible
            slideShowElement.style.display = 'block';
            setTimeout(function () {
                slideShowElement.classList.add('visible');
            }, 10); // Adding a slight delay to trigger the transition
        } else {
            // Slide down and hide
            slideShowElement.classList.remove('visible');
            slideShowElement.addEventListener('transitionend', function handler() {
                slideShowElement.style.display = 'none';
                slideShowElement.removeEventListener('transitionend', handler);
            });
        }
    }

})