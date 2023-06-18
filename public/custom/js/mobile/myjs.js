"use strict";
let prevFunc=function(){}
let nextFunc=function(){}
let submitFunc=function(){}
let radioClickFunc=function(){}
let textAreaFunc=function(){}
let divCount=0

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
    //This isa very silly approach. I should have use getElementById. 
    // make everything hidden except the fir div
    //These are all the div pls fake one incase we need to go up to 20 They are hard coded 
    const divs = [div0, div1, div2, div3,div4,div5, div6, div7, div8, div9, div10,div11,div12, div13,div14,div15, div16,div17, div18, div19, div20,
        div21,div22,div23,div24,div25, div26, div27, div28, div29, div30,
        div31,div32,div33,div34,div35, div36, div37, div38, div39, div40,
        div41,div42,div43,div44,div45, div46, div47, div48, div49, div50]
   
    submitBtnID.style.display = 'none';
    errMsgID.style.display= "none"
    let feedbackObj={}
    //remove primary bclass add rey class and make  disabled  
    const prevBtn = document.getElementById("prevBtnID");
    const nextBtn = document.getElementById("nextBtnID");
    const submitBtn = document.getElementById("submitBtnID");

    let prevBtnDisabled=function(){
        prevBtn.disabled = true; // disabling it by default
        prevBtn.classList.remove("btn-primary");
        prevBtn.classList.add("btn-secondary");
    }
    
    let nextBtnDisabled=function(){
        nextBtn.disabled = true; // disabling it by default
        nextBtn.classList.remove("btn-primary");
        nextBtn.classList.add("btn-secondary");
    }
    
    let submitBtnDisabled=function(){
        submitBtn.disabled = true; // disabling it by default
        submitBtn.classList.remove("btn-primary");
        submitBtn.classList.add("btn-secondary");
    }

    let prevBtnEnabled=function(){
        prevBtn.disabled = false; // disabling it by default
        prevBtn.classList.add("btn-primary");
        prevBtn.classList.remove("btn-secondary");
    }
    
    let nextBtnEnabled=function(){
        nextBtn.disabled = false; // disabling it by default
        nextBtn.classList.add("btn-primary");
        nextBtn.classList.remove("btn-secondary");
    }
    
    let submitBtnEnabled=function(){
        submitBtn.disabled = false; // disabling it by default
        submitBtn.classList.add("btn-primary");
        submitBtn.classList.remove("btn-secondary");
    }
    let slideDown = (target, duration=500) => {
        if(window.getComputedStyle(target).display == "block") return 0;

        target.style.removeProperty('display');
        let display = window.getComputedStyle(target).display;
    
        if (display === 'none')
          display = 'block';
    
        target.style.display = display;
        let height = target.offsetHeight;
        target.style.overflow = 'hidden';
        target.style.height = 0;
        target.style.paddingTop = 0;
        target.style.paddingBottom = 0;
        target.style.marginTop = 0;
        target.style.marginBottom = 0;
        target.offsetHeight;
        target.style.boxSizing = 'border-box';
        target.style.transitionProperty = "height, margin, padding";
        target.style.transitionDuration = duration + 'ms';
        target.style.height = height + 'px';
        target.style.removeProperty('padding-top');
        target.style.removeProperty('padding-bottom');
        target.style.removeProperty('margin-top');
        target.style.removeProperty('margin-bottom');
        window.setTimeout( () => {
          target.style.removeProperty('height');
          target.style.removeProperty('overflow');
          target.style.removeProperty('transition-duration');
          target.style.removeProperty('transition-property');
        }, duration);
      }
       let slideToggle = (target, duration = 500) => {
        if (window.getComputedStyle(target).display === 'none') {
          return slideDown(target, duration);
        } else {
          return slideUp(target, duration);
        }
    }

    let slideUp = (target, duration=500) => {
        target.style.transitionProperty = 'height, margin, padding';
        target.style.transitionDuration = duration + 'ms';
        target.style.boxSizing = 'border-box';
        target.style.height = target.offsetHeight + 'px';
        target.offsetHeight;
        target.style.overflow = 'hidden';
        target.style.height = 0;
        target.style.paddingTop = 0;
        target.style.paddingBottom = 0;
        target.style.marginTop = 0;
        target.style.marginBottom = 0;
        window.setTimeout( () => {
          target.style.display = 'none';
          target.style.removeProperty('height');
          target.style.removeProperty('padding-top');
          target.style.removeProperty('padding-bottom');
          target.style.removeProperty('margin-top');
          target.style.removeProperty('margin-bottom');
          target.style.removeProperty('overflow');
          target.style.removeProperty('transition-duration');
          target.style.removeProperty('transition-property');
          //alert("!");
        }, duration);
    }
    
    
    //disable the prev btn
    prevBtnDisabled()

    // your code here
    let paginateFunc=function(current, next){
        let currentID=divs[current]   
        let nextID=divs[next]     
        slideUp(currentID)
        slideDown(nextID)
    }
    
    let validateFunc=function(){
        //alert(quesTypeIDArray[divCount])
        if (quesTypeIDArray[divCount]==2){ //2 radio
            let radioName="radio" + divCount
            let validateRadio = document.getElementsByName(radioName)
            for(let i=0;i<validateRadio.length;i++){
                if(validateRadio[i].checked){
                    //Otheres check begin here
                    let optionName=validateRadio[i].value
                    let otherOptionText=""
                    if   (optionName.toUpperCase()== "OTHERS"){
                        let othersText="otherText"+divCount+"ID" 
                        let otherTextID = document.getElementById(othersText)
                        otherOptionText=otherTextID.value
                        otherOptionText="<br>" + otherOptionText
                    }    
                    //End of other checks
                    feedbackObj[divCount]=validateRadio[i].value +  otherOptionText
                    return 1 
                   break;
                }
            }
            //errMsgID.style.display='block'
            slideDown(errMsgID)
            return 0
        }else if (quesTypeIDArray[divCount]==1){ //1 text
            let textId="text" + divCount +"id"
            let validateTextId = document.getElementById(textId)
            if(validateTextId.value){
                feedbackObj[divCount]=validateTextId.value
                return 1 
            }
            //errMsgID.style.display='block'
            slideDown(errMsgID)
            return 0
        }    
        return 1
    }


    prevFunc=function(x){
        slideUp(errMsgID)
        let current=divCount
        //This is the 2nd to the last 
        if (divCount==(x+1)){
            prevBtnDisabled()
        }
        nextBtnEnabled()
        divCount--;
        if (divCount<x) {
           divCount=x
           return 0
        }   
        submitBtnID.style.display = 'none';
        paginateFunc(current,divCount) 
        
    }
    
    nextFunc=function(x){
       // errMsgID.style.display= "none"
        //slideUp(errMsgID)
        if (!validateFunc()) return 0
        prevBtnEnabled()
        let current=divCount
        if (divCount>= (x-1)) { 
            divCount=x-1
            return 0
        }   
        divCount++
        if (divCount==(x-1)) { 
            nextBtnDisabled()
            //submit btn comes alive
            submitBtnID.style.display = 'block';   
         
        }
        paginateFunc(current,divCount)
    }
    
    submitFunc=function(){
        const asyncMobilePostCall = async () => {
            submitBtnDisabled() 
            var post_data={
                userID:userID,
                responses:feedbackObj,
                quesName:quesNameArray,
                quesTypeID:quesTypeIDArray,
                CQCid:CQCidArray,
                quesOptions:quesOptionsArray,
                responseTypeID:responseTypeID,
                unique_value:unique_value
            }
            try {
                const response = await fetch(user_saveFeedbackURL, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token
                    },
                    credentials: "same-origin",
                    body: JSON.stringify(post_data)
                });
                const data = await response.json();
                // enter you logic when the fetch is successful
                window.location.replace(user_successSaveURL);
                submitBtnEnabled()
            
            } catch(error) {
                // enter your logic for when there is an error (ex. error toast)
                submitBtnEnabled() 
                alert(error)
            } 
        
        }    
        asyncMobilePostCall()
    }







    //On clicking the radio button, if it is others then unhide the text box for
    radioClickFunc=function(id, othersDivID){
        //errMsgID.style.display= "none"
        slideUp(errMsgID)
        let optionName=document.getElementById(id).value
        if   (optionName.toUpperCase()== "OTHERS"){
            document.getElementById(othersDivID).style.display = "block"
        }else{
            document.getElementById(othersDivID).style.display = "none"
        }
    }
    
    textAreaFunc=function(id, count){
        //this may be the last page so we better put in feedback obj 
        slideUp(errMsgID)
        let validateTextId = document.getElementById(id)
        feedbackObj[count]=validateTextId.value
    }

   
  });