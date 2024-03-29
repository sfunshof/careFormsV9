<div class="col-md-4">   
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Select the record duration</h6>
            <div class="row">
                <div class="form-floating mb-3 col-md-8">
                    <select class="form-select" id="spotCheckSelectID" aria-label="Floating label select example" onChange="update_spotCheckDashboardSelectFunc('')">
                        <option value="1" {{ $selected == 1 ? 'selected' : '' }}>1 month</option>
                        <option value="3" {{ $selected == 3 ? 'selected' : '' }}>3 months</option>
                        <option value="6" {{ $selected == 6 ? 'selected' : '' }}>6 months</option>
                        <option value="9" {{ $selected == 9 ? 'selected' : '' }}>9 months</option>
                        <option value="12" {{ $selected == 12 ? 'selected' : '' }}>12 months</option>
                        <option value="18" {{ $selected == 18 ? 'selected' : '' }}>18 months</option>
                        <option value="24" {{ $selected == 24 ? 'selected' : '' }}>24 months</option>
                    </select>
                    <label for="spotCheckSelectID"> Select - Past Month  </label>
                </div>
            </div> 
        </div>
    </div>
</div>