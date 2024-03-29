<!-- Button trigger modal -->
<button id= "reportBtnID" style="display:none" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
    Launch static backdrop modal
</button>
  
<!-- Modal -->
<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledbhhhhy="staticBackdropLabel2" aria-hidden="true">
    <div class="modal-dialog  modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-body">
                <!--Modal Spinner -->
                <div id="spinner_modal" style="display: none;">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                
                <button  id="durationBtnID"   onClick="showDurationSelectFunc()"  type="button" class="btn btn-primary w-100" >Select the Duration</button>
                <div style="display: flex; align-items: center; justify-content: center;">            
                    <fieldset id="durationSelectID"  style="display:none">
                        <legend class="mb-3">Selected Duration: 3 Months</legend>

                        <div class="form-check">
                            <input class="form-check-input mb-3"  onClick="reportRadioFunc('1 Month',1)"      type="radio" id="1month" name="duration" value="1 Month">
                            <label class="form-check-label mb-3 "  for="1month">1 Month</label><br>
                        
                            <input class="form-check-input mb-3"  onClick="reportRadioFunc('3 Months',3)"  type="radio" id="3months" name="duration" value="3 Months" checked>
                            <label class="form-check-label mb-3 "  for="3months">3 Months</label><br>
                        
                            <input class="form-check-input mb-3"  onClick="reportRadioFunc('6 Months',6)"  type="radio" id="6months" name="duration" value="6 Months">
                            <label  class="form-check-label mb-3 " for="6months">6 Months</label><br>
                        
                            <input class="form-check-input mb-3"  onClick="reportRadioFunc('1 Year',12)"  type="radio" id="1year" name="duration" value="1 Year">
                            <label class="form-check-label mb-3"  for="1year">1 Year</label><br>
                        </div>
                    </fieldset>
                </div>

                <div class="table-wrapper"  id="durationTableID" >
                    @include('mobilespotcheck.fakecomponents.reportTable')
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> 
