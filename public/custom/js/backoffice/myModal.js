//From boostrap team
var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
//** End of boostrap team  */
let copyToClipboard=function(){}

const modalBtnID = document.querySelector('#modalBtnID');
const modalTitle= document.querySelector('.modal-title');
const modalBody= document.getElementById('modalBodyID');
const modalFooter= document.querySelector('.modal-footer');
const modalDialogID = document.getElementById("modal-dialogID");
const copyBtn= '<button type="button" class="btn btn-primary"  id="tt" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Copied!!!"  onClick="copyToClipboard()" >Copy to clipboard</button>';
const closeBtn= '<button type="button"  class="btn btn-info" data-bs-dismiss="modal">Close</button>';
//We do not know how to pass userID so we use a general function update_serviceUserModalFunc
const disableBtn='<button type="buttton" class="btn btn-danger"  onClick="disable_modalFunc()" >Disable</button>';
const convertBtn='<button type="buttton" class="btn btn-warning"  onClick="convert_modalFunc()" >Convert</button>';
const deleteBtn='<button type="buttton" class="btn btn-danger"  onClick="delete_modalFunc()" >Delete</button>';
const resetBtn='<button type="buttton" class="btn btn-danger"  onClick="reset_modalFunc()" >Reset</button>';
const updateBtn='<button type="buttton" class="btn btn-danger"  onClick="update_modalFunc()" >Update</button>'
const pdfBtn= '<button type="button" class="btn btn-primary"  onClick="printToPdf_modalFunc()" >Print to PDF </button>';
const emailBtn= '<button type="button"  id="emailBtnID"  class="btn btn-success"  onClick="email_modalFunc()" >Send to Carer </button>';
const emailBtnDisabled= '<button type="button"  class="btn btn-success"  disabled >Send to Carer </button>';
const spotCheckSaveBtn='<button type="button" class="btn btn-primary"  onClick="spotCheckSave_modalFunc()" >Save </button>';

const myModalID=document.getElementById('modalBodyID');
const spinner_modal = document.getElementById("spinner_modal");


//*** borrowed space for spinner */
const spinner = document.getElementById("spinner"); 
const alertDangerID = document.getElementById("alertDangerID");
const alertInfoID = document.getElementById("alertInfoID");

const alertDanger_mobileID = document.getElementById("alertDanger_mobileID");
const alertInfo_mobileID = document.getElementById("alertInfo_mobileID");

let show_alertInfo=function(xText, x=0){
    Fnon.Hint.Info(xText, {
        position:'center-center',
        displayDuration: 6000,
        width: '400px',
        callback:function(){
        // callback
        }
    });
    
    /*
    if (isABootstrapModalOpen() ||(x==1)){
        alertInfo_mobileID.innerHTML=xText;
        fadeIn(alertInfo_mobileID);
        setTimeout(() => { fadeOut(alertInfo_mobileID);}, 5*1000);
    }else{
        alertInfoID.innerHTML=xText;
        fadeIn(alertInfoID);
        setTimeout(() => { fadeOut(alertInfoID);}, 5*1000);
    } 
    */   
}


let show_alertDanger=function(xText, param2){
    Fnon.Hint.Danger(xText, {
        position:'center-center',
        displayDuration: 6000,
        width: '400px',
        callback:function(){
            // callback
            // Check if the second parameter (callback) is a function
            if (typeof param2 === 'function') {
                //console.log('Parameter 2 (callback) is provided.');
                // Execute the callback function
                param2();
            } else {
                //console.log('Parameter 2 (callback) is not provided.');
            }
        }
});
                
    /*if (isABootstrapModalOpen()){
        alertDanger_mobileID.innerHTML=xText;
        fadeIn(alertDanger_mobileID);
       setTimeout(() => { fadeOut(alertDanger_mobileID);},6*1000);
    }else{
        alertDangerID.innerHTML=xText;
        fadeIn(alertDangerID);
        setTimeout(() => { fadeOut(alertDangerID);}, 6*1000);
    } 
    */   
}
//Determines if modal is present or not
function isABootstrapModalOpen() {
    //return document.querySelectorAll('.modal.show').length > 0;
    // Select the modal element by ID
    var modal = document.querySelector("#modal-dialogID");
  
    // Check if the modal element exists and is visible
    if (modal && (modal.style.display == "block")) {
         // Check if the modal contains the class modal-lg
         if (modal.classList.contains("modal-lg")) {
             return 1; // Return 1 if the modal is visible and contains the class modal-lg
         } else if (modal.classList.contains("modal-md")) { 
             return 1; // Return 0 if the modal is visible but doesn't contain the class modal-lg
         }else{
            return 0;
         }
     } else {
         return 0; // Return 0 if the modal is not visible or doesn't exist
     }
}

let show_spinner=function(x=0){
    if (isABootstrapModalOpen() || (x==1)){
        // This is the main page
        spinner_modal.removeAttribute('hidden');
    }else{ //This must be modal
       spinner.removeAttribute('hidden');
    }
    
}
let hide_spinner=function(x=0){
    if (isABootstrapModalOpen() || (x==1)){
        // This is the main page
        spinner_modal.setAttribute('hidden', '');
    }else{ //This must be modal
        spinner.setAttribute('hidden', '');
    } 
}

let show_modal=function(titleText, bodyMsg, footerBtn){
    modalDialogID.classList.remove('modal-sm');
    modalDialogID.classList.remove('modal-md');
    modalDialogID.classList.add('modal-lg');
    modalTitle.innerHTML=titleText
    modalBody.innerHTML=bodyMsg;
    modalFooter.innerHTML= footerBtn;
    modalBtnID.click();
    let btn =document.getElementById('externalFuncBtnID');
    if (btn){
        btn.click()
    }
}

let hide_modal=function(){
    let myModalEl = document.getElementById('myModal');
    let modal = bootstrap.Modal.getInstance(myModalEl)
    if (modal !== null) {
        modal.hide(); 
    } 
   
}   
let convert_modalFunc=function(){}
let disable_modalFunc=function(){}
let reset_modalFunc=function(){}
let update_modalFunc=function(){}
let delete_modalFunc=function(){}
let printToPdf_modalFunc=function(){}
let email_modalFunc=function(){}
let spotCheckSave_modalFunc=function(){}
let goAheadWithSendingMsg=function(){}
let externalFunc=function(){}
;

