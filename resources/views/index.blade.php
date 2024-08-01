<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="Sparic– Creative Admin Multipurpose Responsive Bootstrap5 Dashboard HTML Template" name="description">
    <meta content="Spruko Technologies Private Limited" name="author">
    <meta name="keywords"
        content="html admin template, bootstrap admin template premium, premium responsive admin template, admin dashboard template bootstrap, bootstrap simple admin template premium, web admin template, bootstrap admin template, premium admin template html5, best bootstrap admin template, premium admin panel template, admin template">

    <!-- Favicon -->
    <link rel="icon" href="{{ url('/landings') }}/img/favicon_io/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('/landings') }}/img/favicon_io/logo.png">

    <!-- Title -->
    <title>Sintrenayu | @yield('title')</title>

    @include('template.component.style_css')
    @yield('css')

</head>

<body class="app sidebar-mini ltr">

    <!--Global-Loader-->
    <div id="global-loader">
        <img src="{{ url('/assets') }}/images/loader.svg" alt="loader">
    </div>

    <div class="page">
        <div class="page-main">

            <!-- app-Header -->
            @include('template.header')
            <!-- app-Header -->

            <!--News Ticker-->
            {{-- <div class="contain er-fluid bg-white news-ticker">
                <div class="bg-white">
                    <div class="best-ticker" id="newsticker">
                        <div class="bn-news">
                            @php
                                $totalPenyuluh = DB::table('penyuluhs')->count();
                                $totalLaporanPadi = DB::table('laporan_padis')->count();
                                $totalLaporanPalawija = DB::table('laporan_palawijas')->count();
                                $totalLuasLahanWilayah = DB::table('luas_lahan_wilayah')->count();
                                $totalPenugasan = DB::table('penugasan_penyuluh')->count();
                                $totalKecamatan = DB::table('kecamatans')->count();
                                $totalDesa = DB::table('desas')->count();
                                $totalPasar = DB::table('pasars')->count();
                                $totalPetugasPasar = DB::table('petugas_pasars')->count();
                                $totalVerifyPadi = DB::table('verify_padis')->count();
                                $totalVerifyPalawija = DB::table('verify_palawijas')->count();
                                // Data statis
                                $totalPetani = 5000;
                                $totalAlatPertanian = 1200;
                                $totalProduksiBeras = 300000; // dalam ton
                                $totalPenggunaanPupuk = 10000; // dalam ton
                            @endphp
                            <ul>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-rice bg-danger-transparent text-danger mx-1"></span>
                                    <span class="d-inline-block">Total Laporan Padi</span>
                                    <span class="bn-positive me-4">{{ $totalLaporanPadi }}</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-user bg-info-transparent text-info mx-1"></span>
                                    <span class="d-inline-block">Total Penyuluh</span>
                                    <span class="bn-negative me-4">{{ $totalPenyuluh }}</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-leaf bg-success-transparent text-success mx-1"></span>
                                    <span class="d-inline-block">Total Laporan Palawija</span>
                                    <span class="bn-negative me-4">{{ $totalLaporanPalawija }}</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-map bg-warning-transparent text-warning mx-1"></span>
                                    <span class="d-inline-block">Total Luas Lahan Wilayah</span>
                                    <span class="bn-positive me-4">{{ $totalLuasLahanWilayah }}</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-briefcase bg-primary-transparent text-primary mx-1"></span>
                                    <span class="d-inline-block">Total Penugasan</span>
                                    <span class="bn-positive me-4">{{ $totalPenugasan }}</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-home bg-danger-transparent text-danger mx-1"></span>
                                    <span class="d-inline-block">Total Desa</span>
                                    <span class="bn-positive me-4">{{ $totalDesa }}</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-home bg-secondary-transparent text-secondary mx-1"></span>
                                    <span class="d-inline-block">Total Kecamatan</span>
                                    <span class="bn-positive me-4">{{ $totalKecamatan }}</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-home bg-info-transparent text-info mx-1"></span>
                                    <span class="d-inline-block">Total Petugas Pasar</span>
                                    <span class="bn-positive me-4">{{ $totalPetugasPasar }}</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-home bg-success-transparent text-success mx-1"></span>
                                    <span class="d-inline-block">Total Pasar</span>
                                    <span class="bn-negative me-4">{{ $totalPasar }}</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-check-circle bg-danger-transparent text-danger mx-1"></span>
                                    <span class="d-inline-block">Total Laporan Padi yang Diverifikasi</span>
                                    <span class="bn-negative me-4">{{ $totalVerifyPadi }}</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-check-circle bg-warning-transparent text-warning mx-1"></span>
                                    <span class="d-inline-block">Total Laporan Palawija yang Diverifikasi</span>
                                    <span class="bn-positive me-4">{{ $totalVerifyPalawija }}</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-user bg-danger-transparent text-danger mx-1"></span>
                                    <span class="d-inline-block">Total Petani</span>
                                    <span class="bn-negative me-4">{{ $totalPetani }}</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-gear bg-primary-transparent text-primary mx-1"></span>
                                    <span class="d-inline-block">Total Alat Pertanian</span>
                                    <span class="bn-negative me-4">{{ $totalAlatPertanian }}</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-tree bg-info-transparent text-info mx-1"></span>
                                    <span class="d-inline-block">Total Produksi Beras</span>
                                    <span class="bn-positive me-4">{{ $totalProduksiBeras }} ton</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-tree bg-success-transparent text-success mx-1"></span>
                                    <span class="d-inline-block">Total Penggunaan Pupuk</span>
                                    <span class="bn-positive me-4">{{ $totalPenggunaanPupuk }} ton</span>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div> --}}
            <!--/News Ticker-->

            <!--App-Sidebar-->
            @include('template.sidebar')
            <!--/App-Sidebar-->

            <!-- app-content-->
            <div class="main-content app-content">
                <div class="side-app">

                    <!-- container -->
                    <div class="main-container container-fluid">

                        <!-- page-header -->
                        {{-- <div class="page-header d-sm-flex d-block">
                            <ol class="breadcrumb mb-sm-0 mb-3">
                                <!-- breadcrumb -->
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard01</li>
                            </ol><!-- End breadcrumb -->
                            <div class="ms-auto">
                                <div>
                                    <a href="#" class="btn bg-secondary-transparent text-secondary btn-sm"
                                        data-bs-toggle="tooltip" title="" data-bs-placement="bottom"
                                        data-bs-original-title="Rating">
                                        <span>
                                            <i class="fa fa-star"></i>
                                        </span>
                                    </a>
                                    <a href="lockscreen.html"
                                        class="btn bg-primary-transparent text-primary mx-2 btn-sm"
                                        data-bs-toggle="tooltip" title="" data-bs-placement="bottom"
                                        data-bs-original-title="lock">
                                        <span>
                                            <i class="fa fa-lock"></i>
                                        </span>
                                    </a>
                                    <a href="#" class="btn bg-warning-transparent text-warning btn-sm"
                                        data-bs-toggle="tooltip" title="" data-bs-placement="bottom"
                                        data-bs-original-title="Add New">
                                        <span>
                                            <i class="fa fa-plus"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div> --}}
                        <!-- End page-header -->

                        @yield('content')
                    </div>
                    <!-- container end -->

                </div>
            </div>
            <!-- End app-content-->
        </div>

        <!--footer-->
        @include('template.footer')
        <!-- End Footer-->

    </div>
    <!-- End Page -->

    <!-- Back to top -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    @include('template.component.style_js')
    @include('sweetalert::alert')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @if (session('success'))
        <script type="text/javascript">
            Swal.fire({
                title: "Berhasil",
                text: "{{ session('success') }}",
                icon: "success"
            });
        </script>
    @endif
    @if (session('error'))
        <script type="text/javascript">
            Swal.fire({
                title: "Gagal",
                text: "{{ session('error') }}",
                icon: "error"
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('#logoutButton').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Anda yakin ingin logout?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ url('/logout') }}";
                    }
                });
            });
        });
    </script>
    {{-- <script src="https://cdnjs.com/libraries/Chart.js"></script> --}}
    @yield('script')

</body>

</html>
