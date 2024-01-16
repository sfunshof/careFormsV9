"use strict";
let  extractData_thenDisplay=function(){}
let too_manySMS=function(){}
let sms_toUsers=function(){}

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

  // ** FADE OUT FUNCTION **
    let fadeOut= function(element) {
        let opacity = 1;
        let timer = setInterval(function() {
            if (opacity <= 0.1) {
                clearInterval(timer);
                element.style.display = 'none';
        }
        element.style.opacity = opacity;
        opacity -= opacity * 0.1;
        }, 50);
      
   }
  

// ** FADE IN FUNCTION **
let fadeIn =function(element) {
    let opacity = 0;
    element.style.display = 'block';
    let timer = setInterval(function() {
      if (opacity >= 1) {
        clearInterval(timer);
      }
      element.style.opacity = opacity;
      opacity += 0.1;
    }, 50);
  }
  


ready(function() {
    //survey feedback table
    extractData_thenDisplay=function(url){
        //alert(url)
        //Function to get data
        async function getData(url) {
            // Storing response
            const response = await fetch(url);
            // Storing data in form of JSON
            var data = await response.json();
            //console.log(data);
            if (response) {
                //hide loader
                spinner.setAttribute('hidden', '');    
            }
            show_feedbackForm(data);
            //alert(JSON.stringify(data))
        }
        getData(url)
    }

    
    let show_feedbackForm=function(data){
        let text = "";
        let company_ = "<h4>"  +  data.companyName +  ' ' +  data.month  +  data.year +  data.respType  + ' </h4>'
        let  name_  = '<h5>' + data.fullName +  '</h5>'
        let  date_ =  '<h5>' +  '<span class="text-center">' +  data.datePosted + data.dateReceived  + '</span>' + '</h5>' ;
        let title= company_ + name_  + date_
        let quesNamesArray=JSON.parse(data.quesName);     //How many How can
        let quesTypeIDArray=JSON.parse(data.quesTypeID);  //1,2 0,0, 2
        let quesOptionsArray=JSON.parse(data.quesOptions); //Yes, No
        let responseTemp=JSON.parse(data.response); //{1:"one", 2:"two"} or [0ne, two]
        
        let printModalContentsToPDF =function(modalElement){
           // Clone the modal element
            let modalClone = modalElement.cloneNode(true);
            // Remove all buttons from the modal footer
            let footerButtons = modalClone.querySelectorAll('.modal-footer button');
            for (let i = 0; i < footerButtons.length; i++) {
                footerButtons[i].parentNode.removeChild(footerButtons[i]);
            }
            // Create a new jsPDF instance
            const doc = new jsPDF();
            // Add the modal clone to the PDF document
            doc.fromHTML(modalClone.outerHTML, 15, 15, {
                'width': 170
             });

            // Save the PDF document
            doc.save('modal.pdf');
        }

         printToPdf_modalFunc=function(){
            let modalElement = document.getElementById("myModal");
            printModalContentsToPDF(modalElement);
         }

        //convert object to array
        let responseArray=[]
        if (typeof responseTemp === 'object'){
            responseArray=Object.values(responseTemp); 
        }else{
            responseArray=responseTemp;   
        }
        
        let cardStart= '<div class="card">' +
                            '<div class="card-body">' +
                                '<form class="row g-3"> ' + 
                                    '<div class="col-12"> ' 
        let cardStop=                '</div>' +
                                  '</form>' +
                             '</div>' +
                         '</div>'           
        text +=  cardStart
        let pageCount=0
        let quesName=""
        let ulResponse="";
        let options=""
        let optionsArray=[];
        quesTypeIDArray.forEach(myFunction);
        //alert(JSON.stringify(quesOptionsArray))
        function myFunction(item, index) {
            if (item > 0){
                quesName= '<div class="mb-3">  <label class="form-label">'  +   (pageCount +1)     + ' . ' + quesNamesArray[index] + '</label>'
                text +=  quesName ;  
                ulResponse= '<ul class="ms-3  mb-0 pb-0 ">'
                options="" ;
                optionsArray=JSON.parse(quesOptionsArray[index]) 
                let bold1='';
                let bold2="";
                if (item==2){ //radio
                    bold1='<strong>';
                    bold2="</strong>";
                    //alert("item = " + item + " index= " + index)
                    //alert(JSON.stringify(optionsArray) + ' === ' + pageCount  +  ' item= '  + item)
                    for (let i=0; i < optionsArray.length; i++ ){
                      options += "<li> " +  optionsArray[i] + " </li>"
                   }
                } 
               // ulResponse += bold1 + responseArray[pageCount]  + bold2
                text += ulResponse;
                text += options +   bold1 + responseArray[pageCount]  + bold2 + '</ul> </div>'
                pageCount++
            }else{
                text +=  quesNamesArray[index] + "<br>"  
            }
            
        }
        text +=  cardStop
        let bodyMsg=text;
        let btns=pdfBtn +  closeBtn;
        show_modal(title, bodyMsg,btns)
    }
    
    too_manySMS=function(smsMsg,tel, status){
        spinner.setAttribute('hidden', '');
        let title=""
        if (status==3){ 
            title="Error: Too many emails already sent. Set Delivery to SMS to send";
        }else if (status==0) {
            title ="Error: Too many emails and sms already sent. Copy and send sms to " + tel ;
        }else if (status==1) {
            title ="Error: Too many sms sent to " + tel + " Copy and send sms manually";
        }else if (status==4) {
            title ="Error: Too many sms sent to " + tel + " Set Delivery to Email to send";
        }
            let bodyMsg =smsMsg;
        //copy to clipboard
        copyToClipboard=function(){
            navigator.clipboard.writeText(smsMsg)
            const mytt = document.getElementById('tt');
            const tooltip = new bootstrap.Tooltip(mytt,{
            trigger:'click'
            })
            
            tooltip.show();
            setTimeout(function () {
            tooltip.hide();
            }, 600)

        }
        let btns= copyBtn +   closeBtn
        show_modal(title, bodyMsg, btns )
    }
    
    sms_toUsers=function(userID,statusID, tel,responseTypeID,URLpath,date_of_interest,URLreload,sms,sentCount,sentEmailCount){
         const asyncPostCall = async () => {
            let post_data={
                userID:userID,
                statusID:statusID,
                 tel:tel,
                date_of_interest:date_of_interest,
                responseTypeID:responseTypeID,
                sms:sms,
                sentCount:sentCount,
                sentEmailCount:sentEmailCount
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
                   spinner.setAttribute('hidden', '');
                    //alert(JSON.stringify(post_data))
                    alertInfoID.innerHTML="Message successfully sent";
                    fadeIn(alertInfoID);
                    let  myTimeout = setTimeout(fadeOut(alertInfoID), 15000);
                    if (myTimeout)  window.location.replace(URLreload) ;
               
               } catch(error) {
                // enter your logic for when there is an error (ex. error toast)
                alert(error);
                spinner.setAttribute('hidden', '');
            } 
           
        }    
        asyncPostCall()
    }

}) 