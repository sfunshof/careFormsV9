<section class="section dashboard">
    <div class="row">
              
       
        {{--  include the spotcheck duration here  --}}
        @include('backoffice.fakecomponents.spotcheck_duration_component')         
        
        <div class="col-md-4">   
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Spot Check Summary</h6>
                    <p> <span id="userCheckID">{{ $unique_carers }}</span> 
                       Unique Spot Checks done out of  <span id="userTotalID">{{ $total_carers }}</span> Carers  
                    </p>
                    <p> <span id="suCheckID">{{ $unique_serviceUsers }}</span> 
                        Unique visits done out of  <span id="suTotalID">{{ $total_serviceUsers }}</span> Service Users    
                    </p>
          
                </div>
            </div>
        </div>

        <div class="col-md-4">   
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Carers' 5 Star Review</h6>
                    <p><span class="">{{ $total_star }}</span> Carer spot-checked cases</p>   
                    <div class="mt-0">
                        <div class="row">
                            <div class="col">
                                <div class="centered-span">
                                    <span class="stars-landing" style="--rating: {{$weighted_star}};" aria-label="Average carer rating is {{ $weighted_star }} out of 5.">★★★★★</span>
                                    <span class="ms-4">{{ $weighted_star}} out of 5</span>
                                </div>
                            </div>
                        </div>
                        
                        @for ($i = 5; $i >= 1; $i--)
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">{{ $i }} star</span>
                                <div class="progress" style="flex-grow: 1;">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        @if (array_sum($array_star) > 0)
                                            style="width: {{ ($array_star[$i - 1] * 100) / array_sum($array_star) }}%;"
                                            aria-valuenow="{{ ($array_star[$i - 1] * 100) / array_sum($array_star) }}"
                                        @endif
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                @if (array_sum($array_star)> 0) 
                                    <span class="">{{ number_format(($array_star[$i - 1] * 100) / array_sum($array_star), 0) }}%</span>
                                @endif
                            </div>
                        @endfor
                    </div>
                     
                </div>
            </div>
        </div>
    </div>
    {{-- Table --}}
    <div class="row">
        <div class="col-md-12">   
            <div class="card">
                <div class="card-body">
                    @if (count($records) ==0 )
                        <h4 class="text-danger">
                            There are no records of any spot checks carried out
                        </h4>
                    @else    
                        <h6 class="card-title">Details</h6>
                        <table class="table table-striped" id="employeeBrowseTableID">
                            <thead>
                                <tr>
                                    <th>Latest Date</th>
                                    <th>Carer</th>
                                    <th>No of Spot Checks</th>
                                    <th>Average Rating</th>
                                </tr>
                            </thead>
                            <tbody>  
                                @foreach($records as $record)
                                <tr>
                                    <td>{{ $record->latest_date }}</td>
                                    <td>{{ $employee_data[$record->carerID] }}</td>
                                    <td>{{ $record->countN }}</td>
                                    <td>{{ $record->rating }}</td>
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