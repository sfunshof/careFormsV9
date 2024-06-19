@foreach ($arrayTable as $index => $caption)
        <table>
            <thead>
                <tr>
                    <th colspan="12" class="table-caption">{{ $caption }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Group data by row for efficient rendering
                    $groupedData = [];
                    foreach ($data[$index] as $item) {
                        $row = $item['Row'];
                        $groupedData[$row][] = $item;
                    }
                    $maxRow = max(array_keys($groupedData));
                @endphp

                @for ($row = 1; $row <= $maxRow; $row++)
                    @if (isset($groupedData[$row]))
                        <tr>
                            @foreach ($groupedData[$row] as $item)
                                <td colspan="{{ $item['Col'] }}">
                                    @if (!empty($item['title']))
                                        <span class="float-label">{{ $item['title'] }}</span>
                                    @endif
                                    <span class="name-label">{{ $item['Name'] }}</span>
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endfor
            </tbody>
        </table>
    @endforeach
    <h3> Assessment Questionnaire </h3>
   @include ('backoffice.pages.pdf_quesTemplate')
   <div class="horizontal-line"></div>
   <p class="italic-right">
         Accessed by: {{ $accessByArray->first()->supervisor}}  &nbsp; Date: {{ $accessByArray->first()->date_issue }} 
   </p>   