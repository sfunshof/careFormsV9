@php
    $status = "Please select the carer to be spot checked";
    $color = "";
    if (count($carers) == 0) {
        $status = "There are no registered carers on the system";
        $color = "text-danger";
    }
@endphp

<p class="fs-4 text-center  {{ $color }}  "> {{ $status }} </p>
<ul class="list-group">
    @foreach ($carers as $carer)
        <?php  
            $name=$carer['firstName']. '  ' . $carer['lastName'];    
        ?>
        <li  tabindex="0"  onclick="confirmCarerFunc('{{$carer['userID']}}', '{{ $name}} ')"  class="list-group-item custom-list-item">{{$name}}</li>
    @endforeach
    <!-- Add more list items here -->
</ul>
