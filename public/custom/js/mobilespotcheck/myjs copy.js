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
    let showCarerPageFunc=function(){
        Fnon.Ask.Danger({
            title:'Warning!!!',
            message:'You are about to go to the start page. All data will be lost <br> Do you wish to continue?',
            btnOkText: 'Yes',
            btnOkBackground: '#dc3545',
            btnOkColor: '#fff',
            btnCancelText: 'No',
            btnCancelColor: '#fff',
            btnCancelBackground: '#808080',
            callback:(result)=>{
                // callback
                if (result) {
                  root.showSelectCarersPage=true
                  root.showSelectServiceUsersPage=false
                  root.showSpotCheckPage=false
                  toggleShowPrevIconFunc(0)
                  toggleShowNextIconFunc(0) 

                }
            }
        });
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
    
    let showSpotCheckQuesNoFunc=function(showIndex) {
        const divs = document.querySelectorAll('[id^="div"]');
        divs.forEach(function(div) {
          // Hide all divs except the one specified
          if (div.id === 'div' + showIndex) {
              div.style.display = 'block';
          } else {
              div.style.display = 'none';
          }
       });
    }

    let showSpotCheckQuesFunc=function(pos,direction){
      let dir=direction

      if ( (pos > quesSize) && (direction==1)){
          return 0
      }
      if (pos == (quesSize) && (direction==1)){
          //too much
          dir=-1 //This is the max
          toggleNextIconFunc(0)         
      }else if (direction==-1){
          toggleNextIconFunc(-1)
      }
             
      alert(pos)
      if ((pos== 0) && (direction==1))   {
          showSpotCheckPageFunc() //This is there to 'initlise the page with hide and showfound in v-if
      }else if ((pos== 1) && (direction==-1)) { //1 not 0 because the index is 0 but was changed to 1 by dir
         //alert("pos = " + pos + ' diretion =' + direction) 
         showServiceUserPageFunc()
      }else if (pos > 0){
         let posX=pos 
         if (direction==-1) posX=pos-1
         showSpotCheckQuesNoFunc(posX)
         alert(" bad pos = " + spotCheckPos+ ' diretion =' + direction) 
      } 
      spotCheckPos=pos+dir
    }

    confirmCarerFunc=function(id, name){
      // component methods accessible on return value from mount()
      
       Fnon.Ask.Primary({
          title:'Confirm Spot Check',
          message:'Do you wish to spot check: ' + name + "<br> Lastest spot check on ",
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
          showSpotCheckQuesFunc(spotCheckPos,-1)  
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
          showSpotCheckQuesFunc(0,1)
          break;
        case 3:
          //Move within showSpotCheckPage
          showSpotCheckQuesFunc(spotCheckPos,1)  
          break;
        default:
          // code block
      }
    } 
    
    radioClickFunc=function(pos, value){
      //alert(pos + '   ' + value)
      let id=pos+"box"
      const othersId = document.getElementById(id);
      if (value=="Others"){
          othersId.style.display = 'block';
      }else{
          othersId.style.display = 'none';
      }
    }




    //hide the two icons until such a time
     toggleShowPrevIconFunc(0)
     toggleShowNextIconFunc(0)
   
  })  