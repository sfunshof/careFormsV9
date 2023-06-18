<div class="bg-secondary text-white text-center text-md-start pt-2 pb-2">   
    <small> 
            @if ($mobile_companyName)
                Copyright @<?php echo date("Y"); ?>  {{$mobile_companyName[0]->companyName}}  <br>
                All Rights Reserved
            @else
                Powered by <br>
                metricsart.com
            @endif    
    </small> 
</div>
