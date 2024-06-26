@extends('index')
@section('title', 'Grafik Stok Pangan | Pangan')
@section('content')
<div class="page-header d-sm-flex d-block">
    <ol class="breadcrumb mb-sm-0 mb-3">
        <!-- breadcrumb -->
        <li class="breadcrumb-item1"><a href="{{ url('/pangan/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item1 active">Grafik Stok Pangan</li>
    </ol><!-- End breadcrumb -->
</div>
<!-- Row -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header custom-header d-flex justify-content-between align-items-center border-bottom">
                <h3 class="card-title">Grafik Stok Pangan</h3>
                <div class="dropdown">
                    <a href="javascript:void(0);"
                        class="d-flex align-items-center bg-primary btn btn-sm mx-1 fw-semibold"
                        data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Sort by:
                        Weekly<i class="fe fe-chevron-down fw-semibold mx-1"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" role="menu" data-popper-placement="bottom-end">
                        <li><a href="javascript:void(0);">Monthly</a></li>
                        <li><a href="javascript:void(0);">Yearly</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body pb-0">
                {{-- <div class="d-flex ms-5">
                    <div>
                        <p class="mb-0 fs-15 text-muted">
                            This month
                        </p>
                        <span class="text-primary fs-20 fw-semibold"><i
                                class="fe fe-dollar-sign fs-13"></i>815,320</span>
                    </div>
                    <div class="ms-5">
                        <p class="mb-0 fs-15 text-muted">
                            Last month
                        </p>
                        <span class="fs-20 text-secondary fw-semibold"><i
                                class="fe fe-dollar-sign fs-13"></i>743,950</span>
                    </div>
                </div> --}}
                <div id="revenue_chart">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection
