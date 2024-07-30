"use strict"
let get_postCodeFunc=function(){}
let end_dailyMileageFunc=function(){}
let submit_postCodeFunc=function(){}
let mileageReportFunc=function(){}
let initMileageFunc=function(){}
let printModal=function(){}

let  show_spinner=function(isNotModal=1) {
    let spinnerElement = document.getElementById('spinner');
    if (isNotModal==0) spinnerElement = document.getElementById('modal_spinner');
    spinnerElement.style.display = 'block'; // Show the spinner
}
let hide_spinner=function(isNotModal=1) {
    let spinnerElement = document.getElementById('spinner');
    if (isNotModal==0) spinnerElement = document.getElementById('modal_spinner');
    spinnerElement.style.display = 'none'; // Hide the spinner
}
const apiKey = hereApiKey;

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
    // Get the modal and text elements
    const modal = new bootstrap.Modal(document.getElementById('myModal'));
    const modalText = document.getElementById('postCodeText');

    get_postCodeFunc=function(countVisit){
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(async (position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                                
                const reverseGeocodeUrl = `https://revgeocode.search.hereapi.com/v1/revgeocode?at=${lat},${lon}&lang=en-US&apiKey=${apiKey}`;
    
                try {
                    const response = await fetch(reverseGeocodeUrl);
                    const data = await response.json();
                    if (data.items.length === 0) {
                       // resultElement.textContent = `No postcode found for coordinates: ${lat}, ${lon}`;
                       printModal(0,countVisit,"");
                       return;
                    }
                    const address = data.items[0].address;
                    const fullPostcode = address.postalCode ? address.postalCode : 'Postcode not available';
                    //console.log(address)
                    
                    // resultElement.textContent =`Postcode: ${address.postalCode}`;
                     printModal(1,countVisit, fullPostcode)
                } catch (error) {
                    //resultElement.textContent = `Error: ${error.message}`;
                     printModal(0, countVisit, "")
                }
            }, (error) => {
                //resultElement.textContent = `Error getting location: ${error.message}`;
                 printModal(0, countVisit, "")   
            });
        } else {
            //resultElement.textContent = 'Geolocation is not supported by your browser.';
           //alert('Geolocation is not supported by your browser.');
           printModal(0, countVisit, "")
        }
    }

    function send_postCodeToController(postCode, isLast) {
        show_spinner(isLast)
        const post_data={
            postCode : postCode,
            isLast :isLast
        };
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
                    body: JSON.stringify(post_data)
                });
                
                               
                if (!response.ok) {
                    // Check for CSRF token mismatch error
                    const errorData = await response.json();
                    //alert(JSON.stringify(errorData));
                    hide_spinner(isLast)
                    if (errorData.message && errorData.message.includes('CSRF token mismatch')) {
                        warning("Session expired: Please login again to repeat") 
                        window.location.href = loginURL;
                        return;
                    }
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                //alert(JSON.stringify(data));

                if (isLast==1){
                    window.location.href = loginURL;
                }else{
                
                  
                    
                    modalText.value=""
                    const modal = document.querySelector('.modal');
                    const closeButton = modal.querySelector('.btn-close');
                    closeButton.click();
                    
                    let  countVisit=  data.countVisit
                    const countVisitLabel=document.getElementById('countVisitID')
                    countVisitLabel.textContent=countVisit
                    const firstLabel=document.getElementById('firstVisitID')
                    const firstLabelx=document.getElementById('firstVisitIDx')
                    const secondLabel=document.getElementById('secondVisitID')
                    const secondLabelx=document.getElementById('secondVisitIDx') 
                    if (countVisit==0){
                        secondLabel.style.display='none'
                        secondLabelx.style.display='none'
                        firstLabel.style.display='block'
                        firstLabelx.style.display='block'
                    }else {
                        secondLabel.style.display='block'
                        secondLabelx.style.display='block'
                        firstLabel.style.display='none'
                        firstLabelx.style.display='none'
                    }
                
                
                    hide_spinner(isLast)
                }    
            } catch(error) {
                // enter your logic for when there is an error (ex. error toast)
                //console.log(error)
                //alert(error);
                //in case it went bad jsut recall
                if (error.message && error.message.includes('CSRF token mismatch')) {
                    warning("Session expired: Please login and repeat") 
                    window.location.href = loginURL;
                }
                hide_spinner(isLast)
            } 
        }    
        asyncPostCall()
    }
    printModal=function(status, visitNo, postCode){
          //Both messages are hidden
        const visitTexts = document.querySelectorAll('p[id^="firstVisit"], p[id^="secondVisit"]');
        visitTexts.forEach(text => {
            text.style.display = 'none';
        });
        
        let firstVisitText = document.getElementById('firstVisitID');
        let secondVisitText = document.getElementById('secondVisitID');
        let title="Postcode found. Please confirm";
        if (status==0){
            title="Postcode not found"
            firstVisitText = document.getElementById('firstVisitIDx');
            secondVisitText = document.getElementById('secondVisitIDx');
        }    
        //change the title
        const modalTitle = document.getElementById('modalTitleID');
        modalTitle.textContent = title;

        if (visitNo==0) {
            firstVisitText.style.display = 'block';
        }else if (visitNo> 0){
            secondVisitText.style.display = 'block';
        }
        
        modalText.value=postCode
        // Show the modal
        modal.show();
        // Event listener to focus on the text field when the modal is shown
        document.getElementById('myModal').addEventListener('shown.bs.modal', function () {
            document.getElementById('postCodeText').focus();
        });
    }
    submit_postCodeFunc=function(){
        const modalText = document.getElementById('postCodeText');
        let postCode=modalText.value;
        const errorText = document.getElementById('err_postCodeID');
        errorText.textContent="" 
        
        function validateUKPostcode(postcode) {
            // Regular expression for UK postcodes
           const postcodeRegex = /^[A-Z]{1,2}[0-9][0-9A-Z]?\s?[0-9][A-Z]{2}$/i;
           return postcodeRegex.test(postcode);
        }

        
        if (postCode.length==0){
            errorText.textContent="Post cannot be empty"     
           return 0
        }else{
            const isValid = validateUKPostcode(postCode);
            if (isValid) {
               // console.log('Valid postcode');
               send_postCodeToController(postCode,0)
            } else {
                errorText.textContent="This is not a valid Postcode"     
                return 0
            }
        }
    }
    end_dailyMileageFunc=function(){
        Fnon.Ask.Warning({
            title:'Warning',
            message:'This will end the daily mileage capture. Do you wish to continue?',
            btnOkText: 'Yes',
            btnCancelText: 'No',
            callback:(result)=>{
                // callback
                if (result){
                    send_postCodeToController("",1)  
                }
            }
        });
        show_spinner(0)
    }
    
    initMileageFunc=function(){
        root.showLocationButtonsPage=true
        root. showMileageReportPage=false
    }

    async function fetchDistances(postcodePairs) {
        const post_data={
            pairs: postcodePairs
        };
        show_spinner()
       // const asyncPostCall = async () => {
            try {
                const response = await fetch(getDistanceURL, {
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
        
                if (!response.ok) {
                    if (!response.ok) {
                        // Check for CSRF token mismatch error
                        const errorData = await response.json();
                        if (errorData.message && errorData.message.includes('CSRF token mismatch')) {
                            window.location.href = loginURL;
                            warning("Session expired: Please login again to repeat") 
                            return;
                        }
                        throw new Error('Network response was not ok');
                    }
                }
                const data = await response.json();
               // alert(JSON.stringify(data));
                return data.distances
               
                ;
                //hide_spinner(0)
            } catch(error) {
                // enter your logic for when there is an error (ex. error toast)
                //console.log(error)
                //alert(error);
                //in case it went bad jsut recall
                
                if (error.message && error.message.includes('CSRF token mismatch')) {
                    warning("Session expired: Please login and repeat") 
                    window.location.href = loginURL;
                }
                
               // hide_spinner(0)
            } 
        }

    async function populateTable(postcodeArray)  {
        //shamelessly copied from mileage/myjs.js         
        function round_number(number) {
            // Handle non-numeric inputs gracefully
            if (isNaN(number)) {
              return number; // Return the original value if not a number
            }
            // Use toFixed() to control the number of decimal places
            const rounded = number.toFixed(2);
          
            // Check if the last two digits are zeros
            if (rounded.slice(-2) === '.00') {
              // Remove trailing zeros if both are zero
              return parseFloat(rounded.slice(0, -2));
            } else if (rounded.slice(-1) === '0') {
              // Remove trailing zero if only one is zero
              return parseFloat(rounded.slice(0, -1));
            }
            // Otherwise, return the rounded value with two decimal places
            return parseFloat(rounded);
        }
        show_spinner()        
        tableBody.innerHTML = ''; // Clear existing rows
      
        let postcodePairs = [];
        function createPairs(array) {
            let newArray = [];
            for (let i = 0; i < array.length - 1; i++) {
                newArray.push([array[i], array[i + 1]]);
            }
            return newArray;
        }
        postcodePairs=createPairs(postcodeArray)
        //for (let i = 0; i < postcodeArray.length - 1; i += 2) {
        //    postcodePairs.push([postcodeArray[i], postcodeArray[i + 1]]);
        //}
        //alert(JSON.stringify(postcodePairs));
        if (postcodePairs.length === 0) {
            const noDataRow = document.createElement('tr');
            const noDataCell = document.createElement('td');
            noDataCell.colSpan = 3; // Adjust the colspan based on the number of columns
            noDataCell.textContent = 'No Available Data';
            noDataCell.style.textAlign = 'center';
            // Add the style for red color
            noDataCell.style.color = 'red';
            noDataRow.appendChild(noDataCell);
            tableBody.appendChild(noDataRow);
            hide_spinner()
           return 0;
        }   


        const distances = await fetchDistances(postcodePairs);

        for (let i = 0; i < distances.length; i++) {
            const row = document.createElement('tr');

            const fromCell = document.createElement('td');
            fromCell.textContent = postcodePairs[i][0];
            const toCell = document.createElement('td');
            toCell.textContent = postcodePairs[i][1];
            const distanceCell = document.createElement('td');
            distanceCell.textContent = distances[i];

            if (distances[i] === '---') {
                fromCell.classList.add('text-danger');
                toCell.classList.add('text-danger');
                distanceCell.classList.add('text-danger');
            } else {
                fromCell.classList.add('text-success');
                toCell.classList.add('text-success');
                distanceCell.classList.add('text-success');
            }

            row.appendChild(fromCell);
            row.appendChild(toCell);
            row.appendChild(distanceCell);

            tableBody.appendChild(row); 
        }
        let total_distance=  distances.map(d => isNaN(d) ? 0 : Number(d)).reduce((acc, curr) => acc + curr, 0);
        total_distance=round_number(total_distance)
        let distanceVisit = document.getElementById('milesVisitID');
        distanceVisit.textContent=total_distance
        hide_spinner()
    }
   
    mileageReportFunc=function() {
        show_spinner()
        const post_data={
        };
        const asyncPostCall = async () => {
            show_spinner()
            try {
                const response = await fetch(getPostcodeURL, {
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
            
                
                hide_spinner()
                if (!response.ok) {
                    // Check for CSRF token mismatch error
                    const errorData = await response.json();
                    if (errorData.message && errorData.message.includes('CSRF token mismatch')) {
                        window.location.href = loginURL;
                        warning("Session expired: Please login again to repeat") 
                        return;
                    }
                    throw new Error('Network response was not ok');
                }
                
                //Everything OK
                const data = await response.json();
                let postCodeArray=data["postCodeArray"];
                //alert(JSON.stringify(postCodeArray));
                populateTable(postCodeArray);
               
                 //alert(JSON.stringify(distances));
                root.showLocationButtonsPage=false
                root.showMileageReportPage=true
                //hide_spinner()
                   
            } catch(error) {
                // enter your logic for when there is an error (ex. error toast)
                //console.log(error)
                //alert(error);
                //in case it went bad jsut recall
                if (error.message && error.message.includes('CSRF token mismatch')) {
                    warning("Session expired: Please login again to repeat") 
                    window.location.href = loginURL;
                }
                hide_spinner()
            } 
        }    
        asyncPostCall()
    }

})

