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
                    <i class="si si-briefcase fs-2 text-primary"></i>
                    <p class="card-text mt-3 mb-3">Total Projects</p>
                    <p class="h1 text-center  text-primary">459</p>
                </div>
            </div>
        </div><!-- col end -->
        <div class="col-sm-6 col-md-6 col-lg-3 col-xl-3">
            <div class="card">
                <div class="card-body text-center list-icons">
                    <i class="si si-basket-loaded fs-2 text-secondary"></i>
                    <p class="card-text mt-3 mb-3">Total Lahan Penyuluhan</p>
                    <p class="h1 text-center  text-secondary">{{ $penugasan }}</p>
                </div>
            </div>
        </div><!-- col end -->
        <div class="col-sm-6 col-md-6 col-lg-3 col-xl-3">
            <div class="card">
                <div class="card-body text-center list-icons">
                    <i class="si si-people fs-2 text-warning"></i>
                    <p class="card-text mt-3 mb-3">Total User</p>
                    <p class="h1 text-center  text-warning">{{ $user }}</p>
                </div>
            </div>
        </div><!-- col end -->
        <div class="col-sm-6 col-md-6 col-lg-3 col-xl-3">
            <div class="card">
                <div class="card-body text-center list-icons">
                    <i class="si si-eye fs-2 text-success"></i>
                    <p class="card-text mt-3 mb-3">Customer Visitis</p>
                    <p class="h1 text-center text-success">2635</p>
                </div>
            </div>
        </div><!-- col end -->
    </div>
@endsection
