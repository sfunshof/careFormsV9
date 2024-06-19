"use strict"
let save_prospectFunc=function(){}
let initProspectFunc=function(){}
let submitProspectFunc=function(){}
let randomDigits=-1 //this is used in serviceUserTable for the prospects

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

function show_spinner() {
    const spinnerElement = document.getElementById('spinner');
    spinnerElement.style.display = 'block'; // Show the spinner
    spinnerElement.style.zIndex=-9999;
}
function hide_spinner() {
    const spinnerElement = document.getElementById('spinner');
    spinnerElement.style.display = 'none'; // Hide the spinner
}

ready(function() {
    document.querySelectorAll('[data-datepicker]').forEach(function (element) {
        flatpickr(element, {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            allowInput: false
        });
    });

    //shamelessly copied from save_serviceUser.js
    save_prospectFunc= function(userID) {
        //Begin the spinner
        show_spinner()
        //Clear previous error
        const errClass =  document.querySelectorAll(".text-danger");
        errClass.forEach(errClass => {
           errClass.innerHTML="";
        });
        randomDigits=-1
        //All the fields that need storing
        const formData = {
            'title': document.querySelector('select[name="title"]').value,
            'firstName': document.querySelector('input[name="firstName"]').value,
            'lastName' : document.querySelector('input[name="lastName"]').value,
            'postCode':  document.querySelector('input[name="postCode"]').value,
            'mobile':   document.querySelector('input[name="mobile"]').value,
            'email':    document.querySelector('input[name="email"]').value,
            'proxy':    document.querySelector('select[name="proxy"]').value,
            'isProspect': 1,
            'userID' :-1,
            'NiN': document.querySelector('input[name="NiN"]').value,
            'NhsN': document.querySelector('input[name="NhsN"]').value,
            'DOB':document.querySelector('input[name="DOB"]').value,
            'religion': document.querySelector('select[name="religion"]').value,
            'gender':document.querySelector('select[name="gender"]').value,
            'address':document.querySelector('textarea[name="address"]').value,
        }
        const asyncPostCall = async () => {
            try {
                const response = await fetch(saveURL, {
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
                    Fnon.Hint.Primary('Success: Data has successfully been saved. <br> Now begin to enter the assessment records', {
                        position:'center-center',
                        callback:function(){
                           // callback
                           document.getElementById("addnew_formID").reset();
                           toggleShowNextIconFunc(1)
                           toggleShowPrevIconFunc(1)
                           toggleNextIconFunc(1)
                           togglePrevIconFunc(0)
                           root.showProspectEntryPage=false
                           go_to_first_quespage()
                           root.showProspectQuesPage=true
                           randomDigits=data["randomNo"]
                           
                         }
                      });
                    
                }else if(status==-1) {
                    Fnon.Hint.Danger('Failed: Problems saving data', {
                        callback:function(){
                       
                        }
                    });
                }else if(status==0){
                    let errors =data["error"];
                    for (let el in errors){
                        let classX="." + el  + "_err";
                        //alert (el + ' hhhh ' + errors[el])
                        document.querySelector(classX).textContent=errors[el]; 
                    }
                    Fnon.Hint.Danger('Error: Problems validating the data. Please check the fields', {
                        position: 'center-center',
                        callback:function(){
                       
                        }
                    });
                }
                hide_spinner()
                
            } catch(error) {
                    // enter your logic for when there is an error (ex. error toast)
                    console.log(error)
                    alert(error);
                    hide_spinner(1)
                    
            } 
        }    
        asyncPostCall()
    }
    
    let homeFunc=function(){
        const myForm = document.getElementById('addnew_formID');
        myForm.reset()
        document.getElementById("mobile_prospectQuesFormID").reset();
        root.showProspectEntryPage=true
        root.showProspectQuesPage=false
        root.showSuccessSavedPage=false
        togglePrevIconFunc(0)
        toggleNextIconFunc(0)
        toggleShowNextIconFunc(0)
        toggleShowPrevIconFunc(0)
        spotCheckResult=[]
    }
    initProspectFunc=function(){
        Fnon.Ask.Danger({
          title:'Start Up Page',
          message:'Going to the Start will clear all data. Do you wish to continue?',
          btnOkText: 'Yes',
          btnOkBackground: '#0d6efd',
          btnOkColor: '#fff',
          btnCancelText: 'No',
          btnCancelColor: '#fff',
          btnCancelBackground: '#808080',
          callback:(result)=>{
              // callback
              if (result) {
                 homeFunc()
              }
          }
       });
    }
    
    
    submitProspectFunc= function() {
        show_spinner()
        //do no try any nonesence
        togglePrevIconFunc(0)
        const post_data={
            prospectData: spotCheckResult,
            randomDigits: randomDigits,
            userID: -1
        };
        const asyncPostCall = async () => {
            try {
                const response = await fetch(submitURL, {
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
                // alert(JSON.stringify(data));
                document.getElementById("mobile_prospectQuesFormID").reset();
                root.showSuccessSavedPage=true
                setTimeout(() => {
                    homeFunc()
                    //console.log('x is now false');
                }, 4000);
                hide_spinner()
                
            } catch(error) {
                    // enter your logic for when there is an error (ex. error toast)
                    //console.log(error)
                    //alert(error);
                    //in case it went bad jsut recall
                    togglePrevIconFunc(1)
                    hide_spinner()
            } 
        }    
        asyncPostCall()
    }
    
    prevIconFunc=function(){
        previous()
    }

    nextIconFunc=function(){
        next()
    }
    
    previous_after_first_quespage=function(){
        // Check if div0 is visible
        togglePrevIconFunc(0)            
    }
})

