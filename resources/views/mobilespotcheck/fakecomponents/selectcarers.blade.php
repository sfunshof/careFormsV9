@php
    $status = "Please select the carer to be spot checked";
    $color = "";
    if (count($carers) == 0) {
        $status = "There are no registered carers on the system";
        $color = "text-danger";
    }
@endphp

<p class="fs-4 text-center  {{ $color }}  "> {{ $status }} </p>

<div class="overflow-auto" style="max-height: 65vh;">
    <ul class="list-group">
        @foreach ($carers as $carer)
            <?php  
                $name=$carer['firstName']. '  ' . $carer['lastName'];    
            ?>
            <li  tabindex="0"  onclick="confirmCarerFunc('{{$carer['userID']}}', '{{ $name}} ')"  class="list-group-item custom-list-item">{{$name}}</li>
        @endforeach
        <!-- Add more list items here -->
        @for ($i = 1; $i <= 20; $i++)
            <li class="list-group-item custom-list-item" >Item {{ $i }}</li>
        @endfor
    </ul>
</div>