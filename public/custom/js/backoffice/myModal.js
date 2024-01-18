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
const deleteBtn='<button type="buttton" class="btn btn-danger"  onClick="delete_modalFunc()" >Delete</button>';
const updateBtn='<button type="buttton" class="btn btn-danger"  onClick="update_modalFunc()" >Update</button>'
const pdfBtn= '<button type="button" class="btn btn-primary"  onClick="printToPdf_modalFunc()" >Print to PDF </button>';

const myModalID=document.getElementById('modalBodyID');
const spinner_modal = document.getElementById("spinner_modal");


//*** borrowed space for spinner */
const spinner = document.getElementById("spinner");
const alertDangerID = document.getElementById("alertDangerID");
const alertInfoID = document.getElementById("alertInfoID");

const alertDanger_mobileID = document.getElementById("alertDanger_mobileID");
const alertInfo_mobileID = document.getElementById("alertInfo_mobileID");

let show_alertInfo=function(xText){
    if (isABootstrapModalOpen()){
        alertInfo_mobileID.innerHTML=xText;
        fadeIn(alertInfo_mobileID);
        setTimeout(() => { fadeOut(alertInfo_mobileID);}, 5*1000);
    }else{
        alertInfoID.innerHTML=xText;
        fadeIn(alertInfoID);
        setTimeout(() => { fadeOut(alertInfoID);}, 5*1000);
    }    
}

let show_alertDanger=function(xText){
    if (isABootstrapModalOpen()){
        alertDanger_mobileID.innerHTML=xText;
        fadeIn(alertDanger_mobileID);
       setTimeout(() => { fadeOut(alertDanger_mobileID);},6*1000);
    }else{
        alertDangerID.innerHTML=xText;
        fadeIn(alertDangerID);
        setTimeout(() => { fadeOut(alertDangerID);}, 6*1000);
    }    
}
//Determines if modal is present or not
function isABootstrapModalOpen() {
    return document.querySelectorAll('.modal.show').length > 0;
}
let show_spinner=function(){
    if (isABootstrapModalOpen()){
        // This is the main page
        spinner_modal.removeAttribute('hidden');
    }else{ //This must be modal
        spinner.removeAttribute('hidden');
    }
    
}
let hide_spinner=function(){
    if (isABootstrapModalOpen()){
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
}
let hide_modal=function(){
    let myModalEl = document.getElementById('myModal');
    let modal = bootstrap.Modal.getInstance(myModalEl)
    modal.hide();
}   

let disable_modalFunc=function(){}
let update_modalFunc=function(){}
let delete_modalFunc=function(){}
let printToPdf_modalFunc=function(){}
let goAheadWithSendingMsg=function(){}
;

