<input type="hidden" id="dailyDateID" value="{{ $data['endDate'] }}" >
<h6 class="card-title">Update  {{ $data['endDate'] }}  Postcodes </h6>
<h6>Office Postcode : {{ $data['office_postcode']}} </h6>
<div class="row">
    <div class= "col-md-9 fw-bold">
        <label>Postcode</label>
    </div>
    <div class= "col-md-3 fw-bold">
        <label>Action</label>
    </div>
</div>    
<div id="rowContainer">
    @foreach($data['daily'] as $index => $value)
        <div class="row-container mb-0">
            <div class="input-container">
                <input 
                    name="PostCode[]" 
                    type="text" 
                    class="form-control" 
                    value="{{ $value }}" 
                    placeholder="{{ $index === 0 ? 'Office Postcode' : 'Client\'s Postcode' }}"
                >
                <small class="fw-bold ms-3 mt-0 mb-1 error_class"></small>
            </div>
            <div class="icon-container ms-3 me-3">
                <i class="fas fa-plus text-success cursor_pointer"  onclick="insertRowFunc(this)" 
                   style="visibility: {{ ($index === count($data['daily']) - 1 && $data['is_last'] == 1) ? 'hidden' : 'visible' }};">
                </i>
                @if($index > 0)
                    <i class="fas fa-times text-danger delete-icon cursor_pointer"   onclick="deleteRowFunc(this)" 
                       style="visibility: {{ ($index === count($data['daily']) - 1 && $data['is_last'] == 1) ? 'hidden' : 'visible' }};">
                    </i>
                @endif
            </div>
        </div>
    @endforeach
   <button type="button" class="sticky-button mb-4" onClick="validatePostcodesFunc()">Update</button> <!-- This position is fixed -->
</div>
