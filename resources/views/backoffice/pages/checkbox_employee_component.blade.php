<?php 
    $isChecked="";
    if ($isDisabledFlag==0) $isChecked="checked='checked'";
?>

<div class="form-check form-switch form-switch-md">
    <input class="form-check-input" type="checkbox" id="showDisabledUsersID"   onClick="browse_all_employeesFunc()"  {{ $isChecked }}>
    <label class="form-check-label" style="padding-left:.5rem;padding-top:.2rem;" for="showDisabledUsersID">Show Disengaged Users</label>
</div>