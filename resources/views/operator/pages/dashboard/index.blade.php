@extends('index')
@section('title', 'Dashboard Operator')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb1 br-7">
            <li class="breadcrumb-item1 active">Dashboard</li>
        </ol><!-- Eneadcrumb -->
    </div>


    <div class="row row-cards">
        <div class="col-sm-6 col-md-6 col-lg-3 col-xl-3">
            <div class="card">
                <div class="card-body text-center list-icons">
                    <i class="si si-note fs-2 text-primary"></i>
                    <p class="card-text mt-3 mb-3">Total Laporan Padi</p>
                    <p class="h1 text-center text-primary">{{ $LaporanPadi }}</p>
                </div>
            </div>
        </div><!-- col end -->
        <div class="col-sm-6 col-md-6 col-lg-3 col-xl-3">
            <div class="card">
                <div class="card-body text-center list-icons">
                    <i class="si si-map fs-2 text-secondary"></i>
                    <p class="card-text mt-3 mb-3">Total Lahan Penugasan</p>
                    <p class="h1 text-center text-secondary">{{ $penugasan }}</p>
                </div>
            </div>
        </div><!-- col end -->
        <div class="col-sm-6 col-md-6 col-lg-3 col-xl-3">
            <div class="card">
                <div class="card-body text-center list-icons">
                    <i class="si si-user fs-2 text-warning"></i>
                    <p class="card-text mt-3 mb-3">Total User</p>
                    <p class="h1 text-center text-warning">{{ $user }}</p>
                </div>
            </div>
        </div><!-- col end -->
        <div class="col-sm-6 col-md-6 col-lg-3 col-xl-3">
            <div class="card">
                <a href="{{ url('/operator/master/luas_lahan_wilayah') }}" class="card-body text-center list-icons">
                    <i class="si si-globe fs-2 text-success"></i>
                    <p class="card-text mt-3 mb-3">Luas Lahan Wilayah</p>
                    <p class="h1 text-center text-success">{{ $luasLahanWilayah }}</p>
                </a>
            </div>
        </div><!-- col end -->
    </div>

@endsection
