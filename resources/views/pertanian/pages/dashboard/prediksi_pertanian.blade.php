<div class="card-body py-0">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div id="myChart" style="width:100%; height:400px;"></div>
    </div>
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-5">
        <div style="max-height: 400px; overflow-y: auto; position: relative;">
            <table class="table table-bordered table-striped" style="table-layout: fixed;">
                <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                    <tr>
                        <th style="padding: 0.75rem; vertical-align: middle; text-align: center;">Tahun</th>
                        <th style="padding: 0.75rem; vertical-align: middle; text-align: center;">Aktual</th>
                        <th style="padding: 0.75rem; vertical-align: middle; text-align: center;">Prediksi</th>
                        <th style="padding: 0.75rem; vertical-align: middle; text-align: center;">Selisih</th>
                        <th style="padding: 0.75rem; vertical-align: middle; text-align: center;">Error</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($labels as $index => $tahun)
                        <tr>
                            <td style="padding: 0.75rem; vertical-align: middle; text-align: center;">
                                {{ $tahun }}</td>
                            <td style="padding: 0.75rem; vertical-align: middle; text-align: center;">
                                {{ isset($actualData[$index]) ? number_format($actualData[$index], 0, ',', '.') : 'N/A' }}
                            </td>
                            <td style="padding: 0.75rem; vertical-align: middle; text-align: center;">
                                {{ isset($predictedData[$index]) ? number_format(round($predictedData[$index]), 0, ',', '.') : 'N/A' }}
                            </td>
                            <td style="padding: 0.75rem; vertical-align: middle; text-align: center;">
                                @if (isset($actualData[$index]))
                                    {{ number_format(round($predictedData[$index] - $actualData[$index]), 0, ',', '.') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td style="padding: 0.75rem; vertical-align: middle; text-align: center;">
                                @if (isset($actualData[$index]) && isset($predictedData[$index]))
                                    {{ number_format($predictions[$tahun]['error'], 5, ',', '.') }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th style="padding: 0.75rem; vertical-align: middle; text-align: center;"></th>
                        <th colspan="3" style="padding: 0.75rem; vertical-align: middle; text-align: right;">
                            MAPE:</th>
                        <th style="padding: 0.75rem; vertical-align: middle; text-align: center;">
                            {{ $mape }}%</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


</div>
