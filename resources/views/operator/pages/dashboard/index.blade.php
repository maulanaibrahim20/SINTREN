@extends('index')
@section('title', 'Dashboard Operator')
@section('content')
    <div class="page-header d-sm-flex d-block">
        <ol class="breadcrumb mb-sm-0 mb-3">
            <!-- breadcrumb -->
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard01</li>
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
        <div class="col-sm-12 col-lg-6 col-md-6 col-xxl-3">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-0 fw-semibold text-dark lh-1">Employees</p>
                                    <div class="fs-12 text-muted mb-5">Overview of this month</div>
                                    <div class="fs-30 fw-semibold mb-0 lh-1">{{ $user }}
                                    </div>
                                </div>
                                <div class="text-end d-flex flex-column align-items-center">
                                    <label class="custom-switch mb-5">
                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                    </label>
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
                                    <p class="mb-0 fw-semibold text-dark lh-1">Task</p>
                                    <div class="fs-12 text-muted mb-5">Overview of this month</div>
                                    <div class="fs-30 fw-semibold mb-0 lh-1">{{ $unverifiedUsersCount }}
                                    </div>
                                </div>
                                <div class="text-end d-flex flex-column align-items-center">
                                    <label class="custom-switch mb-5">
                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                    <span class="text-warning lh-1 mt-3 fs-26"><i class="fe fe-file-text"></i></span>
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
                                    <p class="mb-0 fw-semibold text-dark lh-1">Earnings</p>
                                    <div class="fs-12 text-muted mb-5">Overview of this month</div>
                                    <div class="fs-30 fw-semibold mb-0 lh-1">{{ $verifiedUsersCount }} <i
                                            class=""></i>
                                    </div>
                                </div>
                                <div class="text-end d-flex flex-column align-items-center">
                                    <label class="custom-switch mb-5">
                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                    <span class="text-danger lh-1 mt-3 fs-26"><i class="fe fe-external-link"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
