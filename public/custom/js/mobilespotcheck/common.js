"use strict";
let toggleNextIconFunc=function(){}
let togglePrevIconFunc=function(){}
let toggleShowNextIconFunc=function(){}
let toggleShowPrevIconFunc=function(){}
let radioClickFunc=function(){}
let textAreaClickFunc=function(){}
let otherTextAreaClickFunc=function(){}
let prevIconFunc=function(){} //define in individual myjs.js
let nextIconFunc=function(){}  //defined in individual myjs.js
let next=function(){} //newly defined
let previous=function(){}  // newly defined
let previous_with_first_quespage=function(){}
let previous_after_first_quespage=function(){}
let go_to_first_quespage=function(){}
let showSpinner=function(){}
let hideSpinner=function(){}
let menuFunc=function(){}
let logoutFunc=function(){}
let spotCheckResult=[]
let spotCheckReview=0


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

showSpinner = function() {
    const spinnerElement = document.getElementById('spinner');
    spinnerElement.style.display = 'block'; // Show the spinner
}

hideSpinner = function() {
    const spinnerElement = document.getElementById('spinner');
    spinnerElement.style.display = 'none'; // Hide the spinner
}
 
ready(function() {
    const prevIcon = document.getElementById('prevId');
    const nextIcon = document.getElementById('nextId');

    togglePrevIconFunc=function(isEnable) {
        if (isEnable) {
            prevIcon.classList.remove('disabled-icon', 'text-secondary');
            prevIcon.classList.add('text-white');
            prevIcon.disabled=false
        } else { //disabled
            prevIcon.classList.add('disabled-icon', 'text-secondary');
            prevIcon.classList.remove('text-white');
            prevIcon.disabled=true
        }
    }

    toggleNextIconFunc=function(isEnable) {
        if (isEnable) {
            nextIcon.classList.remove('disabled-icon', 'text-secondary');
            nextIcon.classList.add('text-white');
            nextIcon.disabled=false
        } else { //disabled
            nextIcon.classList.add('disabled-icon', 'text-secondary');
            nextIcon.classList.remove('text-white');
            nextIcon.disabled=true
        }
    }

    toggleShowPrevIconFunc=function(isShow){
        if (isShow){
            if (prevIcon.classList.contains('d-none')) {
                prevIcon.classList.remove('d-none');
            }
        }else { //hide
                prevIcon.classList.add('d-none');
                
        }
    }    

    toggleShowNextIconFunc=function(isShow){
        if (isShow){
            if (nextIcon.classList.contains('d-none')) {
                nextIcon.classList.remove('d-none');
            }
        }else { //hide
                nextIcon.classList.add('d-none');
        }
    }
    
    radioClickFunc=function(pos, value){
        //alert(pos + '   ' + value)
  
        let id="box"+pos
        const othersId = document.getElementById(id);
        if (value=="Others"){
            othersId.style.display = 'block';
            let textId="other"+pos
            const textarea = document.getElementById(textId);
            if (textarea) {
                const text = value + ': '
                spotCheckResult[pos]=[text]
             }    
  
        }else{
            othersId.style.display = 'none';
            spotCheckResult[pos]=[value]
        }
    }

    textAreaClickFunc=function(pos){
        function captureText() {
            let id="text"+pos
            const textarea = document.getElementById(id);
            const userInput = textarea.value.trim(); // Remove leading/trailing spaces
            // Check if the user input is empty
            if (userInput === '') {
                return undefined;
            } else {
                return userInput;
            }
        }
        spotCheckResult[pos]=[captureText()]
  }
  
  otherTextAreaClickFunc=function(pos) {
    let id="other"+pos
    // Get the value of the textarea
    const inputValue = document.getElementById(id).value;
    if (!Array.isArray(spotCheckResult[pos])) {
        spotCheckResult[pos] = [];
    }
    // Add the input value to the array
    if (inputValue){
        let otherText="Others: " + inputValue
        spotCheckResult[pos].push(otherText);
    }
    
  }

  function validateResponse(current_index){
    function checkElementExistence(pos) {
      // Check if a radio button with the name "radio" exists and is visible
      function checkVisibility(pos) {
          let radioName = 'radio'+pos // Change this to whatever dynamic name you have
          let radio = document.querySelector('input[type="radio"][name="' + radioName + '"]');
          if (radio ) {
            // alert(JSON.stringify(radio))
              return  1
          } else {
              return 99
          }
      }
      //radio
      let radioStatus=checkVisibility(pos)
      //alert(radioStatus)

      if (radioStatus==1){
          return 1;
      }  
      // Check if an element with id "text1" exists
      let textElement = document.getElementById('text' + pos);
      if (textElement) {
          return 0;
      }

      // If neither "radio1" nor "text1" exists, return -1
      return -1;
    } 
    if (typeof spotCheckResult[current_index] === 'undefined') {
        //check the element
        let status= checkElementExistence(current_index)     
        if (status==1){
            //please select an option
            Fnon.Hint.Warning('Please select an option', {
                position:'center-center',
                callback:function(){
                // callback
                }
              });
            return -1
        }else if (status==0){
            //please fill the text
            Fnon.Hint.Warning('Please fill in some text', {
                position:'center-center',
                callback:function(){
                // callback
                }
              }); 
            return -1
        }else if (status==-1){
            //Nothing to fill
            spotCheckResult[current_index]=[] 
        } 
         
    }
  }

    next = function() {
        // Get all div elements with ids starting with 'div'
        var divs = document.querySelectorAll('[id^="div"]');
                
        // Check if the last div is visible
        if (divs[divs.length - 1].style.display !== 'none') {
            // If the last div is visible, return 0
            toggleNextIconFunc(0)
            return 0;
        }
        
        // Find the index of the currently visible div
        var currentIndex = -1;
        divs.forEach(function(div, index) {
            if (div.style.display !== 'none') {
                currentIndex = index;
            }
        });
        
        
        if (validateResponse(currentIndex)==-1){
            return -1
        }
        
        // Hide the currently visible div
        if (currentIndex !== -1) {
            divs[currentIndex].style.display = 'none';
            
            // Show the next div, or the first one if the last one was visible
            var nextIndex = (currentIndex + 1) % divs.length;
            divs[nextIndex].style.display = 'block';
        }
        // Check if the last div was hidden and now visible
        var lastIndex = divs.length - 1;
        if (divs[lastIndex].style.display === 'block' || divs[lastIndex].style.display === '') {
            // If the last div is now visible, return 0
            toggleNextIconFunc(0)
            return 0;
        }
        togglePrevIconFunc(1)

    }

    previous = function() {
        // Get all div elements with ids starting with 'div'
        var divs = document.querySelectorAll('[id^="div"]');
        // Check if div0 is visible
        if (divs[0].style.display !== 'none') {
            if (previous_with_first_quespage()==1){
                return 0
            }
        }        
        
        toggleNextIconFunc(1) //watch this for spot check
        // Check if div7 is visible
        if (divs[divs.length - 1].style.display !== 'none') {
            // If div7 is visible, hide it and show div6
            divs[divs.length - 1].style.display = 'none';
            divs[divs.length - 2].style.display = 'block';
            return;
        }
        
        // Find the index of the currently visible div
        var currentIndex = -1;
        divs.forEach(function(div, index) {
            if (div.style.display !== 'none') {
                currentIndex = index;
            }
        });

        // Hide the currently visible div
        if (currentIndex !== -1) {
            divs[currentIndex].style.display = 'none';
            
            // Show the previous div, or the last one if the first one was visible
            var prevIndex = (currentIndex - 1 + divs.length) % divs.length;
            divs[prevIndex].style.display = 'block';
            //on 1st page disable the prev button
            if (prevIndex==0){
                previous_after_first_quespage()
            }    
        }
        
    }
    go_to_first_quespage=function(){
        var divs = document.querySelectorAll('[id^="div"]');
        divs.forEach(function(div, index) {
            div.style.display = 'none'
        });
        divs[0].style.display='block'     
    }

    logoutFunc=function(){
        Fnon.Ask.Primary({
            title:'Logout',
            message:'Do you wish to logout? <br> Doing this will clear the data you entered',
            btnOkText: 'Yes',
            btnOkBackground: '#0d6efd',
            btnOkColor: '#fff',
            btnCancelText: 'No',
            btnCancelColor: '#fff',
            btnCancelBackground: '#808080',
            callback:(result)=>{
                // callback
                if (result) {
                  showSpinner()  
                  store.clear()
                  // Redirect to the spotcheck/mobile route
                  window.location.href = loginURL;  
                }
            }
         });
    }
    
    menuFunc=function(){
      Fnon.Ask.Primary({
          title:'Back to Menu',
          message:'Do you wish to go back to the main menu? <br> Doing this will clear the data you entered',
          btnOkText: 'Yes',
          btnOkBackground: '#0d6efd',
          btnOkColor: '#fff',
          btnCancelText: 'No',
          btnCancelColor: '#fff',
          btnCancelBackground: '#808080',
          callback:(result)=>{
              // callback
              if (result) {
                showSpinner()  
                //store.clear()
                // Redirect to the spotcheck/mobile route
                window.location.href = menuURL;  
              }
          }
       });
   }
 
})