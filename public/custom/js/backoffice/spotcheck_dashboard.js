"use strict";
let update_spotCheckDashboardSelectFunc=function(){}
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
    update_spotCheckDashboardSelectFunc=function(){
        let selectElement = document.getElementById("spotCheckSelectID");
        let selectedOption = selectElement.options[selectElement.selectedIndex].value;
        show_spinner()
        const asyncPostCall = async () => {
            let post_data={
                selectedMnth:selectedOption
            }
            try {
                const response = await fetch(update_spotcheckDashboardDataURL, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify(post_data)
                });
                //const data = await response.json();
                let data = await response.text(); //server returns text
                //document.documentElement.innerHTML = data;
                // Update the content of the target element
                document.getElementById('dashboardContentID').innerHTML =data;
                // enter you logic when the fetch is successful
                //alert(JSON.stringify(data));
                //show_alertInfo("Dashboard  Successfully updated")
                hide_spinner()
                                          
            } catch(error) {
                // enter your logic for when there is an error (ex. error toast)
                alert(error);
                hide_spinner()
            } 
        }    
        asyncPostCall()
    }


})