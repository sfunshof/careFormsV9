"use strict";
let employeedetailsUpdateFunc=function(){}
let employeeDisableFunc=function(){}
let employeeEnableFunc=function(){}
let browse_all_employeesFunc=function(){}
let  extractEmployeeDetails_thenDisplay=function(){} 
//we use modal here instead of the update_serviceUserFunc 
//beauce userID would not work with j
let update_employeeModalFunc=function(){}

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
    
    let dataTable = document.getElementById('employeeBrowseTableID') || false
   
    //survey feedback table
    if (dataTable) {
         dataTable = new simpleDatatables.DataTable("#employeeBrowseTableID",{
            searchable:false,
            perPageSelect:false,
            fixedheight:true,
            sortable:false,
            labels:{
              info:""
            }
        })
    }  

    employeedetailsUpdateFunc=function(userID){
        //Wre are getting the details beacuse we need to 
        //render the details on the modal 
        let URLpath=get_employeeDetailsURL;
        //The original update_serviceUserModalFunc function  was created in JS and we could
        //not pass the userID argument. so It was left blank and since the parent function
        //has userID we can call update_serviceUserFunc with the userID
        update_employeeModalFunc=function(){
            //Now we call update_serviceUserFunc  with our userID
            update_employeeFunc(userID)
        }
        show_spinner()
        const asyncPostCall = async () => {
            let post_data={
                userID:userID
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
                   //alert(JSON.stringify(data));
                    hide_spinner()
                    let title ="Update the Employee Details";
                    let bodyMsg=data;
                    //We do not know how to pass userID so we use a general function update_employeeModalFunc
                    let btn='<button type="buttton" class="btn btn-primary"  onClick="update_employeeModalFunc()" >Update</button>';
                    let btns= btn + closeBtn
                    show_modal(title, bodyMsg, btns)
                }catch(error) {
                    // enter your logic for when there is an error (ex. error toast)
                    alert(error);
                    hide_spinner()
                } 
        }    
        asyncPostCall()
    }

    employeeDisableFunc=function(userID, fullName){
        //Bring up the modal
        let bodyMsg="Do you want to disable  the user: <br> " + fullName + '? <br>';
        let btns=disableBtn + closeBtn
        show_modal("Warning ", bodyMsg, btns);
        modalDialogID.classList.remove('modal-lg');
        modalDialogID.classList.add('modal-sm');
        

        disable_modalFunc=function(){
            hide_modal()
            show_spinner()
            const asyncPostCall = async () => {
                let post_data={
                    userID:userID
                }
                try {
                    const response = await fetch(disable_employeeURL, {
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
                    window.location.replace(browse_employeesURL + "/" + pageNo);
                                    
                } catch(error) {
                    // enter your logic for when there is an error (ex. error toast)
                    alert(error);
                    hide_spinner()
                } 
            }    
            asyncPostCall()
            }
        }
       
        employeeEnableFunc=function(userID){
            show_spinner()
            const asyncPostCall = async () => {
                let post_data={
                    userID:userID
                }
                try {
                    const response = await fetch(enable_employeeURL, {
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
                    window.location.replace(browse_employeesURL + "/" + pageNo);
                                    
                } catch(error) {
                    // enter your logic for when there is an error (ex. error toast)
                    alert(error);
                    hide_spinner()
                } 
            }    
            asyncPostCall()
        }
        



        browse_all_employeesFunc=function(){
            let disableUsers = document.getElementById("showDisabledUsersID");
            if (disableUsers.checked) {
                window.location.replace(browse_all_employeesURL);
            }else{
                window.location.replace(browse_employeesURL);
            }    
        }

        
    })
       