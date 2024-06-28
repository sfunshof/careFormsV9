<!-- Button trigger modal -->
<button id= "reportBtnID" style="display:none" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#prospect_staticBackdrop2">
    Launch static backdrop modal
</button>
  
<!-- Modal -->
<div class="modal fade" id="prospect_staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledbhhhhy="staticBackdropLabel2" aria-hidden="true">
    <div class="modal-dialog  modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header  bg-primary text-white">
                <h5 class="modal-title w-100 text-center " id="helloModalLabel">Unfinished  Assessments</h5>
            </div>
            <div class="modal-body">
                <!--Modal Spinner -->
                <div id="spinner_modal" style="display: none;">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
           
                <div class="table-wrapper"  id="durationTableID" >
                    @include('mobileprospect.fakecomponents.reportTable')
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> 
