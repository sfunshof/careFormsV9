"use strict";
let logoutFunc=function(){}
let pageLoader=function(){}

let  showSpinner=function() {
    const spinnerElement = document.getElementById('spinner');
    spinnerElement.style.display = 'block'; // Show the spinner
}
let hideSpinner=function() {
    const spinnerElement = document.getElementById('spinner');
    spinnerElement.style.display = 'none'; // Hide the spinner
}

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
      });
    }
}

ready(function() {

    logoutFunc=function(){
        Fnon.Ask.Primary({
            title:'Logout',
            message:'Do you wish to logout',
            btnOkText: 'Yes',
            btnOkBackground: '#0d6efd',
            btnOkColor: '#fff',
            btnCancelText: 'No',
            btnCancelColor: '#fff',
            btnCancelBackground: '#808080',
            callback:(result)=>{
                // callback
                if (result) {
                  showSpinner()  
                  store.clear()
                  // Redirect to the spotcheck/mobile route
                  //window.location.href = loginURL;
                  pageLoader(loginURL)  
                }
            }
         });
    }

})


