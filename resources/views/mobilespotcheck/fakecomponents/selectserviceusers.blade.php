  <p class="fs-4 text-center"> Starting the Spot Check </p>
  @php
    $show="";
    $isDisabled="";
    $count=1;
    if (count($serviceUsers) ==0){
        $count=0;
        $show="d-none";
        $isDisabled="disabled";
    }
  @endphp

  <!-- Button trigger modal -->
  <button  {{ $isDisabled}}    type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
      Select the Service User
  </button>
  
  @if ($count ==0)
      <p class="fs-4 text-center text-danger  "> No service user is registered on this platform </p>
  @endif

  
  <!-- Modal -->
  <div  {{$show}} class="modal fade" id="staticBackdrop"  tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header bg-success text-center">
            {{-- <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1> --}}
            <p class="fs-4 text-white "  id="staticBackdropLabel" > Select the Service User</p>
            {{--
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            --}}
        </div>
        <div class="modal-body ">
            <ul class="list-group">
                @foreach ($serviceUsers as $serviceUser)
                    <?php  
                        $name=$serviceUser['name'];    
                        $id=$serviceUser['userID'];
                    ?>
                    <li tabindex="0"  onclick="assignServiceUserFunc('{{$id}}', '{{$name}}' ) " id="{{$id}}"  class="list-group-item serviceUser-list-item">{{$name}}</li>
                @endforeach   
            </ul>
        </div>
        <div class="modal-footer">
          <button  onClick="cancelSelectedServiceUserFunc()"  type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancel</button>
          <button onClick="selectServiceUserFunc()"   type="button" class="btn btn-primary w-100"  data-bs-dismiss="modal" >Select</button>
        </div>
      </div>
    </div>
  </div>

