"use strict";
let  update_companyProfileFunc= function(){};

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
    
    update_companyProfileFunc= function() {
        //Begin the spinner
        show_spinner()
        
        //Clear previous error
        const errClass =  document.querySelectorAll(".text-danger");
        errClass.forEach(errClass => {
           errClass.innerHTML="";
        });
        
        //All the fields that need storing
        const formData = {
            'companyName': document.querySelector('input[name="companyName"]').value,
            'contactEmail': document.querySelector('input[name="contactEmail"]').value,
            'smsName' : document.querySelector('input[name="smsName"]').value,
            'smsPreTextEmp':  document.querySelector('textarea[name="smsPreTextEmp"]').value,
            'smsPreTextSu':  document.querySelector('textarea[name="smsPreTextSu"]').value,
        }
        let URLpath=update_companyProfileURL;;
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
   

})