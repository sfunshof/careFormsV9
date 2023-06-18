"use strict";
let  update_employeeFunc= function(){};
let  addnew_employeeFunc=function(){};

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
        show_spinner()
        
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
            'companyID': companyID,
            'userID' : userID
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
                                                
                let status=data["status"]
                if (status==1){
                    show_alertInfo("Records Successfully Saved")
                    if (userID < 0) document.getElementById("addnew_formID").reset();
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
                hide_spinner()
                
            } catch(error) {
                    // enter your logic for when there is an error (ex. error toast)
                    console.log(error)
                    alert(error);
                    hide_spinner()
                    
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

})