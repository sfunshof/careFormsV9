<h6>Summary Distance : {{ $data['summary_distance']}}  Miles</h6>
<div class="scrollable-table-container">
    <table class="table table">
        <thead>
            <tr>
                <th class="bg-light">Dates</th>
                <th class="bg-light">Calls</th>
                <th class="bg-light">Miles</th>
                <th class="bg-light">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $reversedDates = array_reverse($data['dates']);
                $reversedCounts = array_reverse($data['counts']);
                $reversedDistances = array_reverse($data['distances']);
            @endphp
            
            @forelse ($reversedDates as $index => $date)
                <tr class="{{ $reversedDistances[$index] == 0 ? 'text-danger' : 'text-success' }}">
                    <td>{{ $date }}</td>
                    <td>{{ $reversedCounts[$index] }}</td>
                    <td>{{ $reversedDistances[$index] }}</td>
                    <td>
                        <span>
                            <i class="fas fa-edit" onclick="set_daily_postcodeFunc('{{ $date }}')"></i> 
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>