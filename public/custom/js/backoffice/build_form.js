"use strict";
let add_quesFunc=function(){}
let update_formFunc=function(){}
let reset_quesFunc=function(){}
let del_quesFunc=function(){}
let quesChangeFunc=function(){}
let cqcChangeFunc=function(){}
let quesTypeChangeFunc=function(){}
let add_optionsFunc=function(){}
let del_optionsFunc=function(){}
let hide_buildFormFrameFunc=function(){}

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
   
    let cqcObj={}
    add_quesFunc=function(count, names_array, types_array, options_array,cqc_array){
        let templateQuestions =get_templateQuestions(count,names_array, types_array, options_array,cqc_array);
         accordionBody.insertAdjacentHTML('beforeend',templateQuestions)
        //This function triggers change on select quesType
        let resSelectArray = getIdsStartingWith('resSelect_')
        resSelectArray.forEach((itemID, index) => {
            let ele= document.getElementById(itemID)
            let changeEvent=new Event ("change")
            ele.dispatchEvent(changeEvent)
        });
        //get all the cqcSelect save them into an object 
        let cqcSelectArray = getIdsStartingWith("cqcSelect_")
         cqcSelectArray.forEach((itemID, index) => {
            let ele= document.getElementById(itemID)
            if ((ele.value > 0) && (ele.value < 10)){
                cqcObj[itemID]=ele.value
            }
                
        });
         

    }
           
    del_quesFunc=function(id){
        let bodyMsg="Do you want to remove this question ? <br>";
        let btns=deleteBtn + closeBtn
        show_modal("Warning ", bodyMsg, btns);
        modalDialogID.classList.remove('modal-lg');
        modalDialogID.classList.add('modal-md');
      
        delete_modalFunc=function(){
            let mainDoc= document.getElementById("main_" + id)
            if (mainDoc !== null) {
                mainDoc.outerHTML = "";
            } 
           
            hide_modal()
        }
    }
    
    reset_quesFunc=function(){
        let bodyMsg="Do you want to reset the form by replacing all questions with the ones from the system ? <br>";
        let btns=resetBtn + closeBtn
        show_modal("Warning ", bodyMsg, btns);
        modalDialogID.classList.remove('modal-lg');
        modalDialogID.classList.add('modal-md');

        let reset_ajaxQuesFunc=function(){
            show_spinner()
            const asyncPostCall = async () => {
                let post_data={
                    respTypeID:respTypeID
                }
                
                try {
                    const response = await fetch(reset_formURL, {
                        method: 'POST',
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json, text-plain, */*",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": token
                        },
                        body: JSON.stringify(post_data)
                    });
                    //const data = await response.json();
                    let data = await response.text(); //server returns text
                    //document.documentElement.innerHTML = data;
                    // Update the content of the target element
                    //Here we aonly pass the variables no need for the form
                    document.getElementById('reset_formID').innerHTML =data;
                    //we need to extract the form details which has changed
                    //the other fields do not change
                    let obj = JSON.parse(data);
                    formDetails= obj.forms
                    //clear the accordion
                     accordionBody.innerHTML = ''
                    show_quesFunc()
                    show_alertInfo("Forms has been successfully reset")
                    // enter you logic when the fetch is successful
                    //alert(JSON.stringify(data));
                    //show_alertInfo("Dashboard  Successfully updated")
                    //alert(JSON.stringify(formDetails))
                    hide_spinner()
                                              
                } catch(error) {
                    // enter your logic for when there is an error (ex. error toast)
                    alert(error);
                    hide_spinner()
                } 
            }    
            asyncPostCall()
        }
        
        reset_modalFunc=function(){
            reset_ajaxQuesFunc()          
            hide_modal()
        }
    }

    quesTypeChangeFunc=function(id){
        //get the value
        let resID= 'resSelect_' + id
        let v= document.getElementById(resID).value 
        
        if (v==0){ // Nothingd
            let noResID= "noRes_" + id
            document.getElementById(noResID).style.display = 'block';
            document.getElementById('userText_'+ id).style.display = 'none';
            for (let element of document.getElementsByClassName("optionsMain__"+ id)){
                element.style.display="none";
            }
            let cqcID= "cqcSelect_" + id
            document.getElementById(cqcID).value=0 
            document.getElementById(cqcID).disabled=true 

        }else if (v==1){ //Text
            let noResID= "noRes_" + id
            document.getElementById(noResID).style.display = 'none';
            document.getElementById('userText_'+ id).style.display = 'block';
            for (let element of document.getElementsByClassName("optionsMain__"+ id)){
                element.style.display="none";
            }
            let cqcID= "cqcSelect_" + id
            document.getElementById(cqcID).value=0 
            document.getElementById(cqcID).disabled=true 

        }else if (v==2){ // Radio
            let noResID= "noRes_" + id
            document.getElementById(noResID).style.display = 'none';
            document.getElementById('userText_'+ id).style.display = 'none';
            for (let element of document.getElementsByClassName("optionsMain__"+ id)){
                element.style.display="block";
            }  
            let cqcID= "cqcSelect_" + id
            document.getElementById(cqcID).disabled=false 
            
             
        }
    }

    quesChangeFunc=function(id){
        let textarea = document.getElementById("quesText_"+id);
        let display = document.getElementById("btnHeading_"+id);
        display.innerHTML = textarea.value== "" ? untitled_const :   textarea.value
        //display.innerHTML = textarea.value;
    }  
   
    cqcChangeFunc=function(id){
        let cqcID =document.getElementById("cqcSelect_"+ id);
        let value=cqcID.value;
        let iFound=0;
        for (let key in cqcObj) {
            if ((cqcObj[key]==value)&& (key !== ("cqcSelect_"+ id))) {
               cqcID.value=cqcObj["cqcSelect_"+ id]
               show_alertDanger("Error: You cannot allocate the same CQC requirement to more than 1 question")
               iFound=1;
            };
        }
        //So, nothing was found so save it
        if (iFound==0)  cqcObj["cqcSelect_"+ id]=value;
    }
    
    add_optionsFunc=function(origRan){
        let template=get_templateOptions(origRan)
        let optionMain = document.getElementById('optionsMain_'+origRan);
        optionMain.insertAdjacentHTML('beforeend',template)
    }
    del_optionsFunc=function(origRan,newRan){
        let bodyMsg="Do you want to remove this option ? <br>";
        let btns=deleteBtn + closeBtn
        show_modal("Warning ", bodyMsg, btns);
        modalDialogID.classList.remove('modal-lg');
        modalDialogID.classList.add('modal-md');
        
        delete_modalFunc=function(){
            let id="options_" + origRan + "_X_"  + newRan
            document.getElementById(id)
            .outerHTML = "";
            hide_modal()
        }    
    }
    
    update_formFunc=function(){
        //alert(respTypeID)
        //return 0; 

        //Bring up the modal
        let bodyMsg="The existing form will be replaced. Do you wish to continue ?"
        let btns=updateBtn + closeBtn
        show_modal("Warning ", bodyMsg, btns);
        modalDialogID.classList.remove('modal-lg');
        modalDialogID.classList.add('modal-md');
        //** Start of update  */
        update_modalFunc=function(){
            hide_modal()
            let objArray=[]
            let isDirty_ever=0;
            //we have to save each  selector
            let quesIDs = getIdsStartingWith("quesText_")
            quesIDs.forEach(myFunction);
           function myFunction(item, index){
                 //get the quesName
                let name_="", cqc_=0,type_=0, options_=[]
                name_=document.getElementById(item).value
                let isDirty=0
                //clear
                let err_="err_" + item
                document.getElementsByClassName(err_)[0].innerHTML=""
                if (name_==""){
                    isDirty=1
                    isDirty_ever=1
                    document.getElementsByClassName(err_)[0].innerHTML="Please enter the question's title"
                }

                let id=item.replace("quesText_", "cqcSelect_")
                cqc_=document.getElementById(id).value
                id=item.replace("quesText_", "resSelect_")
                type_=document.getElementById(id).value
                options_=[]
                if (type_==2){ //get the text options
                    let ranID=item.replace("quesText_", "optionXs_")
                    let optionIDs = getIdsStartingWith(ranID)
                    //clear radio main
                    let err_=item.replace("quesText_", "err_optionsMain__")
                    document.getElementsByClassName(err_)[0].innerHTML=""
                    //radio selected but no options yet
                    if (optionIDs.length<2){
                        isDirty=1
                        isDirty_ever=1
                        document.getElementsByClassName(err_)[0].innerHTML="Please add at least 2 Options"
                    }

                    for(let i=0; i< optionIDs.length;i++){
                        let value=document.getElementById(optionIDs[i]).value
                        //clear
                        let err_="err_" +   optionIDs[i]  
                        document.getElementsByClassName(err_)[0].innerHTML=""
                        //duplicates not allowed
                        if (options_.indexOf(value) > -1){
                            isDirty=1
                            isDirty_ever=1
                            document.getElementsByClassName(err_)[0].innerHTML="Please re-select.Duplicates not accepted"                 
                        }
                        options_.push(value)
                        //blank
                        if (value==""){
                            isDirty=1
                            isDirty_ever=1
                            document.getElementsByClassName(err_)[0].innerHTML="Please re-select. Blanks not accepted"                 
                        }
                    }
                }
                let obj={
                    quesID:index+1,
                    quesName:name_,
                    CQCid:cqc_,
                    quesTypeID:type_,
                    quesAttrib: JSON.stringify(options_),
                    companyID:companyID,
                    responseTypeID:respTypeID
                }
                //Spotcheck
                if (respTypeID==3){
                    delete obj.CQCid;
                    delete obj.responseTypeID;
                }
                objArray.push(obj) 
                let ranID=item.replace("quesText_", "err_btnHeading_") 
                document.getElementsByClassName(ranID)[0].innerHTML=""
                if (isDirty){
                    document.getElementsByClassName(ranID)[0].innerHTML="Problems with this question"    
                }
            
            }
            //alert(JSON.stringify(objArray));
            //return 0;
            show_spinner()
            
            const asyncPostCall = async () => {
             
                if (isDirty_ever){
                    show_alertDanger("Error: " + formTitle +  " not updated. Please correct the errors")
                    hide_spinner()
                    return 0;
                }
                
                let post_data={
                    data:objArray,
                    responseTypeID:respTypeID
                }    
                  
                try {
                    const response = await fetch(update_formURL, {
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
                        //alert(JSON.stringify(data.data));
                        hide_spinner()
                        show_alertInfo("Success: "  + formTitle + "  has been updated")
                    }catch(error) {
                        // enter your logic for when there is an error (ex. error toast)
                        alert(error);
                        hide_spinner()
                    } 
            }    
            asyncPostCall()
        } 
        //*** end of update */
        
    }

     
    hide_buildFormFrameFunc=function(){
        function nullifyFrameStyle() {
            var frame = document.getElementById('scrollableFrame');
            frame.style.cssText = 'max-height: none; overflow-y: visible; border-bottom: none; padding: 0;';
        }
        
        // Function to restore the frame style
        function restoreFrameStyle() {
            var frame = document.getElementById('scrollableFrame');
            frame.style.cssText = 'max-height: 350px; overflow-y: auto; overflow-x:hidden; border-bottom: 1px solid #ccc; padding: 10px;';
        }

        var checkbox = document.getElementById('buildFormFrameID');
        if (checkbox.checked) {
            nullifyFrameStyle()
         }else{
            restoreFrameStyle()
         }
    }




    //This is the JS function called after the page loads. It fills all the 
    //fields and select options and sets their values, Note that 
    //add_serviceUserSurveyQuesFunc function triggers a select change
    
    //*** All these are wrapped in a function so thath reset can use it */
    let show_quesFunc=function(){
        let quesNameArray=[]
        let quesTypeIDArray=[]
        let quesAttribArray=[]
        let cqcArray=[]
        let  myFunction= function(item, index) {
            quesNameArray[index]=item.quesName
            quesTypeIDArray[index]=item.quesTypeID
            quesAttribArray[index]=item.quesAttrib
            cqcArray[index]=item.CQCid
        }
        formDetails.forEach(myFunction)
        let formCount=formDetails.length
        if (formCount == 0) {
            document.getElementById('noQuesID').style.display = 'block';
        } else {
            document.getElementById('noQuesID').style.display = 'none';
        }
        add_quesFunc(formCount, quesNameArray, quesTypeIDArray,quesAttribArray,cqcArray);
    }
    
    show_quesFunc()   


                
})     




