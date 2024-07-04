@extends('index')
@section('title', 'Dashboard Pangan')
@section('content')
<div class="page-header d-sm-flex d-block">
    <ol class="breadcrumb mb-sm-0 mb-3">
        <li class="breadcrumb-item"><a href="{{ url('/pangan/dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </ol>
    <div class="ms-auto">
        <div>
            <a href="#" class="btn bg-secondary-transparent text-secondary btn-sm" data-bs-toggle="tooltip" title="Rating">
                <span>
                    <i class="fa fa-star"></i>
                </span>
            </a>
            <a href="{{ url('/lockscreen') }}" class="btn bg-primary-transparent text-primary mx-2 btn-sm" data-bs-toggle="tooltip" title="lock">
                <span>
                    <i class="fa fa-lock"></i>
                </span>
            </a>
            <a href="#" class="btn bg-warning-transparent text-warning btn-sm" data-bs-toggle="tooltip" title="Add New">
                <span>
                    <i class="fa fa-plus"></i>
                </span>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-lg-6 col-md-6 col-xxl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-0 fw-semibold text-dark lh-1 mb-2">Data Pengguna Pasar</p>
                                <div class="fs-12 text-muted mb-5"> <a href="{{ url('/pangan/user/pasar') }}">Lihat</a></div>
                                <div class="fs-12 text-muted mb-3">Total Pengguna</div>
                                <div class="fs-30 fw-semibold mb-0 lh-1">
                                    {{ $jumlahPetugasPasar }}
                                </div>
                            </div>
                            <div class="text-end d-flex flex-column align-items-center">
                                <span class="text-secondary lh-1 mt-3 fs-26"><i class="fe fe-users"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- col end -->
    <div class="col-sm-12 col-lg-6 col-md-6 col-xxl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-0 fw-semibold text-dark lh-1 mb-2">Data Pangan</p>
                                <div class="fs-12 text-muted mb-5"><a href="{{ url('/pangan/create/data_pangan') }}">Lihat</a></div>
                                <div class="fs-12 text-muted mb-3">Jumlah Data</div>
                                <div class="fs-30 fw-semibold mb-0 lh-1">
                                    {{ $jumlahDataPangan }}
                                </div>
                            </div>
                            <div class="text-end d-flex flex-column align-items-center">
                                <span class="text-warning lh-1 mt-3 fs-26"><i class="fe fe-file-text"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- col end -->
    {{-- <div class="col-sm-12 col-lg-6 col-md-6 col-xxl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-0 fw-semibold text-dark lh-1">Laporan Pangan</p>
                                <div class="fs-12 text-muted mb-5"></div>
                                <div class="fs-30 fw-semibold mb-0 lh-1">

                                </div>
                            </div>
                            <div class="text-end d-flex flex-column align-items-center">
                                <span class="text-primary lh-1 mt-3 fs-26"><i class="fa fa-folder-o"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- <div class="col-sm-12 col-lg-6 col-md-6 col-xxl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-0 fw-semibold text-dark lh-1">Grafik Pangan</p>
                                <div class="fs-12 text-muted mb-5"></div>
                                <div class="fs-30 fw-semibold mb-0 lh-1">

                                </div>
                            </div>
                            <div class="text-end d-flex flex-column align-items-center">
                                <span class="text-danger lh-1 mt-3 fs-26"><i class="ti ti-bar-chart-alt"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection
