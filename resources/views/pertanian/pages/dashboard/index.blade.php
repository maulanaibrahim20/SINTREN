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
                                    <div class="mb-0 fw-semibold text-dark">Total Luas Lahan Wilayah</div>
                                    <h3 class="mt-1 mb-1 text-dark fw-semibold">{{ $CountLuasLahanWilayah }}</h3>
                                </div>
                                <i
                                    class="fe fe-map ms-auto fs-5 my-auto bg-secondary-transparent p-3 br-7 text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total Produksi Padi Per kecamatan</h3>
                </div>
                <div class="card-body">
                    <div id="chart-per-kecamatan"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
            <div class="card d-inline-block overflow-hidden">
                <div class="card-header border-bottom">
                    <h3 class="card-title mb-0">Produksi Padi Per Desa</h3>
                </div>
                <div class="card-body pb-0">
                    <div id="panen-bulan"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card d-inline-block overflow-hidden">
                <div class="card-header border-bottom">
                    <h3 class="card-title mb-0">Produksi Padi Per Desa</h3>
                </div>
                <div class="card-body pb-0">
                    <div id="chart-per-desa"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="card">
            <div class="card-header d-flex justify-content-between allign-items-center">
                <h3 class="card-title mb-0">Trend Rata Rata Hasil
            </div>
            @include('pertanian.pages.dashboard.prediksi_pertanian')
        </div>
    </div>
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const labels = @json($labels);
            const actualData = @json($actualData);
            const predictedData = @json($predictedData);

            Highcharts.chart('myChart', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Trend Pertanian Padi'
                },
                subtitle: {
                    text: 'Sumber: Dinas Ketahanan Pangan Dan Pertanian Kabupaten Indramayu'
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
                    min: 0 // Mulai dari nilai 0 pada sumbu y
                },
                tooltip: {
                    crosshairs: true,
                    shared: true,
                    valueSuffix: ' ton'
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: false // Tidak menampilkan nilai pada garis
                        },
                        enableMouseTracking: true
                    }
                },
                series: [{
                    name: 'Nilai Aktual Tahunan',
                    data: actualData,
                    color: 'rgb(54, 162, 235)' // Biru untuk data aktual
                }, {
                    name: 'Prediksi Nilai Tahunan',
                    data: predictedData,
                    color: 'rgb(255, 99, 132)' // Merah untuk data prediksi
                }]
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartMonths = @json($chartMonths);
            const chartData = @json($chartData);

            Highcharts.chart('panen-bulan', {
                chart: {
                    type: 'spline'
                },
                title: {
                    text: 'Produksi Padi Perbulan'
                },
                xAxis: {
                    categories: chartMonths
                },
                yAxis: {
                    title: {
                        text: 'Total Produksi'
                    }
                },
                series: [{
                    name: 'Produksi',
                    data: chartData
                }]
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kecamatanData = @json($kecamatanData);
            const desaData = @json($desaData);

            // Format data kecamatan untuk Highcharts
            const kecamatanChartData = kecamatanData.map(item => ({
                name: item.kecamatan,
                y: item.total_produksi
            }));

            // Format data desa untuk Highcharts (dikelompokkan per kecamatan)
            const desaChartData = kecamatanData.map(kecamatan => {
                const desaInKecamatan = desaData.filter(desa => desa.district_id === kecamatan.id);
                console.log(`Desa in Kecamatan ${kecamatan.name}:`, desaInKecamatan);
                return {
                    name: kecamatan.kecamatan,
                    data: desaInKecamatan.map(desa => [desa.desa, desa.total_produksi])
                };
            });

            // Chart untuk produksi padi per kecamatan
            Highcharts.chart('chart-per-kecamatan', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Produksi Padi Per Kecamatan'
                },
                series: [{
                    name: 'Produksi',
                    colorByPoint: true,
                    data: kecamatanChartData
                }]
            });

            // Chart untuk produksi padi per desa
            Highcharts.chart('chart-per-desa', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Produksi Padi Per Desa'
                },
                xAxis: {
                    type: 'category',
                    title: {
                        text: 'Desa'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Total Produksi (ton)'
                    }
                },
                accessibility: {
                    enabled: true
                },
                series: desaChartData
            });
        });
    </script>

@endsection
