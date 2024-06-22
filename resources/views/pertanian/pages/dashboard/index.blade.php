@extends('index')
@section('title', 'Dashboard Pertanian')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol><!-- End breadcrumb -->
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="d-flex flex-wrap">
                <div class="col-sm-6 col-lg-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div>
                                    <div class="mb-0 fw-semibold text-dark">Penyuluh User</div>
                                    <h3 class="mt-1 mb-1 text-dark fw-semibold">{{ $countPenyuluh }} Pengguna</h3>
                                    <div class="text-muted fs-12 mt-2"><i
                                            class="fe fe-arrow-up-right text-success me-1"></i>
                                        <span class="fw-bold fs-12 text-primary">6.05%</span> Since last month
                                    </div>
                                </div>
                                <i class="fe fe-user ms-auto fs-5 my-auto bg-primary-transparent p-3 br-7 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div>
                                    <div class="mb-0 fw-semibold text-dark">Laporan Palawija</div>
                                    <h3 class="mt-1 mb-1 text-dark fw-semibold">{{ $CountLaporanPalawija }} Palawija</h3>
                                    <div class="text-muted fs-12 mt-2"><i class="fe fe-arrow-up-right text-danger me-1"></i>
                                        <span class="fw-bold fs-12 text-danger">2.20%</span> Since last month
                                    </div>
                                </div>
                                <i class="fa fa-leaf ms-auto fs-5 my-auto bg-danger-transparent p-3 br-7 text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div>
                                    <div class="mb-0 fw-semibold text-dark">Laporan Padi</div>
                                    <h3 class="mt-1 mb-1 text-dark fw-semibold">{{ $CountLaporanPadi }} Padi</h3>
                                    <div class="text-muted fs-12 mt-2"><i
                                            class="fe fe-arrow-up-right text-warning me-1"></i>
                                        <span class="fw-bold fs-12 text-warning">0.20%</span> Since last month
                                    </div>
                                </div>
                                <i
                                    class="fa fa-pagelines ms-auto fs-5 my-auto bg-warning-transparent p-3 br-7 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div>
                                    <div class="mb-0 fw-semibold text-dark">Sessions</div>
                                    <h3 class="mt-1 mb-1 text-dark fw-semibold">46.4K</h3>
                                    <div class="text-muted fs-12 mt-2"><i
                                            class="fe fe-arrow-up-right text-success me-1"></i>
                                        <span class="fw-bold fs-12 text-success">04.12%</span> Since last month
                                    </div>
                                </div>
                                <i
                                    class="fe fe-database ms-auto fs-5 my-auto bg-secondary-transparent p-3 br-7 text-secondary">
                                </i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-header d-flex justify-content-between allign-items-center">
                <h3 class="card-title mb-0">Trend Rata Rata Hasil
            </div>
            <div class="card-body py-0">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <canvas id="myChart" width="200" height="100"></canvas>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-5">
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
                                        @if (isset($actualData[$index]))
                                            {{ number_format(round($predictedData[$index] - $actualData[$index]), 0, ',', '.') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($actualData[$index]) && isset($predictedData[$index]))
                                            {{ number_format(abs($actualData[$index] - $predictedData[$index]), 0, ',', '.') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th></th>
                                <th colspan="3" style="text-align:right">MAPE:</th>
                                <th>{{ $mape }}%</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
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
                            beginAtZero: true,
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
