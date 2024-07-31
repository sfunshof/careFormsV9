"use strict";
let client_mileage_rptFunc=function(){}
let insertRowFunc=function(){}
let deleteRowFunc=function(){}
let validatePostcodesFunc=function(){}
let update_daily_postcodes=function(){}
let set_daily_postcodeFunc=function(){}
let reload_client_mileageFunc=function(){}

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
    document.querySelectorAll('[data-datepicker]').forEach(function (element) {
        flatpickr(element, {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            allowInput: false,
            defaultDate: element.value 
        });
    });
    
     //42 days back as default
    const today = new Date();
    const sixWeeksAgo = new Date();
    sixWeeksAgo.setDate(today.getDate() - 42);
    
    function extractDate(currentDate) {
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        const day = String(currentDate.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;
        return formattedDate // Output: YYYY-MM-DD format
    }
    
    let startDateValue=extractDate(sixWeeksAgo)
    let endDateValue=extractDate(today)
    
    const endDate = flatpickr("#endDateID", {
        //defaultDate: today,
        maxDate: today,
        onChange: function (selectedDates, dateStr, instance) {
            startDate.set('maxDate', dateStr);
            endDateValue = dateStr;
        }
    });

    const startDate = flatpickr("#startDateID", {
        //defaultDate: sixWeeksAgo,
        maxDate: today,
        onChange: function (selectedDates, dateStr, instance) {
            endDate.set('minDate', dateStr);
            startDateValue = dateStr;
        }
    });
    

    client_mileage_rptFunc=function() {
        show_spinner()
        const post_data={
            
        };
        const asyncPostCall = async () => {
            try {
                const response = await fetch(sumRptURL, {
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
                //alert(JSON.stringify(data));
                document.getElementById('client_mileage_sum').innerHTML = data.html;
                      
                hide_spinner()
            } catch(error) {
                // enter your logic for when there is an error (ex. error toast)
                //console.log(error)
                //alert(error);
                //in case it went bad jsut recall
                 hide_spinner()
            } 
        }    
        asyncPostCall()
    }
    

    insertRowFunc = function(element) {
        const currentRow = element.closest('.row-container');
        const newRow = currentRow.cloneNode(true);
        const inputField = newRow.querySelector('.form-control');
        const errorSpan = newRow.querySelector('.error_class');
        const iconContainer = newRow.querySelector('.icon-container');
    
        // Clear any existing error message
        errorSpan.textContent = "";
    
        // Set value to an empty string
        inputField.value = "";
        inputField.placeholder = "Client's Postcode";
    
        // Ensure both icons are present and visible
        if (!iconContainer.querySelector('.fa-plus')) {
            const plusIcon = document.createElement('i');
            plusIcon.className = 'fas fa-plus text-success cursor_pointer';
            plusIcon.onclick = function() { insertRowFunc(this); };
            iconContainer.appendChild(plusIcon);
        }
        if (!iconContainer.querySelector('.fa-times')) {
            const minusIcon = document.createElement('i');
            minusIcon.className = 'fas fa-times text-danger delete-icon cursor_pointer';
            minusIcon.onclick = function() { deleteRowFunc(this); };
            iconContainer.appendChild(minusIcon);
        }
    
        const rowContainer = document.getElementById('rowContainer');
        
        // Insert the new row below the current row
        currentRow.parentNode.insertBefore(newRow, currentRow.nextSibling);
    
        // Update the last row to remove icons if necessary
        updateLastRow();
    }
    
    deleteRowFunc = function(element) {
        const rowContainer = document.getElementById('rowContainer');
        const rowToDelete = element.closest('.row-container');
        const allRows = rowContainer.querySelectorAll('.row-container');
    
        if (allRows.length > 1) {
            rowToDelete.remove();
            updateLastRow();
        } else {
            alert("At least one row must remain.");
        }
    }
    
    function updateLastRow() {
        const rowContainer = document.getElementById('rowContainer');
        const allRows = rowContainer.querySelectorAll('.row-container');
        const lastRow = allRows[allRows.length - 1];
        const lastRowInput = lastRow.querySelector('.form-control');
        const lastRowIconContainer = lastRow.querySelector('.icon-container');
    
        if (allRows.length === 1) {
            // If there's only one row, ensure it only has the plus icon
            lastRowIconContainer.innerHTML = '<i class="fas fa-plus text-success" onclick="insertRowFunc(this)"></i>';
            lastRowInput.placeholder = "Office Postcode";
        } else if (window.isLastZero === false) {
            // If is_last is 1 (false), remove icons from the last row
            lastRowIconContainer.innerHTML = '';
            lastRowInput.placeholder = "Office Postcode";
        } else {
            // Ensure the last row has both icons
            lastRowIconContainer.innerHTML = `
                <i class="fas fa-plus text-success" onclick="insertRowFunc(this)"></i>
                <i class="fas fa-times text-danger delete-icon" onclick="deleteRowFunc(this)"></i>
            `;
            lastRowInput.placeholder = "Client's Postcode";
        }
    }

    
    let  check_postcodeValidity =function(){}

    //3. This is the third and final routine for sending to the server       
    update_daily_postcodes=function(postCodes) {
        const dailyDate = document.getElementById("dailyDateID"); 
        show_spinner()
        const post_data={
            postCodes : postCodes,
            dailyDate: dailyDate.value,
            startDate:startDateValue,
            endDate:endDateValue
        };
        const asyncPostCall = async () => {
            try {
                const response = await fetch(update_dailyPostcodesURL, {
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
                //alert(JSON.stringify(data));
                //replace <id="client_mileage_sum" with data.html
                var myDiv = document.getElementById('client_mileage_sum');
                // Replace the content of the div
                myDiv.innerHTML = data.html;
                hide_spinner()
                show_alertInfo("Postcodes Successfully Saved")
            } catch(error) {
                hide_spinner()
            } 
        }    
        asyncPostCall()
    }
          
    //1. This is the first call before  sending to the server 
    validatePostcodesFunc = function() {
        const postcodeRegex = /^[A-Z]{1,2}\d[A-Z\d]? ?\d[A-Z]{2}$/i;
        const postcodes = document.querySelectorAll('input[name="PostCode[]"]');
        const numPostcodes = postcodes.length;
    
        if (numPostcodes === 0) {
            return 0; // No postcodes, do nothing
        } else if (numPostcodes === 1) {
            const errorSpan = postcodes[0].nextElementSibling;
            errorSpan.textContent = "At least 2 postcodes";
            errorSpan.style.color = "red";
            isValid = false;
        } else {
            let isValid = true;
            postcodes.forEach((input) => {
                const errorSpan = input.nextElementSibling;
                if (!postcodeRegex.test(input.value)) {
                    errorSpan.textContent = "Invalid UK postcode";
                    errorSpan.style.color = "red";
                    isValid = false;
                } else {
                    errorSpan.textContent = "";
                }
            });
    
            if (isValid) {
                let postCodeArray = [];
                postcodes.forEach(input => {
                    if (input.value.trim() !== "") {
                        postCodeArray.push(input.value.trim());
                    }
                });
                let postCodeUniqueArray = [...new Set(postCodeArray)]; // Ensure uniqueness
                check_postcodeValidity(postCodeUniqueArray, postCodeArray);
            }
        }
    }
   //2. This is the second call before  sending to the server
    check_postcodeValidity=function(postCodeUniqueArray, postCodeArray) {
        
        function display_error_message(invalidPostcodes) {
            const postcodes = document.querySelectorAll('input[name="PostCode[]"]');
            postcodes.forEach((input) => {
                const errorSpan = input.nextElementSibling;
                if (invalidPostcodes.includes(input.value)) {
                    errorSpan.textContent = "Postcode not valid";
                    errorSpan.style.color = "red";
                } else {
                    errorSpan.textContent = "";
                }
            });
        }
        
        
        show_spinner()
        const post_data={
            postCodes : postCodeUniqueArray
        };
        const asyncPostCall = async () => {
            try {
                const response = await fetch(check_postcodeValidityURL, {
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
                if (data.invalid_postcodes.length > 0){
                     display_error_message(data.invalid_postcodes);
                     hide_spinner();
                }else{
                    update_daily_postcodes(postCodeArray)
                }
               
            } catch(error) {
                hide_spinner()
            } 
        }    
        asyncPostCall()
    }
    
    //displays daily postcodes
    set_daily_postcodeFunc=function(date) {
        show_spinner()
        const post_data={
            date : date
        };
        const asyncPostCall = async () => {
            try {
                const response = await fetch(set_dailyPostcodesURL, {
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
                //alert(JSON.stringify(data));
                var myDiv = document.getElementById('client_mileage_daily');
                // Replace the content of the div
                myDiv.innerHTML = data.html;


               hide_spinner()               
            } catch(error) {
                hide_spinner()
            } 
        }    
        asyncPostCall()
    }
    
    reload_client_mileageFunc=function(){
        show_spinner() 
        const post_data={
            endDate : endDateValue,
            startDate: startDateValue
        };
        const asyncPostCall = async () => {
            try {
                const response = await fetch(reload_client_mileageURL, {
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
                //alert(JSON.stringify(data));
                var myDiv_daily = document.getElementById('client_mileage_daily');
                var myDiv_sum = document.getElementById('client_mileage_sum');
                
                
                // Replace the content of the div
                myDiv_daily.innerHTML = data.html_daily;
                myDiv_sum.innerHTML=data.html_sum;

               hide_spinner()               
            } catch(error) {
                hide_spinner()
            } 
        }    
        asyncPostCall()
    }


})    