"use strict";
let detailsLevel1Func=function(){}
let detailsLevel2Func=function(){}
let reload_admin_mileageFunc=function(){}


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
  
function initializeDataTable(tableId) {
    return new simpleDatatables.DataTable("#" + tableId, {
        searchable: false,
        fixedHeight: false,
        sortable: false,
        perPage: 10, // Adjust this number as needed
        perPageSelect: false,
        // Disable all features except pagination
        paging: true,
        labels: {
            placeholder: "",
            perPage: "",
            noRows: "No data available",
            info: "{start} to {end} of {rows} entries",
        },
    });
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

    const userIDField = document.querySelector('input[type="hidden"][name="userIDField"]');

    function openAccordionPane(accordionId, paneId) {
        // Find the accordion container
        const accordion = document.getElementById(accordionId);
        
        if (!accordion) {
            console.error(`Accordion with id '${accordionId}' not found`);
            return;
        }
    
        // Find the target pane
        const targetPane = accordion.querySelector(`#${paneId}`);
        
        if (!targetPane) {
            console.error(`Pane with id '${paneId}' not found in accordion '${accordionId}'`);
            return;
        }
    
        // Get all panes in the accordion
        const allPanes = accordion.querySelectorAll('.accordion-collapse');
    
        // Close all panes
        allPanes.forEach(pane => {
            if (pane.id !== paneId) {
                const bsCollapse = bootstrap.Collapse.getInstance(pane);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }
        });
    
        // Open the target pane
        const bsCollapse = new bootstrap.Collapse(targetPane, {
            toggle: false
        });
        bsCollapse.show();
    
        // Update aria-expanded attribute on the button
        const button = accordion.querySelector(`button[aria-controls="${paneId}"]`);
        if (button) {
            button.setAttribute('aria-expanded', 'true');
        }
    }
    
    // Initialize Simple DataTables with only pagination
    //-origninal
    initializeDataTable("mileageTable1");
  
    const accordionItem1 = document.getElementById('collapseOne');
    let table1Initialized = false;
    accordionItem1.addEventListener('shown.bs.collapse', function () {
        if (!table1Initialized) {
            initializeDataTable("mileageTable1");
            table1Initialized = true;
        }
    });
   
    const accordionItem2 = document.getElementById('collapseTwo');
    let table2Initialized = false;
    accordionItem2.addEventListener('shown.bs.collapse', function () {
        if (!table2Initialized) {
            initializeDataTable("mileageTable2");
            table2Initialized = true;
        }
    });
    

    const accordionItem3 = document.getElementById('collapseThree');
    let table3Initialized = false;
    accordionItem3.addEventListener('shown.bs.collapse', function () {
        if (!table3Initialized) {
            initializeDataTable("mileageTable3");
            table3Initialized = true;
        }
    });
  

    


    detailsLevel1Func=function(userID){
        show_spinner() 
        userIDField.value=userID
        const post_data={
           userID:userID,
           startDate:startDateValue,
           endDate:endDateValue
        };
        const asyncPostCall = async () => {
            try {
                const response = await fetch(get_detailsLevel1URL, {
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
                alert(JSON.stringify(data));
                const div = document.getElementById('detailsDateID');
                div.style.display = 'block';
                
                
                const level1 = document.getElementById('level1_componentID');
                const head1 = document.getElementById('level1_headID'); 
                     
                // Replace the content of the div
                level1.innerHTML = data.html;
                head1.textContent = data.heading
                openAccordionPane('reportAccordion', 'collapseTwo');

               hide_spinner()               
            } catch(error) {
                hide_spinner()
            } 
        }    
        asyncPostCall()
    }
    
    detailsLevel2Func=function(dateX){
        const userID = userIDField.value
        show_spinner() 
        const post_data={
           userID:userID,
           dateX:dateX,
        };
        const asyncPostCall = async () => {
            try {
                const response = await fetch(get_detailsLevel2URL, {
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
                const div = document.getElementById('detailsPostcodesID');
                div.style.display = 'block';
                
                //alert(JSON.stringify(data));
                const level2 = document.getElementById('level2_componentID');
                const head2 = document.getElementById('level2_headID'); 
                     
                // Replace the content of the div
                level2.innerHTML = data.html;
                head2.textContent = data.heading
                openAccordionPane('reportAccordion', 'collapseThree');

               hide_spinner()               
            } catch(error) {
                hide_spinner()
            } 
        }    
        asyncPostCall()
       
    }

    reload_admin_mileageFunc=function(){
        show_spinner() 
        const post_data={
            endDate : endDateValue,
            startDate: startDateValue
        };
        const asyncPostCall = async () => {
            try {
                const response = await fetch(reload_admin_mileageURL, {
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
                //hide the other panes
                const div2 = document.getElementById('detailsPostcodesID');
                div2.style.display = 'none'
                const div1 = document.getElementById('detailsDateID');
                div1.style.display = 'none'

               
                //alert(JSON.stringify(data));
                var myDiv = document.getElementById('admin_mileage_sum');
                        
                // Replace the content of the div
                myDiv.innerHTML = data.html;
                openAccordionPane('reportAccordion', 'collapseOne');
                initializeDataTable("mileageTable1");
               hide_spinner()               
            } catch(error) {
                hide_spinner()
            } 
        }    
        asyncPostCall()
    }


})    