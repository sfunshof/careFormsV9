"use strict";
let confirmCarerFunc=function(){}
let assignServiceUserFunc=function(){}
let cancelSelectedServiceUserFunc=function(){}
let selectServiceUserFunc=function(){}
let toggleNextIconFunc=function(){}
let togglePrevIconFunc=function(){}
let toggleShowNextIconFunc=function(){}
let toggleShowPrevIconFunc=function(){}
let displaySelectedInfoFunc=function(){}
let prevIconFunc=function(){}
let nextIconFunc=function(){}
let radioClickFunc=function(){}
let textAreaClickFunc=function(){}
let otherTextAreaClickFunc=function(){}
let submitSpotCheckFunc=function(){}
let rateStarFunc=function(){}
let reportFunc=function(){}
let reportRadioFunc=function(){}
let initCarerPageFunc=function(){}
let showDurationSelectFunc=function(){}
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

function showSpinner() {
    const spinnerElement = document.getElementById('spinner');
    spinnerElement.style.display = 'block'; // Show the spinner
}
function hideSpinner() {
    const spinnerElement = document.getElementById('spinner');
    spinnerElement.style.display = 'none'; // Hide the spinner
}

function showSpinner_modal() {
   const spinnerElement = document.getElementById('spinner_modal');
   spinnerElement.style.display = 'block'; // Show the spinner
}
function hideSpinner_modal() {
   const spinnerElement = document.getElementById('spinner_modal');
   spinnerElement.style.display = 'none'; // Hide the spinner
}


  
ready(function() {
    let showCarerPageFunc=function(){
        root.showSelectCarersPage=true
        root.showSelectServiceUsersPage=false
        root.showSpotCheckPage=false
        root.showSuccessSavedPage=false
        toggleShowPrevIconFunc(0)
        toggleShowNextIconFunc(0)
    } 

    let showServiceUserPageFunc=function(){
        root.showSelectCarersPage=false
        root.showSelectServiceUsersPage=true
        root.showSpotCheckPage=false
    }

    let showSpotCheckPageFunc=function(){
        root.showSelectCarersPage=false
        root.showSelectServiceUsersPage=false
        root.showSpotCheckPage=true
    }
    
    function warning(message){
      Fnon.Hint.Danger(message, {
        callback:function(){
        // callback
        },
        position:'center-center',
        animation:'slide-bottom', 
      });
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
          //alert(status)
          if (status==1){
              //please select an option
              warning("Please select an option")  
              return -1
          }else if (status==0){
              //please fill the text
              warning("Please enter some text") 
              return -1
          }else if (status==-1){
              //Nothing to fill
              spotCheckResult[current_index]=[] 
          } 
           
      }
    }

    function next() {
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
 
    }

    function previous() {
      // Get all div elements with ids starting with 'div'
      var divs = document.querySelectorAll('[id^="div"]');

      toggleNextIconFunc(1)
          
      // Check if div0 is visible
      if (divs[0].style.display !== 'none') {
          // If div0 is visible, return 0
          Fnon.Ask.Danger({
            title:'Warning!!!',
            message:'Going past this page will result in data losss <br> Do you wish to continue?',
            btnOkText: 'Yes',
            btnOkBackground: '#dc3545',
            btnOkColor: '#fff',
            btnCancelText: 'No',
            btnCancelColor: '#fff',
            btnCancelBackground: '#808080',
            callback:(result)=>{
                // callback
                if (result) {
 
                  showServiceUserPageFunc()

                }
            }
        });
         
          
          return 0;
      }
      
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
      }
  }
  

    let showSpotCheckQuesFunc=function(direction){
        if (direction==1){
          next()
        }else if (direction==-1){
          previous()
        }
          
    }

    confirmCarerFunc=function(id, name){
      // component methods accessible on return value from mount()
      
           
      function getDateForCareID(carerID) {
        for (let i = 0; i < my2AssociativeArray.length; i++) {
            if (my2AssociativeArray[i].carerID == carerID) {
                return my2AssociativeArray[i].latest_date;
            }
        }
        return "Nill";
      }
      let date_ =getDateForCareID(id)
   
      Fnon.Ask.Primary({
          title:'Confirm Spot Check',
          message:'Do you wish to spot check: ' + name + "<br> Lastest spot check on " + date_,
          btnOkText: 'Yes',
          btnOkBackground: '#0d6efd',
          btnOkColor: '#fff',
          btnCancelText: 'No',
          btnCancelColor: '#fff',
          btnCancelBackground: '#808080',
          callback:(result)=>{
              // callback
              if (result) {
                 //hide the select carers and show the start spot check
                 root.showSelectCarersPage=false
                 root.showDisplaySelectedInfoPage=false
                 root.showSelectServiceUsersPage=true
                 let carer = {name:name, id:id};
                 store.set('selectedCarer',carer );
                 store.remove('selectedServiceUser');    
                 toggleShowPrevIconFunc(1)
                 toggleShowNextIconFunc(1)
                 togglePrevIconFunc(1)
                  toggleNextIconFunc(0)

              }else{
                store.remove('selectedCarer');
              }  
          }
      });
    }
   
    assignServiceUserFunc=function(id,name){
        let serviceUser = {name:name, id:id};
        store.set('selectedServiceUser',serviceUser );
        //show the selectedInfo
        root.showDisplaySelectedInfoPage=true 

    }
    
    selectServiceUserFunc=function(){
        //if the service user has not been selected state this
        if (store.get('selectedServiceUser')){
           //OK
           displaySelectedInfoFunc()
        }else{
          Fnon.Hint.Danger('Please select the service user', {
              animation: 'slide-bottom', 
              callback:function(){
                // callback
                toggleNextIconFunc(0)
              }
          });
        }
     }

    cancelSelectedServiceUserFunc=function(){
        store.remove('selectedServiceUser');
        toggleNextIconFunc(0)
        //hide the selected info
        root.showDisplaySelectedInfoPage=false
      
    }
    
    const prevIcon = document.getElementById('prevId').querySelector('i');
    const nextIcon = document.getElementById('nextId').querySelector('i');
    
    togglePrevIconFunc=function(isEnable) {
        if (isEnable){
            if (prevIcon.classList.contains('disabled-icon')) {
                prevIcon.classList.remove('disabled-icon');
            }
        }else { //disabled
              prevIcon.classList.add('disabled-icon');
        }
    }

    toggleNextIconFunc=function(isEnable) {
        if (isEnable){
            if (nextIcon.classList.contains('disabled-icon')) {
                nextIcon.classList.remove('disabled-icon');
            }
        }else { //disabled
              nextIcon.classList.add('disabled-icon');
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
    
    displaySelectedInfoFunc=function(){
        let carer=store.get('selectedCarer')   
        let serviceUser=store.get('selectedServiceUser')
                        
        root.selectedCarer=carer.name
        root.selectedServiceUser=serviceUser.name
        toggleNextIconFunc(1)
    }
    
    prevIconFunc=function(){
      let pageId= root.get_currentPage()
      if (prevIcon.classList.contains('disabled-icon')) {
         return 0;
      }
      switch(pageId) {
        case 2:
          // code block
          showCarerPageFunc()
          break;
        case 3:
          // code block
          //1st check if the 1st page of showSpotCheck
          //Move within showSpotCheckPage
          showSpotCheckQuesFunc(-1)  
          break;
        default:
          // code block
      }
    }
    
    nextIconFunc=function(){
      let pageId= root.get_currentPage()
      if (nextIcon.classList.contains('disabled-icon')) {
         return 0;
      }  
      switch(pageId) {
        case 2:
          // code block
          showSpotCheckPageFunc()
          break;
        case 3:
          //Move within showSpotCheckPage
          showSpotCheckQuesFunc(1)  
          break;
        default:
          // code block
      }
    } 
    
    submitSpotCheckFunc=function(){
        if (spotCheckReview==0){
          warning("Please select a star rating") 
          return -1
        }
        let carer=store.get('selectedCarer')
        let serviceUser=store.get('selectedServiceUser')
        showSpinner()
        const post_data={
            carerID : carer.id,
            serviceUserID: serviceUser.id,
            spotCheckData: spotCheckResult,
            spotCheckReview: spotCheckReview
        }
       fetch(save_mobileSpotCheckURL, {
          method: 'POST',
          body: JSON.stringify(post_data),
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": token
        },
      })
      .then(response => {
          hideSpinner();
          if (response.ok) {
              root.showSuccessSavedPage=true
              setTimeout(() => {
                   showCarerPageFunc()
                  //console.log('x is now false');
              }, 4000);
            return response.json();
          } else {
              throw new Error(`Error: ${response.status} ${response.statusText}`);
          }
      })
      .then(data => {
          hideSpinner();
          //console.log('Response from server:', data);
          // Handle the response data as needed
      })
      .catch(error => {
          hideSpinner();
          //console.error('Fetch error:', error);
      });

      //alert(JSON.stringify(spotCheckResult))
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
      // Add the input value to the array
      let otherText="Others: " + inputValue

      spotCheckResult[pos].push(otherText);
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

    reportFunc=function(){
      const modalEl = document.getElementById('staticBackdrop2');
      const bsModal = new bootstrap.Modal(modalEl);
      bsModal.show();
    }
    logoutFunc=function(){
        Fnon.Ask.Primary({
            title:'Logout',
            message:'Do you wish to logout',
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

    initCarerPageFunc=function(){
      Fnon.Ask.Primary({
        title:'Start Up Page',
        message:'Going to the Start will clear all data. Do you wish to continue?',
        btnOkText: 'Yes',
        btnOkBackground: '#0d6efd',
        btnOkColor: '#fff',
        btnCancelText: 'No',
        btnCancelColor: '#fff',
        btnCancelBackground: '#808080',
        callback:(result)=>{
            // callback
            if (result) {
               showCarerPageFunc()
            }
        }
     });
    }




     
    rateStarFunc= function(rating) {
        // Change color of stars up to the selected rating
        const stars = document.querySelectorAll('.star');
        for (let i = 0; i < rating; i++) {
            stars[i].classList.add('yellow');
        }
        // Reset color of stars beyond the selected rating
        for (let i = rating; i < 5; i++) {
            stars[i].classList.remove('yellow');
        }
        // Update selected rating
        spotCheckReview = rating;
    }
    
    //Shows selectedduration hides table and button
    showDurationSelectFunc=function(){
      let durationBtn = document.getElementById("durationBtnID");
      let durationSelect = document.getElementById("durationSelectID");
      let durationTable=document.getElementById('durationTableID');
  
      // Hide the button
      durationBtn.style.display = "none";
      durationTable.style.display="none";

      // Show the duration select with sliding effect
      durationSelect.style.display = "block";
      durationSelect.style.height = durationSelect.scrollHeight + "px";
      
      // Add transition for smooth sliding effect
      durationSelect.style.transition = "height 3.5s ease-in-out";
      
      // After a short delay, set the height to auto to allow for dynamic content
      setTimeout(function() {
          durationSelect.style.height = "auto";
      }, 50);
  
    }
    
    //Hides selected duration: shows btn and table
    reportRadioFunc=function(strvalue,Mnths){
        
      function update_otherReports(strvalue){
          let durationBtn = document.getElementById("durationBtnID");
          let durationSelect = document.getElementById("durationSelectID");
          let durationTable=document.getElementById('durationTableID');
          let caption = document.getElementById('reportCaptionID');
  
          // Change the caption text
          caption.textContent = strvalue +  ' Spot Checks';
          
          // Slide up the duration select with transition
          durationSelect.style.height = durationSelect.scrollHeight + "px";
          durationSelect.style.transition = "height 1.5s ease-in-out";
          
          setTimeout(function() {
              durationSelect.style.height = "0";
          }, 50);
      
          // After transition, hide the duration select and show the button
          setTimeout(function() {
              durationSelect.style.display = "none";
              durationBtn.style.display = "block";
              durationTable.style.display="block";
          }, 50);
       }

        function show_mobile_SpotCheckData(Mnths){
            showSpinner_modal()
            const legend = document.querySelector('legend');
            // Update the legend text with the selected value
            legend.textContent = 'Selected Duration: ' + strvalue;

            const asyncMobileCall = async () => {
            let post_data={
                  selectedMnth:Mnths
            }
            try {
                  const response = await fetch(show_mobileSpotCheckDataURL, {
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
                  document.getElementById('durationTableID').innerHTML =data;
                  update_otherReports(strvalue)
                  // enter you logic when the fetch is successful
                  //alert(JSON.stringify(data));
                  //show_alertInfo("Dashboard  Successfully updated")
                  
                  hideSpinner_modal()
                                            
              } catch(error) {
                  // enter your logic for when there is an error (ex. error toast)
                  //alert(error);
                  window.location.href = loginURL;   //session dead
                  hideSpinner_modal()
              } 
          }    
          asyncMobileCall()
      }
      
      show_mobile_SpotCheckData(Mnths)
  
    }
    //Initialise with the 3 months
    //reportRadioFunc('3 Months',3)

    //hide the two icons until such a time
     toggleShowPrevIconFunc(0)
     toggleShowNextIconFunc(0)
   
  })  