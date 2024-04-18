"use strict";
let serviceUserdetailsUpdateFunc=function(){}
let serviceUserDisableFunc=function(){}
let serviceUserEnableFunc=function(){}
let browse_all_serviceUsersFunc=function(){}
let  extractServiceUserDetails_thenDisplay=function(){} 
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

    serviceUserdetailsUpdateFunc=function(userID){
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
                    let title ="Update the Service User Details";
                    let bodyMsg=data;
                    //We do not know how to pass userID so we use a general function update_serviceUserModalFunc
                    let btn='<button type="buttton" class="btn btn-primary"  onClick="update_serviceUserModalFunc()" >Update</button>';
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
        



        browse_all_serviceUsersFunc=function(){
            let disableUsers = document.getElementById("showDisabledUsersID");
            if (disableUsers.checked) {
                window.location.replace(browse_all_serviceUsersURL);
            }else{
                window.location.replace(browse_serviceUsersURL);
            }    
        }

        
    })
       