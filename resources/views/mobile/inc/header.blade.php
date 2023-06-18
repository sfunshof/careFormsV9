<h6 class="m-3 fs-5 text-center"> 
   @if ($mobile_companyName)  
      {{$mobile_companyName[0]->companyName}}   
   @endif   
   {{ $date_of_interest }} {{$campaign }} 
</h6>
@if ($username)  
   <h6  class="m-3 fs-5 text-center"> {{ $username }} </h6>
@endif   