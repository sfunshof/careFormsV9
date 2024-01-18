"use strict";
let yearChangeFunc=function(){}
let monthChangeFunc=function(){}
let pieChartArray_emp=[]
let pieChartArray_su=[]

const date_const="3033-01-01"
//alert(JSON.stringify(responseKeyArray))
//alert(JSON.stringify(CQCArray)) //5,1,0
//alert(JSON.stringify(quesOptionsArray['2023-04-01'][1])) //5,1,0

let draw_chart=function(idChart, dataValue,labelValue,labelCaption){
    //alert(JSON.stringify(dataValue))
    //alert(JSON.stringify(labelValue))
    var options = {
        series: dataValue,
        chart: {
            width: 380,
            type: 'pie',
        },
        dataLabels: {
            enabled: true
        },
        labels: labelValue,
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        noData: {
            text: "No Data Available",
            align: "center",
            offsetX:-100,
            offsetY:-100,
            verticalAlign: "middle",
            style: {
                color: "#CD5C5C",
                fontSize: "22px",
                fontFamily: "Helvetica, Arial, sans-serif"
            }
        }
    };
    
    let ele = document.getElementById(idChart)
    if (ele){
        let chart = new ApexCharts(document.getElementById(idChart), options);
        chart.render();
        return chart;
    }
    return -1
    
}


let get_dateDetails=function(date,id,responseKeyArray,responseValueArray){ //2024-05-01 quesNo
    if ((date==date_const)|| (responseValueArray.length==0)) {
        let result=[];
        result[0]=[];
        result[1]=[];
        return result
    }
    
    //console.log(JSON.stringify(responseValueArray));
    //console.log(JSON.stringify(date));
    
    let labelC =responseKeyArray[date]
    let dataC =responseValueArray[date]
    if (dataC.length==0){ //No one has submitted for this date
        let result=[];
        result[0]=[];
        result[1]=[];
        return result
    }
    
    
    let data=dataC[id] //['A,B,C]
    let label=labelC[id]
    let dataString=data.toString();
    let labelString=label.toString();
    let dataArray_str=dataString.split(",")
    let dataArray = dataArray_str.map(Number);
    let labelArray=labelString.split(",")
    let result=[];
    result[0]=dataArray;
    result[1]=labelArray
    return result
}
let get_quesOptionsArray=function(date_curr, date_prev, quesPos, quesOptionsArray){
    if (quesOptionsArray.length==0){
        return []
    }

    let options_curr_str=quesOptionsArray[date_curr][quesPos]
    let options_curr=JSON.parse(options_curr_str)
    if (date_prev==date_const){
        let options_prev=[]
        let optionsArray = [...new Set([...options_curr, ...options_prev])];
        return optionsArray
    }
    let options_prev_str=quesOptionsArray[date_prev][quesPos]
    let options_prev=JSON.parse(options_prev_str)
    let optionsArray = [...new Set([...options_curr, ...options_prev])];
    return optionsArray
}

let merge_arrays=function(data,labels, all_labels){
    // Convert Y to percentages
    let sum = data.reduce((a, b) => a + b, 0);
    let percentages = data.map(value => ((value / sum) * 100).toFixed(2));

    // Merge X, percentages, and Z into object Q
    let output = {};
    all_labels.forEach(key => {
        let index = labels.indexOf(key);
        output[key] = index !== -1 ? percentages[index] + "%" : "0%";
    })
    return output;
}
//let X = ["A", "B", "C"];
//let Y = [2, 3, 5];
//let Z = ["A", "B", "C", "D"];
//let Q=merge_arrays(Y,X,Z)
//alert(JSON.stringify(Q))
let draw_table=function(firstColumn, secondColumn,thirdColumn, headers, tableID){
    
    let table = document.getElementById(tableID);
    if (table){
        let tableBody = document.querySelector("#" + tableID + "  tbody");
    
        // Clear any existing rows
        tableBody.innerHTML = "";
        
        while (table.rows.length > 0) {
        table.deleteRow(0);
        }
        if (secondColumn.length==0){
            let headerRow = table.insertRow(0);
            headerRow.classList.add("table-primary");
            let noData="No Data Available"
            for (let i = 0; i < 3; i++) {
                let row = tableBody.insertRow();
                let cell = row.insertCell();
                cell.colSpan = 3;
                cell.style.textAlign = "center";
                if (i==2)  cell.innerHTML = noData;
                let cells = document.querySelectorAll("td");
                cells.forEach(cell => {
                    if (cell.innerHTML === noData) {
                        cell.classList.add("text-danger");
                    }
                });
            }
            return 0;
        }
    
        // Insert header row
        let headerRow = table.insertRow(0);
        headerRow.classList.add("table-primary",  "fw-bold");
        for (let i = 0; i < headers.length; i++) {
            let cell = headerRow.insertCell(i);
            cell.innerHTML = headers[i];
        }
        
        // Insert data rows
        for (let i = 0; i < firstColumn.length; i++) {
            let row = table.insertRow(i + 1);
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
        
            cell1.innerHTML = firstColumn[i];
            cell2.innerHTML = secondColumn[firstColumn[i]];
            //check 3rd column
            if (thirdColumn[firstColumn[i]] === undefined) {
                // myArray is undefined
            }else{
                cell3.innerHTML = thirdColumn[firstColumn[i]]; 
            }
            
        }
    }else{
        //
    }
    
}

let formatDate =function(dateString) {
    if (dateString==date_const){
        return 'No Previous Data'
    }
    let date = new Date(dateString);
    let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    let month = monthNames[date.getMonth()];
    let year = date.getFullYear();
    return `${month} ${year}`;
}
//Get the options for the date
let date_current=date_const
let date_previous=date_const

