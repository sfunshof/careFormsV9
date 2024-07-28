<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitleID">Modal Title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @php
                $font="fs-3";
            @endphp
            <p class="{{ $font }}"   id= "firstVisitID">Confirm and Submit the Postcode for the first visit  </p> 
            <p class="{{ $font }}"   id= "secondVisitID">Confirm and Submit the Postcode for this Visit </p>
                        
            <p class="{{ $font }}"   id= "firstVisitIDx">Enter and Submit the Postcode for the first Visit </p> 
            <p class="{{ $font }}"   id= "secondVisitIDx">Enter and Submit the Postcode of this Visit </p>
            
            <input type="text" class="form-control no-shadow" id="postCodeText">
            <span class="text-danger" id="err_postCodeID"></span>
            
            <div id="modal_spinner" style="display: none;">
              <div class="text-center mt-5">
                  <div class="spinner-border" role="status">
                      <span class="visually-hidden">Loading...</span>
                  </div>
              </div>
          </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success w-100 btn-lg mb-4" onClick="submit_postCodeFunc()" >Submit</button>
          <button type="button" class="btn btn-secondary w-100 btn-lg "   data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>