"use strict";
let selectYearFunc=function(){}
let selectMonthFunc=function(){}
let surveyFunc=function(){}

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
    
    let dataTable = document.getElementById('SurveyFeedbackTableID') || false
    const selectYearID    = document.getElementById("selectYearID"); 
    const selectMonthID  = document.getElementById("selectMonthID"); 
    let monthVal=-1;
   
    //Trigger both select option
    let changeEvent=new Event ("change")
    selectYearID.dispatchEvent(changeEvent)
    selectMonthID.dispatchEvent(changeEvent)



    //survey feedback table
    if (dataTable) {
         dataTable = new simpleDatatables.DataTable("#surveyFeedbackTableID",{
            searchable:false,
            perPageSelect:false,
            fixedheight:true,
            sortable:false,
            labels:{
              info:""
            }
        })
    }  
    
    let year =selectYearID.options[selectYearID.selectedIndex].value
    let monthText=selectMonthID.options[selectMonthID.selectedIndex].text
    monthVal = selectMonthID.options[selectMonthID.selectedIndex].value
    selectYearFunc=function(){
        show_spinner()
        year=selectYearID.options[selectYearID.selectedIndex].value
        let result=get_reloadURL();
        let URLreload=result.URLreload;
        window.location.replace(URLreload)        
    }
    selectMonthFunc=function(){
        show_spinner()
        monthText=selectMonthID.options[selectMonthID.selectedIndex].text
        monthVal=selectMonthID.options[selectMonthID.selectedIndex].value
        let result=get_reloadURL();
        let URLreload=result.URLreload;
        window.location.replace(URLreload) 
    }
    surveyFunc=function(userID, statusID,  responseTypeID, unique_value, sentCount, sentEmailCount, tel){
        //statusID 1 Created not sent  => Send
        //statusID 2 Sent not received => Re-send we use date_posted
        //statusID 3 Received => view
        //const token = document.head.querySelector("[name~=csrf-token][content]").content;
        
        let sms = document.getElementById(unique_value+1).checked;
        let email = document.getElementById(unique_value+2).checked;
        function convertToNumber(input) {
            return input ? 1 : 0;
        }
        //alert(convertToNumber(sms) + ' ' + convertToNumber(email))
        let isSMS=convertToNumber(sms)

        //spinner.removeAttribute('hidden');
        let URLpath=_sendSMSURL;
        //View 
        if (statusID==3){
            URLpath=user_viewURL + "/" + userID + "/" + unique_value + "/" +  responseTypeID;
            // Calling that async function to display the feedback form's data
            extractData_thenDisplay(URLpath);
            return 0;
        }
        //sent by sms 
        let smsMsg= smsPreText + ' ' +  URLbase + "/" + unique_value
        if (isSMS==1){
            if (isServiceUser==1){
                if ((statusID==2) && (sentCount==2)){
                    //bring out the modal
                    too_manySMS(smsMsg,tel, 1)
                    return 0;
                }
            }else if (isServiceUser==0){ //employee
                if ((statusID==2) && (sentCount==2) && (sentEmailCount < 2)  ){
                    //SMS full send by email
                    too_manySMS(smsMsg,tel, 4)
                    return 0;
                }
                if ((statusID==2) && (sentCount==2) && (sentEmailCount == 2)  ){
                    //SMS full , email full
                    too_manySMS(smsMsg,tel, 1)
                    return 0;
                }

            }    
        }else if (isSMS==0){ //sent by email
            if ((statusID==2) && (sentEmailCount==2) && (sentCount < 2) ){
                //Email full send by SMS
                too_manySMS(smsMsg,tel, 3)
                return 0;
            }
            if ((statusID==2) && (sentEmailCount==2) && (sentCount == 2)  ){
                //SMS full , email full
                too_manySMS(smsMsg,tel, 0)
                return 0;
            }   
        }
        
       //Send SMS/Email to the clients
        let result=get_reloadURL();
        let date_of_interest=result.date_of_interest;
        let URLreload=result.URLreload;
        sms_toUsers(userID,statusID, tel,responseTypeID,URLpath,date_of_interest,URLreload,isSMS,sentCount,sentEmailCount)
        
    }
    let get_reloadURL=function(){
        let monthStr=monthVal < 10 ? "0" + monthVal: monthVal
        let date_of_interest=  year + "-" + monthStr + "-01"    
        
        let pageNo= typeof dataTable.currentPage !== 'undefined' ? dataTable.currentPage :-1 ;

        let URLreload=URLbase+ '/employee/browse_surveyfeedback/' +monthVal + '/' + year + '/' + pageNo
        if (isServiceUser==1){
             URLreload=URLbase+ '/serviceUser/browse_surveyfeedback/' +monthVal + '/' + year + '/' + pageNo
        }
        
        
        const result={"date_of_interest":date_of_interest, "URLreload":URLreload }
        return result;
    }     
    
    if (dateFlag==1){
        show_alertInfo("This time period is not yet active")
    }
   
   

}) 