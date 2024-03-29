<section class="section">
              
    {{--  include the spotcheck duration here  --}}
     @include('backoffice.fakecomponents.spotcheck_duration_component')         

    <div class="row">
        <div class="col-lg-12">   
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
                                    <th>Spot Checks Cases </th>
                                    <th> View </th>   
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
                                    </tr>   
                                @endforeach
                                @foreach($table_records as $rec)
                                    <tr>
                                        <td>{{$rec->date}}</td>
                                        <td>{{ $carerNames[$rec->carerID]}}</td>
                                        <td>{{$serviceUserNames[$rec->serviceUserID]}}</td>
                                        <td>{{$rec->star}}</td>
                                        <td>{{ $count_carerID[$rec->carerID]}}</td>
                                        <td> 
                                            <span style="cursor:pointer" onClick="viewSpotCheckFunc({{$rec->keyID}})">  
                                            <i class="bi bi-eye text-success"></i> </span>
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