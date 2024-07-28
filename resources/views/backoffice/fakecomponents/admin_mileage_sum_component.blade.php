<table id="mileageTable1"  class="table">
    <thead>
        <tr>
            <th>Care worker</th>
            <th>No of calls</th>
            <th>Miles</th>
            <th>Cost £</th>
            <th>Details</th>
        </tr>
    </thead> 
    <tbody>
        @forelse ($data['report'] as $employee)
            <tr>
                <td>{{ $employee['firstName'] }} {{ $employee['lastName'] }}</td>
                <td>{{ $employee['totalCount'] }}</td>
                <td>{{ $employee['totalDistance'] }}</td>
                <td>{{ number_format($employee['mileageCost'], 2) }}</td>
                <td> <i class="fas fa-info text-primary" onClick="detailsLevel1Func('{{ $employee['userID'] }}')" style="cursor: pointer;"></i></td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-danger">No data available</td>
            </tr>
        @endforelse
    </tbody>
</table>