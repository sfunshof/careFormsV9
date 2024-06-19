"use strict";
let serviceUserdetailsUpdateFunc=function(){}
let serviceUserDisableFunc=function(){}
let serviceUserEnableFunc=function(){}
let prospectConvertFunc=function(){}
let browse_all_serviceUsersFunc=function(){}
let extractServiceUserDetails_thenDisplay=function(){} 
let pdf_print_prospectFunc = function(){}

//we use modal here instead of the update_serviceUserFunc 
//beauce userID would not work with j
let update_serviceUserModalFunc=function(){}

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
    
    let dataTable = document.getElementById('serviceUserBrowseTableID') || false
   
    //survey feedback table
    if (dataTable) {
         dataTable = new simpleDatatables.DataTable("#serviceUserBrowseTableID",{
            searchable:false,
            perPageSelect:false,
            fixedheight:true,
            sortable:false,
            labels:{
              info:""
            }
        })
    }  
    //This is shared by  serviceUsers and prosprct each with thier own blade
    //get_serviceUserDetailsURL is defined differently for service user and prospect
    // 
    serviceUserdetailsUpdateFunc=function(userID, isProspect, isPrint){
        //Wre are getting the details beacuse we need to 
        //render the details on the modal 
        let URLpath=get_serviceUserDetailsURL;
        //The original update_serviceUserModalFunc function  was created in JS and we could
        //not pass the userID argument. so It was left blank and since the parent function
        //has userID we can call update_serviceUserFunc with the userID
        update_serviceUserModalFunc=function(){
            //Now we call update_serviceUserFunc  with our userID
            update_serviceUserFunc(userID)
        }
        //alert(URLpath)

        show_spinner()
        const asyncPostCall = async () => {
            let post_data={
                userID:userID,
                isPrint:isPrint
            }
            try {
                const response = await fetch(URLpath, {
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
                   // enter you logic when the fetch is successful
                    //alert(JSON.stringify(data))
                    hide_spinner() 
                    let title ="Update the Service User Details";
                    let btn ='<button type="buttton" class="btn btn-primary"  onClick="update_serviceUserModalFunc()" >Update</button>';
                    if (isProspect==1) title="Update the Assessment Details"
                    if (isPrint==1){
                        title="Print the Assessment Details"
                        btn = '<button type="button" class="btn btn-primary"  onClick="printToPdf_modalFunc()" >Print to PDF </button>';
                    } 
                    let bodyMsg=data;
                    //We do not know how to pass userID so we use a general function update_serviceUserModalFunc
                    
                    let btns= btn + closeBtn
                    show_modal(title, bodyMsg, btns)
                    modalDialogID.classList.remove('modal-md');
                    modalDialogID.classList.add('modal-lg');
                }catch(error) {
                    // enter your logic for when there is an error (ex. error toast)
                    //alert(error);
                    hide_spinner()
                } 
        }    
        asyncPostCall()
    }

    serviceUserDisableFunc=function(userID, fullName){
        //Bring up the modal
        let bodyMsg="Do you want to disable  the user: <br> " + fullName + '? <br>';
        let btns=disableBtn + closeBtn
        show_modal("Warning ", bodyMsg, btns);
        modalDialogID.classList.remove('modal-lg');
        modalDialogID.classList.add('modal-md');
        

        disable_modalFunc=function(){
            hide_modal()
            show_spinner()
            const asyncPostCall = async () => {
                let post_data={
                    userID:userID
                }
                try {
                    const response = await fetch(disable_serviceUserURL, {
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
                    // enter you logic when the fetch is successful
                    //alert(JSON.stringify(data));
                    show_alertInfo("User Successfully disabled")
                    hide_spinner()
                    let pageNo= typeof dataTable.currentPage !== 'undefined' ? dataTable.currentPage :-1 ;
                    window.location.replace(browse_serviceUsersURL + "/" + pageNo);
                                    
                } catch(error) {
                    // enter your logic for when there is an error (ex. error toast)
                    alert(error);
                    hide_spinner()
                } 
            }    
            asyncPostCall()
            }
        }               
       
        serviceUserEnableFunc=function(userID){
            show_spinner()
            const asyncPostCall = async () => {
                let post_data={
                    userID:userID
                }
                try {
                    const response = await fetch(enable_serviceUserURL, {
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
                    // enter you logic when the fetch is successful
                    //alert(JSON.stringify(data));
                    show_alertInfo("User Successfully activated")
                    hide_spinner()
                    let pageNo= typeof dataTable.currentPage !== 'undefined' ? dataTable.currentPage :-1 ;
                    window.location.replace(browse_serviceUsersURL + "/" + pageNo);
                                  
                                    
                } catch(error) {
                    // enter your logic for when there is an error (ex. error toast)
                    alert(error);
                    hide_spinner()
                } 
            }    
            asyncPostCall()
        }
        
        pdf_print_prospectFunc=function(){
            show_spinner(1)
            const asyncPostCall = async () => {
                let post_data={
                    userID:document.querySelector('input[name="userID"]').value,
                    isPrint:1
                }
                try {
                    const response = await fetch(pdf_prospectURL, {
                        method: 'POST',
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json, text-plain, */*",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": token
                        },
                        body: JSON.stringify(post_data)
                    });
                    const blob = await response.blob();
                   // alert(JSON.stringify(blob))
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "assessment.pdf";
                    link.click();
                    hide_spinner(1)                                  
                                    
                } catch(error) {
                    // enter your logic for when there is an error (ex. error toast)
                    alert(error);
                    hide_spinner(1)
                } 
            }    
            asyncPostCall()
        }


        prospectConvertFunc=function(userID, fullName){
            //Bring up the modal
            let bodyMsg="Do you want to convert the assessment: " + fullName + '<br> to a service user ?';
            let btns=convertBtn + closeBtn
            show_modal("Warning ", bodyMsg, btns);
            modalDialogID.classList.remove('modal-lg');
            modalDialogID.classList.add('modal-md');
            
    
            convert_modalFunc=function(){
                hide_modal()
                show_spinner()
                const asyncPostCall = async () => {
                    let post_data={
                        userID:userID
                    }
                    try {
                        const response = await fetch(convert_prospectURL, {
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
                        //alert(JSON.stringify(data))
                        // enter you logic when the fetch is successful
                        //alert(JSON.stringify(data));
                        show_alertInfo("User Successfully converted")
                        hide_spinner()
                        let pageNo= typeof dataTable.currentPage !== 'undefined' ? dataTable.currentPage :-1 ;
                        window.location.replace(browse_serviceUsersURL + "/" + pageNo);
                                        
                    } catch(error) {
                        // enter your logic for when there is an error (ex. error toast)
                        alert(error);
                        hide_spinner()
                    } 
                }    
                asyncPostCall()
                }
            }

           let  applyViewStyleFunc=function(){
                // Create a style element
                const style = document.createElement('style');
                style.textContent = `
                    .form-control[data-print], .form-select[data-print], .form-floating .form-control[data-print], .form-floating .form-select[data-print], .form-floating textarea[data-print] {
                        border-top: 0;
                        border-left: 0;
                        border-right: 0;
                        border-radius: 0;
                        box-shadow: none !important;
                    }
                    .form-floating .form-control[data-print]:focus,
                    .form-floating .form-control[data-print]:not(:placeholder-shown),
                    .form-floating select.form-select[data-print]:focus,
                    .form-floating select.form-select[data-print]:not(:placeholder-shown),
                    .form-floating textarea[data-print]:focus,
                    .form-floating textarea[data-print]:not(:placeholder-shown) {
                        border-bottom: 2px solid #000; /* Default bottom border */
                    }
                    .form-control[data-print][readonly],
                    .form-select[data-print][readonly],
                    textarea[data-print][readonly] {
                        background-color: #fff; /* Keep background white */
                        opacity: 1; /* Ensure full opacity */
                        cursor: not-allowed; /* Change cursor to indicate non-editable */
                    }
                    textarea[data-print][readonly] {
                        resize: none; /* Disable resizing */
                        overflow: hidden; /* Hide scrollbar */
                    }
                    .form-select[data-print][readonly] {
                        pointer-events: none; /* Disable clicks */
                        appearance: none; /* Remove default arrow */
                        -webkit-appearance: none; /* Remove default arrow in Safari */
                        -moz-appearance: none; /* Remove default arrow in Firefox */
                    }
                    .form-check-input[data-print][readonly] {
                        pointer-events: none; /* Disable clicks */
                    }
                `;
                // Append the style element to the head
                document.head.appendChild(style);
        
                // Select all form controls with data-print attribute and apply styles
                const formControls = document.querySelectorAll('.form-control[data-print], .form-select[data-print], textarea[data-print], .form-check-input[data-print]');
        
                formControls.forEach(control => {
                    // Remove top, left, right borders and add styles
                    control.style.borderTop = '0';
                    control.style.borderLeft = '0';
                    control.style.borderRight = '0';
                    control.style.borderRadius = '0';
                    control.style.boxShadow = 'none';
        
                    // Set controls to read-only
                    control.setAttribute('readonly', true);
                    control.style.backgroundColor = '#fff';
                    control.style.opacity = '1';
                    control.style.cursor = 'not-allowed';
        
                    // Special handling for select elements
                    if (control.tagName === 'SELECT') {
                        control.style.pointerEvents = 'none';
                        control.style.appearance = 'none';
                        control.style.WebkitAppearance = 'none';
                        control.style.MozAppearance = 'none';
                    }
        
                    // Special handling for textareas
                    if (control.tagName === 'TEXTAREA') {
                        control.style.resize = 'none';
                        control.style.overflow = 'hidden';
                    }
        
                    // Add event listeners for focus and blur to control the bottom border
                    control.addEventListener('focus', function () {
                        control.style.borderBottom = '2px solid #000';
                    });
        
                    control.addEventListener('blur', function () {
                        if (control.value !== '') {
                            control.style.borderBottom = '2px solid #000';
                        } else {
                            control.style.borderBottom = '';
                        }
                    });
        
                    // Check initial state of the input
                    if (control.value !== '') {
                        control.style.borderBottom = '2px solid #000';
                    }
                });
      
            }
        externalFunc=function(){
            applyViewStyleFunc()
        }
            
        printToPdf_modalFunc=function(){
            pdf_print_prospectFunc()
        }
        
        browse_all_serviceUsersFunc=function(){
            let disableUsers = document.getElementById("showDisabledUsersID");
            show_spinner()
            if (disableUsers.checked) {
                window.location.replace(browse_all_serviceUsersURL);
            }else{
                window.location.replace(browse_serviceUsersURL);
            }    
        }

        
    })
       