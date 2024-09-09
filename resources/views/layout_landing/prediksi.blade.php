<section id="features" class="features">
    <div class="container">
        <div id="container"></div>
    </div>
    <div class="container">
        <h3>Data Training:</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th>Aktual</th>
                    <th>Prediksi</th>
                    <th>Selisih</th>
                    <th>Error</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($labels as $index => $tahun)
                    <tr>
                        <td>{{ $tahun }}</td>
                        <td>{{ isset($actualData[$index]) ? number_format($actualData[$index], 0, ',', '.') : 'N/A' }}
                        </td>
                        <td>{{ isset($predictedData[$index]) ? number_format(round($predictedData[$index]), 0, ',', '.') : 'N/A' }}
                        </td>
                        <td>
                            @if (isset($actualData[$index]) && isset($predictedData[$index]))
                                {{ number_format(round($predictedData[$index] - $actualData[$index]), 0, ',', '.') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if (isset($actualData[$index]) && isset($predictedData[$index]))
                                {{ number_format($predictions[$tahun]['error'], 5, ',', '.') }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="4" style="text-align:right">MAPE:</th>
                    {{-- <th>{{ $mape }}%</th> --}}
                </tr>
            </tbody>
        </table>
    </div>
</section>
