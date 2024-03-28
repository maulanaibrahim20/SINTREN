@extends('index')
@section('title', 'Dashboard UPTD | UPTD')
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
        <div class="col-xl-7 col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header custom-header d-flex justify-content-between align-items-center border-bottom">
                    <h3 class="card-title">Trend Pertanian</h3>
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
                    <div class="d-flex ms-5">
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
                    </div>
                    <div id="revenue_chart">
                    </div>
                </div>
            </div>
        </div>
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