let set_charDateArray=function(chartDateArray){
    if (chartDateArray[0] === undefined) {
        // myArray is undefined
    }else{
       date_current=chartDateArray[0] 
    }
    if (chartDateArray[1] === undefined) {
        // myArray is undefined
    }else{
       date_previous=chartDateArray[1] 
    }
}    

let get_tableData=function(date_current,date_previous,i, responseKeyArray,responseValueArray, quesOptionsArray){
    let result_current=get_dateDetails(date_current,i, responseKeyArray,responseValueArray)
    let result_previous=get_dateDetails(date_previous,i,responseKeyArray,responseValueArray)
    let optionsArray=get_quesOptionsArray(date_current,date_previous,i,quesOptionsArray) //i =ques Position
    let dataArray_current = result_current[0]
    let labelArray_current= result_current[1]
    let dataArray_previous = result_previous[0] //["Yes", "No"]
    let labelArray_previous= result_previous[1]   //[3.5]
    let current_array= merge_arrays( dataArray_current,labelArray_current,optionsArray)
    let previous_array=[]
    if (date_previous !== date_const){
        previous_array= merge_arrays( dataArray_previous,labelArray_previous,optionsArray)
    }
   
    
    let header=["Response", formatDate(date_current), formatDate(date_previous)]
    let result=[]
    //for table
    result[0]=optionsArray
    result[1]=current_array
    result[2]=previous_array
    result[3]=header
    //for chat
    result[4]= dataArray_current
    result[5]=labelArray_current    
    return result
}

let set_setupGraphData=function(userType,quesTypeID,CQCArray,responseKeyArray,responseValueArray,pieChartArray, quesOptionsArray){
    for(let i=0; i< quesTypeID.length;i++){
        if ((CQCArray[i]>0)&&(quesTypeID[i]==2)){
            let idChart= "chart" + userType +i
            let idTable=  "table" + userType +i
             let result=get_tableData(date_current,date_previous,i,responseKeyArray,responseValueArray, quesOptionsArray)
            let optionsArray=result[0]
            let current_array=result[1]
            let previous_array=result[2]
            let header=result[3]
            draw_table(optionsArray, current_array, previous_array, header,  idTable)
            let caption="For ques"
            let dataArray_current=result[4]
            let labelArray_current =result[5] 
            //alert(JSON.stringify(dataArray_current)) //
            let myChart=draw_chart(idChart, dataArray_current,labelArray_current,caption)
            let chartObj={}
            //chartObj.date=date;
            chartObj.id=idChart;
            chartObj.myChart=myChart;
            pieChartArray.push(chartObj)
        }
    }
}

set_charDateArray(chartDateArray_su)
set_setupGraphData("_su_", quesTypeID_su,CQCArray_su,responseKeyArray_su,responseValueArray_su, pieChartArray_su, quesOptionsArray_su)

set_charDateArray(chartDateArray_emp)
set_setupGraphData("_emp_", quesTypeID_emp,CQCArray_emp,responseKeyArray_emp,responseValueArray_emp, pieChartArray_emp, quesOptionsArray_emp)


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
        
    //this returns 2023-04-01 
    function get_updatedData(id,userType){
                
        let updatePieChart =function (myChart, dataArray, labelArray){
            myChart.updateSeries(dataArray);
            myChart.updateOptions({
                labels: labelArray
            });   
        }
        //part of the update not first time draw
        let draw_allGraphs = function(pieChartArray, chartDateArray, responseKeyArray,responseValueArray, quesOptionsArray){
            let typeID=userType +id
            let mnID="month" +typeID;
            let yrID="year" + typeID; 
            let chID="chart" +typeID;
            let tbID="table" + typeID;
            let mn=document.getElementById(mnID).value
            if (mn < 10) mn=0+mn
            let yr=document.getElementById(yrID).value
            let date=yr + "-" + mn + "-01"
            let obj = pieChartArray.find(item => item.id === chID)
            
            let myChart=null ;
            if (obj)  myChart=obj.myChart
            
            //check if the date exist
            let dateIndex=chartDateArray.indexOf(date)
            if (dateIndex >= 0){
                //begin table update
                let dateIndex_prev=dateIndex+1
                let date_prev=date_const
                if (chartDateArray[dateIndex_prev] === undefined) {
                    // myArray is undefined
                }else{
                   date_prev=chartDateArray[dateIndex_prev] 
                }  
                  
                let result=get_tableData(date,date_prev,id,responseKeyArray,responseValueArray, quesOptionsArray)
                let optionsArray=result[0]
                let current_array=result[1]
                let previous_array=result[2]
                let header=result[3]
                draw_table(optionsArray, current_array, previous_array, header,  tbID)
                
                let dataArray=result[4]
                let labelArray=result[5]
                updatePieChart(myChart, dataArray, labelArray)  
            }else{
                //alert("date not found")
                
                updatePieChart(myChart, [], [])
                draw_table([],[],[],'',tbID)
            }
        }
        if (userType=="_su_"){
            draw_allGraphs(pieChartArray_su, chartDateArray_su, responseKeyArray_su,responseValueArray_su,quesOptionsArray_su)
        }else if (userType=="_emp_"){
            draw_allGraphs(pieChartArray_emp, chartDateArray_emp, responseKeyArray_emp,responseValueArray_emp,quesOptionsArray_emp)
        }
       
        
    }

    yearChangeFunc=function(id,subject_){
        get_updatedData(id,subject_)
    }


    monthChangeFunc=function(id,subject_){
        get_updatedData(id,subject_)
    }
   

})   