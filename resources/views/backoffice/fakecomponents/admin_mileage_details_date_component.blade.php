<table id="mileageTable2" class="table table-striped">
    <thead>
        <tr>
            <th>Date</th>
            <th>Calls</th>
            <th>Miles</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data['dates'] as $entry)
            <tr>
                <td>{{ \Carbon\Carbon::parse($entry['dates'])->format('d M Y') }}</td>
                <td>{{ $entry['calls'] }}</td>
                <td>{{ $entry['distance']}}</td>
                <td> <i class="fas fa-info text-primary" onClick="detailsLevel2Func('{{ \Carbon\Carbon::parse($entry['dates'])->format('Y-m-d') }}')" style="cursor: pointer;"></i></td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-danger">No mileage data available</td>
            </tr>
        @endforelse
    </tbody>
</table>