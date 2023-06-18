"use strict";
let  update_serviceUserFunc= function(){};
let  addnew_serviceUserFunc=function(){};

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
        show_spinner()
        
        //Clear previous error
        const errClass =  document.querySelectorAll(".text-danger");
        errClass.forEach(errClass => {
           errClass.innerHTML="";
        });
        
        //All the fields that need storing
        const formData = {
            'title': document.querySelector('select[name="title"]').value,
            'firstName': document.querySelector('input[name="firstName"]').value,
            'lastName' : document.querySelector('input[name="lastName"]').value,
            'postCode':  document.querySelector('input[name="postCode"]').value,
            'mobile':   document.querySelector('input[name="mobile"]').value,
            'proxy':    document.querySelector('select[name="proxy"]').value,
            'companyID': companyID,
            'userID' : userID
        }
        let URLpath=save_serviceUserURL;;
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
                    show_alertInfo("Data Successfully Saved")
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
    addnew_serviceUserFunc=function(){
        save_serviceUserFunc(-1)
    }
    update_serviceUserFunc=function(userID){
        save_serviceUserFunc(userID)
    }

})