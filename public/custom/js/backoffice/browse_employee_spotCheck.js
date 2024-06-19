"use strict";
let update_spotCheckDashboardSelectFunc=function(){}
let viewSpotCheckFunc=function(){}
let editSpotCheckFunc=function(){}
let saveSpotCheckFunc=function(){}
let showOtherTextFunc=function(){}
let setRatingFunc=function(){}
let setInitRatingFunc=function(){}
let key_variable=-1 //anytime a view, or edit is clicked we save the keyID b/c of email

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
                const response = await fetch(browse_employee_spotcheckURL, {
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
                document.getElementById('browse_employeeContentID').innerHTML =data;
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

    let print_to_pdfFunc=function(){
        show_spinner()
        const asyncPdfCall = async () => {
            try {
                const response = await fetch(pdf_employee_spotcheckURL, {
                    method: 'GET',
                    headers: {
                        "Content-Type": "application/pdf",
                        "Content-Disposition": "attachment;",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token
                    },
                });
                hide_spinner()
                if (response.ok) {
                    
                    // Handle the PDF download or display logic here
                    const blob = await response.blob(); // Convert the response to a Blob

                    // Create a temporary anchor element to trigger the download
                    const a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                     a.download = 'spotcheck.pdf'; // Specify the desired filename
                    a.click();
                    
                    console.log('PDF generated successfully!');
                } else {
                    console.error('Error generating PDF');
                }
            } catch (error) {
                hide_spinner()
                console.error('An error occurred:', error);
            }
        };
        asyncPdfCall()
    }
    //redfined for v2
    printToPdf_modalFunc=function(){
        print_to_pdfFunc()
        //let modalElement = document.getElementById("myModal");
    //    printModalContentsToPDF(modalElement);

    }
    email_modalFunc=function(){
        // alert(view_employee_spotcheckURL)
        show_spinner(1)
        const asyncPostEmailCall = async () => {
            let post_data={
                keyID:key_variable
            }
            try {
                const response = await fetch(email_employee_spotcheckURL, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify(post_data)
                });
                let data = await response.json();
                alert(JSON.stringify(data))
                if (data['status'] > 0){
                    show_alertInfo("Success: Spot check sent to carer for review")
                }else {
                    show_alertDanger("Failed: Could not sent an email. Please try again later")
                }
                hide_spinner(1)
            } catch(error) {
                // enter your logic for when there is an error (ex. error toast)
                 alert(error);
                hide_spinner()
            } 
        }    
        asyncPostEmailCall()
    }

    viewSpotCheckFunc=function(keyID, isAccept){
       // alert(view_employee_spotcheckURL)
        show_spinner()
        key_variable=keyID
        const asyncPostViewCall = async () => {
            let post_data={
                keyID:keyID
            }
            try {
                const response = await fetch(view_employee_spotcheckURL, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify(post_data)
                });
                //let data = await response.json();
                let data = await response.text(); //server returns text
                //extract the data from script

                //** We run everything inside the script this is so because we have 
                // issues getting title. The message is ok  */
                // Create a temporary HTML element to parse the script content
                //******** Summary We could not extract the Title **********//
                //alert(data) //==>helps to debug error
                // Create a temporary div element to parse the HTML string
                let tempDiv = document.createElement('div');
                tempDiv.innerHTML = data;
                // Find the script element within the parsed HTML
                let scriptElement = tempDiv.querySelector('script');
                // Extract the script contents
                let scriptContents = scriptElement.textContent;
                // Use regular expressions to extract the values of x and y
                //Title must be a single line
                let title = scriptContents.match(/let\s+myTitle\s*=\s*(.*?);/)[1];
                //** End of extract for title   ***/
                hide_spinner()
                let btns=emailBtn + pdfBtn + closeBtn
                if (isAccept==1){
                    btns=emailBtnDisabled + pdfBtn + closeBtn
                }
                show_modal(title,data,btns) 
               

            } catch(error) {
                // enter your logic for when there is an error (ex. error toast)
                 alert(error);
                hide_spinner()
            } 
        }    
        asyncPostViewCall()
    }
    editSpotCheckFunc=function(keyID){
        // alert(view_employee_spotcheckURL)
        key_variable=keyID
        show_spinner()
        const asyncPostViewCall = async () => {
             let post_data={
                 keyID:keyID
             }
             try {
                 const response = await fetch(edit_employee_spotcheckURL, {
                     method: 'POST',
                     headers: {
                         "Content-Type": "application/json",
                         "Accept": "application/json, text-plain, */*",
                         "X-Requested-With": "XMLHttpRequest",
                         "X-CSRF-TOKEN": token
                     },
                     body: JSON.stringify(post_data)
                 });
                 //let data = await response.json();
                 let data = await response.text(); //server returns text
                 //extract the data from script
 
                 //** We run everything inside the script this is so because we have 
                 // issues getting title. The message is ok  */
                 // Create a temporary HTML element to parse the script content
                 //******** Summary We could not extract the Title **********//
                 //alert(data) //==>helps to debug error
                 // Create a temporary div element to parse the HTML string
                 let tempDiv = document.createElement('div');
                 tempDiv.innerHTML = data;
                 // Find the script element within the parsed HTML
                 let scriptElement = tempDiv.querySelector('script');
                 // Extract the script contents
                 let scriptContents = scriptElement.textContent;
                 // Use regular expressions to extract the values of x and y
                 //Title must be a single line
                 let title = scriptContents.match(/let\s+myTitle\s*=\s*(.*?);/)[1];
                 //** End of extract for title   ***/
                 hide_spinner()
                 //let btns=pdfBtn + closeBtn
                 let btns= emailBtn + spotCheckSaveBtn + closeBtn
                 show_modal(title,data,btns) 
                 setInitRatingFunc(keyID)
 
             } catch(error) {
                 // enter your logic for when there is an error (ex. error toast)
                  alert(error);
                 hide_spinner()
             } 
         }    
         asyncPostViewCall()
    }
    spotCheckSave_modalFunc=function(){
        const inputKey =  document.getElementById('inputKeyID');
        let keyID= inputKey.value
        show_spinner(1)

        let array_resp=[]
        let countID = document.getElementById("countID")
        if (countID){
            let count=countID.value
           for (let i=0;i <count; i++){
                array_resp[i]=[]
                //radio buttons
                let radioButtons = document.getElementsByName("radio" + i);
                if (radioButtons){
                    // Find the checked radio button using a loop
                    for (const radioButton of radioButtons) {
                        if (radioButton.checked) {
                            radioButton.value; // Return the value of the checked button
                            if (radioButton.value=="Others"){
                                let textOthers = document.getElementById("text" + i)
                                array_resp[i]= ["Others:,Others " + textOthers.value]
                            }else{
                                array_resp[i]=[radioButton.value]
                            }
                        }
                    }
                }
                //textArea
                let textAreaId=document.getElementById("textArea" + i)
                if (textAreaId){
                    array_resp[i]=[textAreaId.value]
                }

            }
        }
        //This is the rating
        const hiddenInput = document.getElementById('selected-rating');
        // Set the initial rating from the hidden input
        const rating = parseInt(hiddenInput.value);
        //alert(rating)
        const asyncPostCall = async () => {
            let post_data={
                responses:array_resp,
                rating:rating,
                keyID:keyID
            }
            //alert(JSON.stringify(save_employee_spotcheckURL))
            //return 0
            try {
                const response = await fetch(save_employee_spotcheckURL, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify(post_data)
                });
                const data = await response.json();
                //let data = await response.text(); //server returns text
                //document.documentElement.innerHTML = data;
                // Update the content of the target element
                //document.getElementById('browse_employeeContentID').innerHTML =data;
                // enter you logic when the fetch is successful
                //alert(JSON.stringify(data));
                show_alertInfo("Spot Check  Successfully updated", 1)
                hide_spinner(1)
                                          
            } catch(error) {
                // enter your logic for when there is an error (ex. error toast)
                alert(error);
                hide_spinner()
            } 
        }    
        asyncPostCall()
    }

     
    setRatingFunc=function(rating) {
        const stars = document.querySelectorAll('.star-rating .star');
        const hiddenInput = document.getElementById('selected-rating');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('gold');
            } else {
                star.classList.remove('gold');
            }
        });
        hiddenInput.value = rating;
    }
    
    setInitRatingFunc=function(keyID){
        const inputKey =  document.getElementById('inputKeyID');
        inputKey.value=keyID

        const hiddenInput = document.getElementById('selected-rating');
        // Set the initial rating from the hidden input
        const initialRating = parseInt(hiddenInput.value);
        if (initialRating) {
            setRatingFunc(initialRating);
        }
    }


   
    showOtherTextFunc=function(name, index){
        let textInput = document.getElementById('text' + index)
        if (name.toLowerCase().includes("others")) {
           textInput.style.display = 'block';
        } else {
            textInput.style.display = 'none';
        }      
    }    
  

})