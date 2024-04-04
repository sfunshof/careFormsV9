<!-- Button to Open the Modal -->
  <button type="button" class="btn btn-primary d-none" 
            data-bs-toggle="modal" data-bs-target="#myModal" 
            id="modalBtnID">
     Open modal
  </button>
  
  <!-- The Modal -->
  <div class="modal fade" id="myModal"  data-bs-backdrop="static" data-bs-keyboard=false  >
    <div class="modal-dialog modal-lg modal-dialog-scrollable" id="modal-dialogID">
      <div class="modal-content">
  
        <!-- Modal Header -->
        <div class="modal-header">
          <h5 class="modal-title text-center" id="modalTitle">Modal Heading</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
  
        <!-- Modal body -->
        <div class="modal-body">
            <div id="modalBodyID"> </div>
            <div hidden id="spinner_modal"></div> 
            <div  style="display:none"  class="alert alert-danger text-center"  id="alertDanger_mobileID"  role="alert"> </div>
            <div  style="display:none" class="alert alert-info text-center" id="alertInfo_mobileID" role="alert"> </div>
        </div>
  
        <!-- Modal footer -->
        <div class="modal-footer" >
          
        </div>
  
      </div>
    </div>
  </div>