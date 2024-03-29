"use strict";
let update_spotCheckDashboardSelectFunc=function(){}
let viewSpotCheckFunc=function(){}
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
    viewSpotCheckFunc=function(keyID){
       // alert(view_employee_spotcheckURL)
        show_spinner()
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
                let btns=pdfBtn + closeBtn
                show_modal(title,data,btns) 
        

            } catch(error) {
                // enter your logic for when there is an error (ex. error toast)
                 alert(error);
                hide_spinner()
            } 
        }    
        asyncPostViewCall()
    }
})