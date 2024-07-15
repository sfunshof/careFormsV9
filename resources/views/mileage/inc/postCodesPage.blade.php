<div class="container border rounded border-primary">
    <p class="fs-2">Postcodes</p>
      
    <p> 
        <span class="more-text">Please enter the office postcode and then subsequent postcodes in the order as visited by the careworker.<br></span>
        <span class="toggle-link more-link" onclick="showMore(this)"> More ... </span>
        <span class="hidden-text less-text">
            You may also add the dates if visits took place across many days. <br>
            Alternatively, you may choose to import these postcodes from Excel.<br>
            Make sure postcodes are in the first column and optional dates in the second column.
        </span>	
        <span class="toggle-link less-link" onclick="showLess(this)" style="display: none;"> less ... </span>
     </p>
     
    
   

    <div class="textFields-wrapper">
        <div class="labels">
            <div class="label col-4 fw-bold">Postcodes</div>
            <div class="label col-6  fw-bold text-end">Dates (Optional)</div>
            <div class="label col-2" style="visibility: hidden;">❌</div>
        </div>
        <div class="textFields ms-2" style=" overflow-x: hidden;overflow-y: auto;">
            <!-- Initial row of text fields will be added here by JavaScript -->
        </div>
    </div>
    <button type="button" class="btn btn-primary mb-3" onclick="addRowFunc()">Add Postcode</button>
    <button type="button" class="btn btn-secondary mb-3 d-none" onclick="validateFunc()">Validate</button>
    <button type="button" class="btn btn-success mb-3 d-none" onclick="assignFieldsFunc()">Assign</button>
    <button type="button" class="btn btn-warning mb-3" onclick="document.getElementById('fileInput').click()">Import Excel</button>
    <button type="button" class="btn btn-danger mb-3" onclick="resetFunc()">Reset</button>
    <button type="button" class="btn btn-info mb-3" onclick="calculateDistanceFunc()">Calculate</button>
    <button type="button" class="btn  mb-3 d-none" onclick="createTableRowFunc()">Draw Table</button>
    <button type="button" class="btn  mb-3 d-none" onclick="clearTableFunc()">Clear Table</button>

    <input type="file" id="fileInput" style="display: none;" accept=".xls,.xlsx,.csv,.txt" onchange="importExcelFunc(event)">
</div>