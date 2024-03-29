"use strict";
let get_templateQuestions=function(){}
let get_templateOptions=function(){}
const untitled_const = "Untitled Question"

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

    get_templateOptions=function(randomNo, text1){
        let newRan=getRandomToken(10);
        let id="options_" + randomNo + '_X_' + newRan; 
        let id_t="optionXs_" + randomNo + '_X_' + newRan + 't'
        if (!text1) {
            text1=" "
        }     
        
        function get_options(text1){ 
            let options='<option value=""></value>';
            for (let i=0; i < optionsArray.length; i++){
                let selected="";
                if (optionsArray[i].options==text1) selected='selected'; 
                options += ('<option ' + selected + ' value= "' + optionsArray[i].options + '" >'     +  optionsArray[i].options +  ' </option> <br>');
            }
            return options
        }    


        let templateOptions='<div class="d-flex" id="' + id + '" >' +
                '<div class="col-md-10 form-floating d-inine-block">' +
                    '<select name="' + id_t + '" class= "form-select"   aria-label="Floating label select"   ' +  ' id= "' + id_t + '">' + 
                        get_options(text1) +
                    '</select>' +
                    '<label for="' + id_t + '">Select an Option</label>' +
                    '<span class="text-danger err_' + id_t +  '"  >  </span>' + 
                 '</div>' +  

                '<div classs="col-md-1" style="padding-top:15px; padding-left:20px">'+
                '<i data-bs-toggle="tooltip" data-bs-placement="top"   title="remove option"    onClick="del_optionsFunc(' +  "'" +  randomNo + "'"  + ',' +  "'" +  newRan + "'"   +   ' )"    role="button" class="ri-close-line text-danger fs-4 text"></i>' +
            '</div>' +  
        '</div>' ;
         return templateOptions;
    }
    
    let display_options=function(randomNo, quesType_, options_){
        if (quesType_ ==2){
            let options=JSON.parse(options_)
            let count=options.length
            let templateOptions=""
            for (let i=0; i< count; i++){
                templateOptions += get_templateOptions(randomNo,options[i])
            }
           
            return templateOptions
        } 

        return '' 
    }

    let get_templateBody=function(randomNo, names_, quesType_, options_, cqc_){

        let get_cqcOptions=function(cqc_){
            let cqcOptions=""
            for (let i=0; i < cqcOptionsArray.length; i++){
                let selected="";
                if (cqcOptionsArray[i].CQCid==cqc_) selected='selected'; 
                cqcOptions += ('<option ' + selected + ' value= "' + cqcOptionsArray[i].CQCid + '" >'     +  cqcOptionsArray[i].CQCtext +  ' </option> <br>');
            }
            return cqcOptions
        }
        
        let get_quesOptions=function(quesType_){
            let quesOptions=""
            for (let i=0; i < quesOptionsArray.length; i++){
                let selected="";
                if (quesOptionsArray[i].quesTypeID==quesType_) selected='selected'; 
                quesOptions += ('<option ' + selected + ' value= "' + quesOptionsArray[i].quesTypeID + '" >'     +  quesOptionsArray[i].quesTypeName +  ' </option> <br>');
            }
            return quesOptions
        }

        //console.log(randomNo)
        //SpotCheck
        let hideCQC="";
        if (respTypeID==3){
            hideCQC="d-none"
        }
        let template_inside=""+    
        '<div class="row align-items-start p-1 shadow-lg p-4 mb-4 bg-white">' +
            '<div class="col-md-4 "> ' +
                '<div class="form-floating">' +
                    '<textarea class="form-control" placeholder="Ask a question" id="quesText_' + randomNo +   '" style="height: 70px"    onChange="quesChangeFunc(' +  "'" + randomNo + "'" + ' )" >' + names_  + '</textarea> ' +
                    '<label for="quesText_' + randomNo + '">Question</label>' +
                    '<span class="text-danger err_quesText_' +  randomNo +  '"  >  </span>' + 
                '</div>' +
            '</div>' +     
            '<div class="col-md-3 ' + hideCQC + ' ">' +
                '<div class="form-floating">' +
                    '<select class="form-select" id="cqcSelect_' + randomNo + '" aria-label="Floating label select" onChange="cqcChangeFunc(' +  "'" + randomNo + "'" + ' )" >' +
                    get_cqcOptions(cqc_) +
                    '</select>' +
                    '<label for="cqcSelect_' + randomNo + '">Select CQC Requirement</label>' +
                    '<span class="text-danger err_cqcSelect_' +  randomNo +  '"  >  </span>' + 
                '</div>' +
            '</div>' +    
            '<div class="col-md-2 ">' +
                '<div class="form-floating">' +
                    '<select class="form-select" id="resSelect_' + randomNo + '" aria-label="Floating label select" onChange="quesTypeChangeFunc(' +  "'" + randomNo + "'" + ' )" >' +
                        get_quesOptions(quesType_)  +
                    '</select>' +
                    '<label for="resSelect_' + randomNo + '" >Response Type</label>' +
                    '<span class="text-danger err_resSelect_' +  randomNo +  '"  >  </span>' + 
                '</div>'+
            '</div>' +    
            '<div class ="col-md-3 ">' +
                '<div class="text-nowrap mt-3 bg-white text-black" style="width: 8rem; display:none;" id="noRes_' +  randomNo + '">' +
                    '<p>' +
                        "<label>For user's information only</label>" +
                    '</p>' +
                '</div>' +
                '<div class="form-floating" id="userText_' + randomNo + '" style="display:none">' +
                    '<input type="text" class="form-control"  placeholder="Sample Text"  disabled> ' +
                    '<label for="userText_' + randomNo + '">User enters some text</label>' +
                '</div>' +
                '<div id="optionsMain_' + randomNo + '" style="display:none" class="optionsMain__' +  randomNo + '">' +
                    display_options(randomNo, quesType_, options_) +
                '</div>' +
    
                '<div class="row pt-1 mt-1 optionsMain__' + randomNo + '" style="display:none">' +
                '<span class="text-danger err_optionsMain__' +  randomNo +  '"  >  </span>' + 
                   '<hr class="bg-danger border-2 border-top border-primary ms-3">' +
                    '<div class="text-left">' +
                        '<button class="btn btn-primary" type="button" onClick="add_optionsFunc('+ "'" +  randomNo +  "'" + ')">Add Options</button>' +
                    '</div>' +
                '</div>' +
           '</div>' +
        '</div>'
        
        return template_inside;
    }
    
    
    
    get_templateQuestions=function(no_of_ques, names_array, types_array, options_array, cqc_array){
        let template_all="";
        let result=[] //returns the manin template  and randomNo Array
        //if any array is not defined please define it here
        if (!names_array){
            var names_array=[], types_array=[], options_array=[], cqc_array=[]
            for(let i=0; i < no_of_ques; i++){
                names_array[i]=""
                types_array[i]=0
                options_array[i]=""
                cqc_array[i]=0
            }
        }
        let randomNoArray=[]
        for(let i=0; i < no_of_ques; i++){
            let randomNo=getRandomToken(10);  
            let names_ = names_array[i]== "" ? untitled_const:   names_array[i] 
             randomNoArray.push(randomNo)
            let template_inside=  get_templateBody(randomNo, names_array[i], types_array[i], options_array[i], cqc_array[i])
            let templateQuestions ="" +
            '<div class="accordion-item "   id="main_' + randomNo +  '" >  ' +
                   '<h5 class="accordion-header p-0 m-0" id="heading_' + randomNo + '"> ' +
                        '<button class="accordion-button collapsed accordion-button border border-light ps-2 pe-2 mt-3" type="button"' +
                            ' data-bs-toggle="collapse" data-bs-target="#collapse_' + randomNo + '"' +
                            ' aria-expanded="false" aria-controls="collapse_' + randomNo + '"> ' +
                            '<span id= "btnHeading_' + randomNo + '">' +   names_  + '</span> ' +
                            ' &nbsp; <span class="text-danger fs-6 err_btnHeading_' +  randomNo +  '"  >  </span>' + 
                        '</button>' +
                    '</h5> ' +
                    '<div id="collapse_' + randomNo + '" class="accordion-collapse collapse " ' +
                        'aria-labelledby="heading_' + randomNo + '" data-bs-parent="#accordionBodyID">' +
                        '<div class="accordion-body">' +
                            template_inside +
                            '<div class="row d-inline-block ms-2 text-primary">' +
                                'Remove this question '  + '<i  id=" remove_' + randomNo + '"   role="button" onClick="del_quesFunc(' + "'" + randomNo + "'"  + ')" class="text-danger  bi bi-x-square fa-3x" style="font-size: 1.25rem;"  ></i>' +
                            '</div>' +
                        '</div>' +
                    '</div> ' +
             '</div>';
            template_all +=templateQuestions
        }
        return  template_all;
    }

})    