<div class="container mt-4">
    <form id="myForm">
        @include('mobilespotcheck.fakecomponents.quesTemplate')      

        <div class="mb-3"  id="div{{ $count }}"  style="display:none" >
            <label class="form-label"> Please rate the overall performance of the staff </label>
            <div class="star-container">
                <span class="star" onclick="rateStarFunc(1)">★</span>
                <span class="star" onclick="rateStarFunc(2)">★</span>
                <span class="star" onclick="rateStarFunc(3)">★</span>
                <span class="star" onclick="rateStarFunc(4)">★</span>
                <span class="star" onclick="rateStarFunc(5)">★</span>
            </div>
            <div class="submit-button">
                <button class="btn btn-primary btn-block w-100" onclick="submitSpotCheckFunc();return false;">Submit</button>
            </div>
        </div> 
    </form>
</div>