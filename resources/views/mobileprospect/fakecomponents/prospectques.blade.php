<div class="container mt-4">
    <form id="mobile_prospectQuesFormID">
        @include('mobilespotcheck.fakecomponents.quesTemplate')      

        <div class="mb-3 text-center"  id="div{{ $count }}"  style="display:none" >
            <p>
                This is the end of the assessment data entry. <br>
                You should click the submit button to complete it
            </p>   
            <div class="submit-button">
                <button class="btn btn-primary btn-block w-100" onclick="submitProspectFunc();return false;">Submit</button>
            </div>
        </div> 
    </form>
</div>