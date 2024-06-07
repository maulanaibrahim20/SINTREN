@extends('index')
@section('title', 'Hasil')
@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Data Prediksi</h2>
        </div>
        <div class="card-body">
            <canvas id="myChart" width="200" height="100"></canvas>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-3">
                <div class="card-header">
                    <h2>Kriteria Interpretasi MAPE</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th>MAPE (%)</th>
                                    <th>Akurasi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>&lt; 10 %</td>
                                    <td>Sangat Baik</td>
                                    <td>Sangat akurat, prediksi sangat baik</td>
                                </tr>
                                <tr>
                                    <td>10-20 %</td>
                                    <td>Baik</td>
                                    <td>Baik, prediksi cukup akurat</td>
                                </tr>
                                <tr>
                                    <td>20-50 %</td>
                                    <td>Layak / Memadai</td>
                                    <td>Cukup akurat, tapi ada ruang untuk perbaikan</td>
                                </tr>
                                <tr>
                                    <td>&gt; 50 %</td>
                                    <td>Sangat Buruk</td>
                                    <td>Tidak akurat, prediksi sangat buruk</td>
                                </tr>
                                <tr>
                                    <td>Hasil</td>
                                    {{-- <td>Mean Absolute Percent Error (MAPE): <strong>{{ $tanpaRound }}%</strong></td> --}}
                                    <td>Mean Absolute Percent Error (MAPE): <strong>{{ $mape }}%</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h2>Detail Perhitungan Nilai</h2>
        </div>
        <div class="card-body">
            <h3>Detail Perhitungan Nilai:</h3>
            {{-- <ul>
                @foreach ($detailedPredictions as $description)
                    <li>{{ $description }}</li>
                @endforeach
            </ul> --}}
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // Data prediksi dari controller
            const labels = @json($labels);
            const actualData = @json($actualData);
            const predictedData = @json($predictedData);

            // Inisialisasi data label
            const data = {
                labels: labels,
                datasets: [{
                        label: 'Nilai Aktual Tahunan',
                        data: actualData,
                        fill: false,
                        borderColor: 'rgb(54, 162, 235)', // Biru untuk data aktual
                        tension: 0.1
                    },
                    {
                        label: 'Prediksi Nilai Tahunan',
                        data: predictedData,
                        fill: false,
                        borderColor: 'rgb(255, 99, 132)', // Merah untuk data prediksi
                        tension: 0.1
                    }
                ]
            };

            // Konfigurasi grafik
            const config = {
                type: 'line',
                data: data,
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Nilai'
                            }
                        }
                    }
                }
            };

            // Render grafik ke canvas
            const myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
        });
    </script>

@endsection
