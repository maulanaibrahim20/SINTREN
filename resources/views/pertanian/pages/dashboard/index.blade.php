@extends('index')
@section('title', 'Dashboard Pertanian')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol><!-- End breadcrumb -->
        <div class="ms-auto">
            <div>
                <a href="#" class="btn bg-secondary-transparent text-secondary btn-sm" data-bs-toggle="tooltip"
                    title="" data-bs-placement="bottom" data-bs-original-title="Rating">
                    <span>
                        <i class="fa fa-star"></i>
                    </span>
                </a>
                <a href="lockscreen.html" class="btn bg-primary-transparent text-primary mx-2 btn-sm"
                    data-bs-toggle="tooltip" title="" data-bs-placement="bottom" data-bs-original-title="lock">
                    <span>
                        <i class="fa fa-lock"></i>
                    </span>
                </a>
                <a href="#" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip"
                    title="" data-bs-placement="bottom" data-bs-original-title="Add New">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between allign-items-center">
                    <h3 class="card-title mb-0">Trend Rata Rata Hasil
                </div>
                <div class="card-body py-0">
                    <canvas id="myChart" width="200" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
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

    <div class="row">
        <div class="col-xl-5 col-lg-12 col-md-12 col-sm-12">
            <div class="row row-sm">
                <div class="col-sm-6 col-lg-6">
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
                <div class="col-sm-6 col-lg-6">
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
            <div class="row row-sm">
                <div class="col-12">
                    <div class="card overflow-hidden">
                        <div class="card-header pb-0 border-bottom-0">
                            <h3 class="card-title">Deliverables</h3>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-block d-sm-inline-flex align-items-center my-3">
                                <p class="mb-0 me-5"> <span class="legend bg-blue"></span>Marketing Strategy</p>
                                <p class="mb-0 me-5"> <span class="legend bg-teal"></span>Engaging Audience</p>
                                <p class="mb-0 me-5"> <span class="legend bg-pink"></span>Others</p>
                            </div>
                            <div class="progress br-10 progress-md">
                                <div class="progress-bar lh-1 bg-blue w-20">20%</div>
                                <div class="progress-bar lh-1 bg-cyan w-30">30%</div>
                                <div class="progress-bar lh-1 bg-pink w-50">50%</div>
                            </div>
                        </div>
                    </div>
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
