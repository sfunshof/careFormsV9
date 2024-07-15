"use strict";
let rowCount = 0;

let addRowFunc= function(){}
let removeRowFunc=function(){} 
let validateFunc=function(){}
let assignFieldsFunc=function(){}
let importExcelFunc=function() {}
let resetFunc=function(){}
let calculateDistanceFunc=function() {}
let createTableRowFunc=function(){}
let clearTableFunc=function(){}
let printInvalidate=function(){}
let showLess=function(){}
let showMore=function(){}

const tableBody = document.querySelector('#dynamicTable tbody');
let postCodeArray = [];
let dateArray = [];


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
        addRowFunc=function(){
            function getFormattedDate() {
                const date = new Date();
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Add leading zero for single-digit months
                const day = String(date.getDate()).padStart(2, '0'); // Add leading zero for single-digit days
              
                return `${year}-${month}-${day}`;
            }
            rowCount++;
            const rowId = `row-${rowCount}`;
    
            const rowDiv = document.createElement('div');
            rowDiv.className = 'text-field-container mb-0';
            rowDiv.id = rowId;
    
            const textFieldRow = document.createElement('div');
            textFieldRow.className = 'row';
    
            const firstTextInputCol = document.createElement('div');
            firstTextInputCol.className = 'col-4';
    
            const firstTextInput = document.createElement('input');
            firstTextInput.type = 'text';
            firstTextInput.className = 'form-control shadow-none form-control-sm';
            firstTextInput.placeholder = rowCount === 1 ? 'Office Postcode' : 'Client Postcode';
    
            const firstTextError = document.createElement('div');
            firstTextError.className = 'error-message';
    
            firstTextInputCol.appendChild(firstTextInput);
            firstTextInputCol.appendChild(firstTextError);
    
            const secondTextInputCol = document.createElement('div');
            secondTextInputCol.className = 'col-6';
    
            const secondTextInput = document.createElement('input');
            secondTextInput.type = 'text';
            secondTextInput.className = 'form-control shadow-none  form-control-sm';
            secondTextInput.placeholder = "e.g. " + getFormattedDate();
    
            const secondTextError = document.createElement('div');
            secondTextError.className = 'error-message';
    
            secondTextInputCol.appendChild(secondTextInput);
            secondTextInputCol.appendChild(secondTextError);
    
            textFieldRow.appendChild(firstTextInputCol);
            textFieldRow.appendChild(secondTextInputCol);
    
            const deleteIconCol = document.createElement('div');
            deleteIconCol.className = 'col-2 d-flex align-items-center';
    
            const deleteIcon = document.createElement('span');
            deleteIcon.innerHTML = '❌';
            deleteIcon.className = 'delete-icon';
    
            // Hide the delete icon for the first row
            if (rowCount === 1) {
                deleteIcon.style.visibility = 'hidden';
            } else {
                deleteIcon.onclick = () => removeRowFunc(rowId);
            }
    
            deleteIconCol.appendChild(deleteIcon);
            textFieldRow.appendChild(deleteIconCol);
    
            rowDiv.appendChild(textFieldRow);
            document.querySelector('.textFields').appendChild(rowDiv);
        }

        removeRowFunc =function(rowId) {
            //Incase an excel file was selected
            const fileInput = document.getElementById('fileInput');
            fileInput.value = '';

            const rowElement = document.getElementById(rowId);
            rowElement.remove();
        }

        validateFunc=function() {
            const rows = document.querySelectorAll('.text-field-container');
            const postcodeRegex = /^[A-Z]{1,2}\d[A-Z\d]? ?\d[A-Z]{2}$/i;
            const datetimeRegex = /^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?$/;
            let validationFailed = false;

            if (rows.length === 1) {
                const firstTextInputCol = rows[0].querySelector('.col-4:nth-child(1)');
                const secondTextInputCol = rows[0].querySelector('.col-6:nth-child(2)');

                const firstTextInput = firstTextInputCol.querySelector('input');
                const firstTextError = firstTextInputCol.querySelector('.error-message');

                const secondTextInput = secondTextInputCol.querySelector('input');
                const secondTextError = secondTextInputCol.querySelector('.error-message');

                // Validate the first text field as a UK postcode
                if (!postcodeRegex.test(firstTextInput.value)) {
                    firstTextError.textContent = 'Invalid UK Postcode';
                    validationFailed = true;
                } else {
                    firstTextError.textContent = '';
                }

                // Validate the second text field as null, date in 'yyyy-mm-dd', or datetime in 'Y-m-d H:i:s' format
                if (secondTextInput.value !== '' && !datetimeRegex.test(secondTextInput.value)) {
                    secondTextError.textContent = 'Invalid  date format (Y-m-d H:i:s or yyyy-mm-dd)';
                    validationFailed = true;
                } else {
                    secondTextError.textContent = '';
                }
            } else {
                let anySecondTextFieldHasValue = false;

                rows.forEach(row => {
                    const secondTextInputCol = row.querySelector('.col-6:nth-child(2)');
                    const secondTextInput = secondTextInputCol.querySelector('input');

                    if (secondTextInput.value !== '') {
                        anySecondTextFieldHasValue = true;
                    }
                });

                rows.forEach(row => {
                    const firstTextInputCol = row.querySelector('.col-4:nth-child(1)');
                    const secondTextInputCol = row.querySelector('.col-6:nth-child(2)');

                    const firstTextInput = firstTextInputCol.querySelector('input');
                    const firstTextError = firstTextInputCol.querySelector('.error-message');

                    const secondTextInput = secondTextInputCol.querySelector('input');
                    const secondTextError = secondTextInputCol.querySelector('.error-message');

                    // Validate the first text field as a UK postcode
                    if (!postcodeRegex.test(firstTextInput.value)) {
                        firstTextError.textContent = 'Invalid UK Postcode';
                        validationFailed = true;
                    } else {
                        firstTextError.textContent = '';
                    }

                    // Validate the second text field
                    if (anySecondTextFieldHasValue && secondTextInput.value === '') {
                        secondTextError.textContent = 'This field cannot be empty if any other date field is filled';
                        validationFailed = true;
                    } else if (secondTextInput.value !== '' && !datetimeRegex.test(secondTextInput.value)) {
                        secondTextError.textContent = 'Invalid date format (Y-m-d H:i:s or yyyy-mm-dd)';
                        validationFailed = true;
                    } else {
                        secondTextError.textContent = '';
                    }
                });
            }

            // Group validation rule
            if (!validationFailed) {
                const groups = {};
                let groupError = false;

                rows.forEach(row => {
                    const secondTextInputCol = row.querySelector('.col-6:nth-child(2)');
                    const secondTextInput = secondTextInputCol.querySelector('input');
                    const secondTextValue = secondTextInput.value.trim();

                    const groupKey = secondTextValue === '' ? 'empty' : secondTextValue.split(' ')[0];

                    if (!groups[groupKey]) {
                        groups[groupKey] = [];
                    }

                    groups[groupKey].push(row);
                });

                for (const key in groups) {
                    if (groups[key].length === 1) {
                        const row = groups[key][0];
                        const firstTextInputCol = row.querySelector('.col-4:nth-child(1)');
                        const firstTextError = firstTextInputCol.querySelector('.error-message');
                        firstTextError.textContent = 'Please add a destination to the origin';
                        groupError = true;
                    }
                }

                if (groupError) {
                    validationFailed = true;
                }
            }

            return !validationFailed;
        }
        
        assignFieldsFunc= function() {
            postCodeArray = [];
            dateArray = [];
    
            const rows = document.querySelectorAll('.text-field-container');
            
            rows.forEach(row => {
                const textFieldRow = row.querySelector('.row');
                const firstTextInputCol = textFieldRow.children[0];
                const secondTextInputCol = textFieldRow.children[1];
    
                const firstTextInput = firstTextInputCol.querySelector('input');
                const secondTextInput = secondTextInputCol.querySelector('input');
    
                postCodeArray.push(firstTextInput.value);
                dateArray.push(secondTextInput.value);
            });
            
            function isArrayEmptyOrBlank(arr) {
                if (!Array.isArray(arr)) {
                  return false; // Not an array, so not considered empty or blank
                }
              
                const isEmptyOrBlank = arr.every(element => element === null || element === "");
                return isEmptyOrBlank ? null : arr; // Return null if empty/blank, otherwise return arr
            }
            dateArray=isArrayEmptyOrBlank(dateArray)
           // console.log('Postcode Array:', postCodeArray);
            //console.log('Date Array:', dateArray);
        }

        importExcelFunc=function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
    
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {type: 'array'});
                const sheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[sheetName];
                const json = XLSX.utils.sheet_to_json(worksheet, {header: 1});
    
                document.querySelector('.textFields').innerHTML = '';
                rowCount = 0;
    
                json.forEach((row, index) => {
                    addRowFunc();
                    const firstTextInput = document.querySelector(`#row-${index + 1} .col-4:nth-child(1) input`);
                    const secondTextInput = document.querySelector(`#row-${index + 1} .col-6:nth-child(2) input`);
    
                    firstTextInput.value = row[0];
                    if (row.length >= 2) {
                        let cellValue = row[1];
                        if (typeof cellValue === 'number') {
                            const date = XLSX.SSF.parse_date_code(cellValue);
                            cellValue = `${date.y}-${String(date.m).padStart(2, '0')}-${String(date.d).padStart(2, '0')}`;
                        }
                        secondTextInput.value = cellValue;
                    } else {
                        secondTextInput.value = '';
                    }
                });
            };
    
            reader.readAsArrayBuffer(file);
        }
        
        resetFunc=function() {
            document.querySelector('.textFields').innerHTML = '';
            rowCount = 0;
            const fileInput = document.getElementById('fileInput');
            fileInput.value = '';
            clearTableFunc()
            addRowFunc();
        }

        
        calculateDistanceFunc=function(){
            let validate=validateFunc()
            if (!validate){return 0;}
            assignFieldsFunc()
            clearTableFunc()

            function get_distanceMoney(distance) {
                // Handle non-numeric input
                if (isNaN(distance)) {
                  return "Invalid distance. Please enter a number.";
                }
              
                // Tier 1: Up to 10000 miles
                const tier1Distance = 10000;
                const tier1Rate = 0.45;
              
                // Tier 2: Beyond 10000 miles
                const tier2Rate = 0.25;
              
                let totalCost = 0;
              
                // Calculate cost for tier 1
                if (distance <= tier1Distance) {
                  totalCost = distance * tier1Rate;
                } else {
                  // Cost for tier 1 + cost for tier 2 (distance exceeding tier 1)
                  totalCost = tier1Distance * tier1Rate + (distance - tier1Distance) * tier2Rate;
                }
                return totalCost.toFixed(2); // Return cost formatted to two decimal places
            }
            
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

            const postcodes = postCodeArray;
            const dates = dateArray;
            const apiKey = hereApiKey;
            const distanceArray = [];
            let total_distance = 0;
            const summaryArray = [];
                        
            const noCoordsArray = [];
            const noRoutesArray = [];

            const geocodeUrl = (postcode) => `https://geocode.search.hereapi.com/v1/geocode?q=${encodeURIComponent(postcode)}&apiKey=${apiKey}`;

            const getCoordinates = async (postcode) => {
                const response = await fetch(geocodeUrl(postcode));
                const data = await response.json();
                if (data.items.length === 0) {
                    noCoordsArray.push(postcode);
                    printInvalidate(noCoordsArray, "Postcodes not valid")
                    throw new Error(`No coordinates found for postcode: ${postcode}`);
                }
                return data.items[0].position;
            };

            const calculateDistance = async (origin, destination) => {
                const routingUrl = `https://router.hereapi.com/v8/routes?transportMode=car&origin=${origin.lat},${origin.lng}&destination=${destination.lat},${destination.lng}&return=summary&apiKey=${apiKey}`;

                const response = await fetch(routingUrl);
                const data = await response.json();
                if (data.routes.length === 0) {
                    noRoutesArray.push(`${origin.lat},${origin.lng} to ${destination.lat},${destination.lng}`);
                    printInvalidate(noRoutesArray, "Postcodes not valid")
                    throw new Error(`No route found between ${origin.lat},${origin.lng} and ${destination.lat},${destination.lng}`);
                }
                return data.routes[0].sections[0].summary.length / 1609.34; // Convert meters to miles
            };

            const calculateDistances = async (postCodeArray, dateArray) => {
                const coordinatesArray = [];

                for (let postcode of postCodeArray) {
                    try {
                        const coords = await getCoordinates(postcode);
                        coordinatesArray.push(coords);
                    } catch (error) {
                        // No coordinates found for this postcode
                        continue;
                    }
                }

                if (noCoordsArray.length > 0) {
                    throw new Error(`Invalid postcodes found: ${noCoordsArray.join(', ')}`);
                }

                if (dateArray) {
                    let currentDistances = [];
                    let currentDate = dateArray[0]
                    if (currentDate.length > 10){
                        currentDate = dateArray[0].toISOString().split('T')[0]; // Initial date part
                    }

                    for (let i = 0; i < coordinatesArray.length - 1; i++) {
                        let originDate = dateArray[i];
                        if (originDate.length > 10){
                            originDate = dateArray[i].toISOString().split('T')[0];
                        }
                        let destDate = dateArray[i + 1];
                        if (destDate.length > 10){
                            destDate = dateArray[i + 1].toISOString().split('T')[0];
                        }

                        if (originDate === currentDate && destDate === currentDate) {
                            try {
                                const distance = await calculateDistance(coordinatesArray[i], coordinatesArray[i + 1]);
                                //alert(distance)
                                currentDistances.push(distance);
                                total_distance += distance;
                            } catch (error) {
                                // No route found for these coordinates
                                continue;
                            }
                        } else {
                            distanceArray.push({ date: currentDate, distances: currentDistances });
                            currentDistances = [];
                            currentDate = destDate;
                        }
                    }
                    if (currentDistances.length > 0) {
                        distanceArray.push({ date: currentDate, distances: currentDistances });
                    }
                } else {
                    let noDateDistances = [];
                    for (let i = 0; i < coordinatesArray.length - 1; i++) {
                        try {
                            const distance = await calculateDistance(coordinatesArray[i], coordinatesArray[i + 1]);
                            noDateDistances.push(distance);
                            total_distance += distance;
                        } catch (error) {
                            // No route found for these coordinates
                            continue;
                        }
                    }
                    distanceArray.push({ date: null, distances: noDateDistances });
                }

                if (noRoutesArray.length > 0) {
                    throw new Error(`No routes found for postcodes: ${noRoutesArray.join(', ')}`);
                }

                return distanceArray;
            };

            const generateSummary = (distanceArray) => {
                distanceArray.forEach(entry => {
                    const totalDistanceForDate = entry.distances.reduce((acc, val) => acc + val, 0);
                    summaryArray.push({ date: entry.date, distance: totalDistanceForDate });
                });
            };
            const datesArray=[]
            const distArray=[]  
            calculateDistances(postcodes, dates)
                .then(distances => {
                    generateSummary(distances);
                    summaryArray.forEach(entry => {
                        //console.log(`Date: ${entry.date}, Distance: ${entry.distance}`);
                        datesArray.push(entry.date)
                        distArray.push(round_number(entry.distance))
                    });

                    //console.log(`Total Distance: ${total_distance}`);
                    createTableRowFunc(distArray,datesArray)
                    const distanceSpan = document.getElementById('totalDistanceID');
                    // Set the text content of the span
                    distanceSpan.textContent = round_number(total_distance); //
                    let money= get_distanceMoney(total_distance)
                    const moneySpan = document.getElementById('reimbursmentID');
                    moneySpan.textContent = money
                 

                })
                .catch(error => {
                    console.error(`Error: ${error.message}`);
            });
        }

        createTableRowFunc=function(firstArray, secondArray){
            // JavaScript to create the table rows dynamically
            const digitArray = firstArray;  //[1, 2, 3, 4, 5,6,7,8];
            const wordsArray = secondArray;   //['one', 'two', 'three', 'four', 'five','six','seven', 'eight'];
            for (let i = 0; i < digitArray.length; i++) {
                const row = document.createElement('tr');

                const digitCell = document.createElement('td');
                digitCell.textContent = digitArray[i];
                row.appendChild(digitCell);

                const wordCell = document.createElement('td');
                wordCell.textContent = wordsArray[i];
                row.appendChild(wordCell);

                tableBody.appendChild(row);
            }
        }
        clearTableFunc=function() {
            const distanceSpan = document.getElementById('totalDistanceID');
            // Set the text content of the span
            distanceSpan.textContent = "__________"
            const moneySpan = document.getElementById('reimbursmentID');
            moneySpan.textContent = "__________"
            tableBody.innerHTML = '';
        }

        printInvalidate=function(psArray, errMsg) {
            const rows = document.querySelectorAll('.text-field-container');
            
            rows.forEach(row => {
                const firstTextInputCol = row.querySelector('.col-4:nth-child(1)');
                const firstTextInput = firstTextInputCol.querySelector('input');
                const firstTextError = firstTextInputCol.querySelector('.error-message');
    
                if (psArray.includes(firstTextInput.value)) {
                    firstTextError.textContent = errMsg;
                }
            });
        }

        const lessTexts = document.querySelectorAll('.less-text');
        lessTexts.forEach(text => text.classList.add('hidden-text'));

        showMore=function(element) {
            const lessText = element.nextElementSibling;
            const lessLink = element.nextElementSibling.nextElementSibling;

            if (lessText) {
                lessText.classList.add('show-text');
                lessText.classList.remove('hidden-text');
            }
            if (lessLink) lessLink.style.display = 'inline';
            element.style.display = 'none';
        }

        showLess=function(element) {
            const lessText = element.previousElementSibling;
            const moreLink = element.parentElement.querySelector('.more-link');

            if (lessText) {
                lessText.classList.add('hidden-text');
                lessText.classList.remove('show-text');
            }
            if (moreLink) moreLink.style.display = 'inline';
            element.style.display = 'none';
        }

        addRowFunc()
   
  });






    

