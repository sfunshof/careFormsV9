"use strict";
let confirmCarerFunc=function(){}
let assignServiceUserFunc=function(){}
let cancelSelectedServiceUserFunc=function(){}
let selectServiceUserFunc=function(){}
let displaySelectedInfoFunc=function(){}
let submitSpotCheckFunc=function(){}
let rateStarFunc=function(){}
let reportFunc=function(){}
let reportRadioFunc=function(){}
let initCarerPageFunc=function(){}
let showDurationSelectFunc=function(){}


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


 
function showSpinner_modal() {
   const spinnerElement = document.getElementById('spinner_modal');
   spinnerElement.style.display = 'block'; // Show the spinner
}
function hideSpinner_modal() {
   const spinnerElement = document.getElementById('spinner_modal');
   spinnerElement.style.display = 'none'; // Hide the spinner
}


  
ready(function() {
    //Repeated
    let isInStandaloneMode = () => {
      return (
          "standalone" in window.navigator &&
          window.navigator.standalone
      );
    };

    let adjustCarerSelect=function(){
        var careSelectElement = document.getElementById('carerSelectID');
        // Check the condition and set max-height accordingly
        if (isInStandaloneMode()) {
            careSelectElement.style.maxHeight = '70vh';
        } else {
            careSelectElement.style.maxHeight = '60vh';
        }
    }

    let adjustServiceUserSelect=function(){
      var careSelectElement = document.getElementById('carerSelectID');
      // Check the condition and set max-height accordingly
      if (isInStandaloneMode()) {
          careSelectElement.style.maxHeight = '80vh';
      } else {
          careSelectElement.style.maxHeight = '60vh';
      }
  }
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
              position:'center-center',
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
 

    displaySelectedInfoFunc=function(){
        let carer=store.get('selectedCarer')   
        let serviceUser=store.get('selectedServiceUser')
                        
        root.selectedCarer=carer.name
        root.selectedServiceUser=serviceUser.name
        toggleNextIconFunc(1)
    }

    let showSpotCheckQuesFunc=function(direction){
        if (direction==1){
            next()
        }else if (direction==-1){
            previous()
        }
        
     }

    
    prevIconFunc=function(){
      let pageId= root.get_currentPage()
      //if (prevIcon.classList.contains('disabled-icon')) {
      //   return 0;
      //}
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
     // if (nextIcon.classList.contains('disabled-icon')) {
      //   return 0;
      //}  
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
        togglePrevIconFunc(0)
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
          togglePrevIconFunc(1)
          //console.error('Fetch error:', error);
      });

      //alert(JSON.stringify(spotCheckResult))
    }

    reportFunc=function(){
      const modalEl = document.getElementById('staticBackdrop2');
      const bsModal = new bootstrap.Modal(modalEl);
      bsModal.show();
    }


   previous_with_first_quespage=function(){
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
                  const myForm = document.getElementById('myForm');
                  myForm.reset()
                  showServiceUserPageFunc()
                  spotCheckResult=[] 
                }
            }
        });
                
        return 1;
      
   }

    initCarerPageFunc=function(){
      Fnon.Ask.Danger({
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
              const myForm = document.getElementById('myForm');
              myForm.reset()
              go_to_first_quespage()
              spotCheckResult=[]
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
          caption.textContent = "The last " + strvalue +  ' Spot Checks';
          
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
            legend.textContent = 'Updated Duration:  last ' + strvalue;

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
    
    adjustCarerSelect()
    //alert("Test")
  })  