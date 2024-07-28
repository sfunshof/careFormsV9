<table id="mileageTable3"    class="table">
    <thead>
        <tr>
            <th>From</th>
            <th>To</th>
            <th>Miles</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data['postcodes'] as $distance)
            <tr>
                <td>{{ $distance['From'] }}</td>
                <td>{{ $distance['To'] }}</td>
                <td>{{ $distance['Distance'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-danger">No data available</td>
            </tr>
        @endforelse
    </tbody>
</table>