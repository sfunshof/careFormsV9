
@if (count($uncompleted_prospects)==0 ) 
    <div class="alert alert-info text-center" role="alert">
          There are no uncompleted Assessments
   </div>
@else
    <table  class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th class="fixed-column">Assessment</th>
                <th> Action </th>
            </tr>
        </thead>
        <tbody>
            @foreach($uncompleted_prospects as $prospect)
                <tr>
                    <td>{{ $prospect['createdDate'] }}</td>
                    <td>{{ $prospect['fullName'] }}</td>
                    <td> <i class=" fs-4 text-danger fas fa-exclamation-triangle"  onClick="completeProspectFunc({{json_encode($prospect)}})"></i></td>
                </tr>                                    
            @endforeach
        </tbody>
    </table>
@endif