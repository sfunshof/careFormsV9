
@if ((count($records)==0 ) && (count($not_yet_spotCheckedIDs)==0))
    <p class="fs-4 text-center  text-danger  "> There are no Spot checks data on the system </p>
@else
    <table  class="table caption-top">
        <caption id="reportCaptionID" class="text-primary fw-bold">The Last 3 Months Spot Checks</caption>
        <thead>
            <tr>
                <th>Date</th>
                <th class="fixed-column">Carer</th>
                <th> No of Checks </th>
            </tr>
        </thead>
        <tbody>
            @foreach($not_yet_spotCheckedIDs as $rec)
                <tr class="text-primary">
                    <td class="text-danger">---</td>
                    <td class="text-danger">{{$carerNames[$rec]}}</td>
                    <td class="text-danger">---</td>
                </tr>   
            @endforeach
            @foreach($records as $record)
                <tr>
                    <td>{{ $record->latest_date }}</td>
                    <td>{{ $employee_data[$record->carerID] }}</td>
                    <td>{{ $record->countN }}</td>
                </tr>                                    
            @endforeach
        </tbody>
    </table>
@endif
