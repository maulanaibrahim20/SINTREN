@extends('index')
@section('title', 'Hasil')
@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Data Prediksi</h2>
        </div>
        <div class="card-body">
            <div id="myChart" width="200" height="100"></div>
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
                                    <td>Mean Absolute Percent Error (MAPE): <strong>{{ $mape }}%</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
            const tipeData = "{{ $tipeData }}"; // Ambil nilai tipeData dari Blade

            // Konfigurasi grafik
            const config = {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Grafik Nilai Aktual dan Prediksi Tahunan'
                },
                subtitle: {
                    text: `Tipe Data: ${tipeData}` // Tampilkan tipe data sebagai subtitle
                },
                xAxis: {
                    categories: labels,
                    title: {
                        text: 'Tahun'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Nilai'
                    },
                    min: 0 // Mulai dari angka 0 pada sumbu Y
                },
                series: [{
                    name: 'Nilai Aktual Tahunan',
                    data: actualData,
                    color: 'rgb(54, 162, 235)', // Biru untuk data aktual
                    lineWidth: 2
                }, {
                    name: 'Prediksi Nilai Tahunan',
                    data: predictedData,
                    color: 'rgb(255, 99, 132)', // Merah untuk data prediksi
                    lineWidth: 2,
                }]
            };

            // Render grafik ke div dengan id 'myChart'
            Highcharts.chart('myChart', config);
        });
    </script>
@endsection
