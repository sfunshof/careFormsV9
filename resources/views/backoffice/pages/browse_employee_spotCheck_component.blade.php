<section class="section">
              
    {{--  include the spotcheck duration here  --}}
     @include('backoffice.fakecomponents.spotcheck_duration_component')      
    <div class="row">
        <div class="col-md-12">   
            <div class="card">
                <div class="card-body">
                    @if((count($table_records) ==0) &&(count($not_yet_spotCheckedIDs)==0))
                        <h4 class="text-danger">
                            There are no records of Carers' Spot Checks registered on the system
                        </h4>
                    @else    
                        <h6 class="card-title">Details</h6>
                        <table class="table table-striped"   id="employeeBrowseTableID" >
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Carer</th>
                                    <th>Service User</th>
                                    <th>Rating</th>
                                    <th>Cases </th>
                                    <th>Action</th>   
                                    <th>Status</th>   
                                </tr>
                            </thead>
                            <tbody>  
                                @foreach($not_yet_spotCheckedIDs as $rec)
                                    <tr class="text-primary">
                                        <td class="text-danger">---</td>
                                        <td class="text-danger">{{$carerNames[$rec]}}</td>
                                        <td class="text-danger">---</td>
                                        <td class="text-danger">---</td>
                                        <td class="text-danger">---</td>
                                        <td class="text-danger">---</td>
                                        <td class="text-danger">---</td>
                                    </tr>   
                                @endforeach
                                @foreach($table_records as $rec)
                                    @php
                                        $employeeAccept=0;
                                        if ($rec->checkComments) {
                                            $employeeAccept=1;
                                        }
                                        $editColor=" text-success ";
                                        $style="cursor:pointer";
                                        if ($employeeAccept==1){
                                            $editColor=" text-danger";
                                            $style="cursor: not-allowed; opacity: 0.5;   pointer-events: none;";
                                        }    
                                    @endphp
                                    <tr>
                                        <td>{{$rec->date}}</td>
                                        <td>{{ $carerNames[$rec->carerID]}}</td>
                                        <td>{{$serviceUserNames[$rec->serviceUserID]}}</td>
                                        <td>{{$rec->star}}</td>
                                        <td>{{ $count_carerID[$rec->carerID]}}</td>
                                        <td> 
                                            <div class="row">
                                                <div class="col"> 
                                                    <span style="cursor:pointer" onClick="viewSpotCheckFunc({{$rec->keyID}}, {{$employeeAccept}})">  
                                                        <i class="bi bi-eye text-success"></i> 
                                                    </span>  
                                                </div>
                                                <div class="col"> 
                                                    <span style="{{$style}}"  onclick="editSpotCheckFunc({{$rec->keyID}})">  
                                                        <i class ="ri-edit-2-fill  {{ $editColor }}"> </i> 
                                                    </span>  
                                                </div>  
                                                <div class="col"> </div>  
                                            </div>
                                        </td>   
                                        <td>
                                            @if ($employeeAccept==1)
                                                <i class="fas fa-user-check text-success"></i> 
                                            @else
                                                <i class="bi bi-exclamation-triangle text-danger"></i>
                                             @endif
                                        </td>   
                                    </tr>   
                                @endforeach
                            </tbody>
                        </table>
                     @endif    
                </div>
            </div>                          
        </div>
    </div>
</section>