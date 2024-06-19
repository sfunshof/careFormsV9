let showSpinner=function(){}
let hideSpinner=function(){}
let submitComments=function(){}

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
    
  save_check_employee_spotcheckFunc= function() {
     //Begin the spinner
     showSpinner()
    
    //All the fields that need storing
    const formData = {
        'comments':  document.querySelector('textarea[name="comments"]').value,
        'keyID':keyID
    }
    const asyncPostCall = async () => {
        try {
            const response = await fetch(check_employee_spotcheckSaveURL, {
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
            //const data = await response.json();
            let data = await response.text(); //server returns text
            //document.documentElement.innerHTML = data;
            // Update the content of the target element
            document.getElementById('mainID').innerHTML =data;
           // hideSpinner()
            
        } catch(error) {
            // enter your logic for when there is an error (ex. error toast)
            console.log(error)
            //alert(error);
            hideSpinner()
        } 
    }    
    asyncPostCall()
}

    showSpinner=function() {
        document.getElementById('spinner').style.display = 'block';
    }

    hideSpinner=function() {
        document.getElementById('spinner').style.display = 'none';
    }
   
    submitComments=function(){
        //check the comments area for text
        const commentsElement = document.getElementById('commentsID');
        // Get the current text content
        const currentText = commentsElement.value;
        if (currentText.length < 2){
            //warnin
            Fnon.Hint.Danger('Error: Please enter some valid comments', {
                callback:function(){
                // callback
                }
            });
        }else{
          save_check_employee_spotcheckFunc()

        }

    }
})