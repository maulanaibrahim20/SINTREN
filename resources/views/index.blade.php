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
    <link rel="icon" href="{{ url('/assets') }}/images/brand/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('/assets') }}/images/brand/favicon.ico">

    <!-- Title -->
    <title>@yield('title')</title>

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
            <div class="contain er-fluid bg-white news-ticker">
                <div class="bg-white">
                    <div class="best-ticker" id="newsticker">
                        <div class="bn-news">
                            <ul>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-users bg-danger-transparent text-danger mx-1"></span>
                                    <span class="d-inline-block">Total Users</span>
                                    <span class="bn-positive me-4">1,653</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-signal bg-info-transparent text-info mx-1"></span>
                                    <span class="d-inline-block">Total Leads</span>
                                    <span class="bn-negative me-4">639</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-briefcase bg-success-transparent text-success mx-1"></span>
                                    <span class="d-inline-block"> Total Trials </span>
                                    <span class="bn-negative me-4">12,765</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-trophy bg-warning-transparent text-warning mx-1"></span>
                                    <span class="d-inline-block">Total Wins</span>
                                    <span class="bn-positive me-4">24</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-envelope bg-primary-transparent text-primary mx-1"></span>
                                    <span class="d-inline-block">Active Email Accounts</span>
                                    <span class="bn-positive me-4">74,526</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-check-circle bg-danger-transparent text-danger mx-1"></span>
                                    <span class="d-inline-block">Active Requests</span>
                                    <span class="bn-positive me-4">14,526</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-envelope bg-secondary-transparent text-secondary mx-1"></span>
                                    <span class="d-inline-block">Deactive Email Accounts</span>
                                    <span class="bn-positive me-4">7,325 </span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-times-circle bg-info-transparent text-info mx-1"></span>
                                    <span class="d-inline-block">Deactive Requests</span>
                                    <span class="bn-positive me-4"> 1,425 </span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-usd bg-success-transparent text-success mx-1"></span>
                                    <span class="d-inline-block">Total Balance</span>
                                    <span class="bn-negative me-4">$1,52,654</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-shopping-cart bg-danger-transparent text-danger mx-1"></span>
                                    <span class="d-inline-block">Total Sales</span>
                                    <span class="bn-negative me-4">23,15,2654</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-money bg-warning-transparent text-warning"></span>
                                    <span class="d-inline-block">Total Purchase</span>
                                    <span class="bn-positive me-4">$7,483</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-usd bg-danger-transparent text-danger mx-1"></span>
                                    <span class="d-inline-block">Total Cost Reduction</span>
                                    <span class="bn-negative me-4">$23,567</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-money bg-primary-transparent text-primary mx-1"></span>
                                    <span class="d-inline-block">Total Cost Savings</span>
                                    <span class="bn-negative me-4">15.2%</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-briefcase bg-info-transparent text-info mx-1"></span>
                                    <span class="d-inline-block">Total Projects</span>
                                    <span class="bn-positive me-4">3,456</span>
                                </li>
                                <li class="text-muted fs-13 fw-semibold">
                                    <span class="fa fa-users bg-success-transparent text-success mx-1"></span>
                                    <span class="d-inline-block">Total Employes</span>
                                    <span class="bn-positive me-4">4,738</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
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

        <!-- Right-sidebar-->
        {{-- <div class="sidebar sidebar-right sidebar-animate">
            <div class="panel panel-primary card mb-0 box-shadow">
                <div class="px-4 py-3 sidebar-icon d-flex align-items-center justify-content-between bg-primary-light">
                    <h6 class="mb-0 fw-semibold">SIDEBAR-MENU</h6>
                    <a href="javascript:void(0)" class="text-end float-end" data-bs-toggle="sidebar-right"
                        data-bs-target=".sidebar-right"><i class="fe fe-x"></i></a>
                </div>
                <div class="tab-menu-heading siderbar-tabs border-0">
                    <div class="tabs-menu">
                        <!-- Tabs -->
                        <ul class="nav panel-tabs">
                            <li class=""><a href="#tab1" class="active" data-bs-toggle="tab"><i
                                        class="fe fe-mail me-1"></i>Chat</a></li>
                            <li><a href="#tab2" data-bs-toggle="tab"><i
                                        class="fe fe-activity me-1"></i>Activity</a></li>
                            <li><a href="#tab3" data-bs-toggle="tab"><i class="fe fe-edit-3 me-1"></i>Todo</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="panel-body tabs-menu-body side-tab-body p-0 border-0 ">
                    <div class="tab-content border-top">
                        <div class="tab-pane active" id="tab1">
                            <div class="chat">
                                <div>
                                    <div class="input-group p-4">
                                        <input type="text" placeholder="Search..." class="form-control search">
                                        <button type="button" class="btn btn-primary">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="px-4 text-dark fw-semibold">Today</div>
                                    <ul class="mb-0 list-group list-group-flush">
                                        <li class="list-group-item d-flex border-0">
                                            <div class="d-flex">
                                                <span class="avatar brround avatar-md cover-image"
                                                    data-bs-image-src="{{ url('/assets') }}/images/users/male/32.jpg">
                                                    <span class="avatar-status bg-green"></span>
                                                </span>
                                                <a href="chat.html" class="ms-2 p-0">
                                                    <h6 class="mt-1 mb-0 fw-semibold">Maryam Naz</h6>
                                                    <small class="text-muted fs-12">Thanks, maryam! talk later</small>
                                                </a>
                                            </div>
                                            <div class="ms-auto text-end mt-1">
                                                <h6 class="text-dark fw-semibold fs-12 mb-0">08:20 PM</h6>
                                                <span
                                                    class="p-1 fs-7 lh-1 bg-success fws-emibold rounded-circle">24</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex border-0">
                                            <div class="d-flex">
                                                <span class="avatar brround avatar-md cover-image"
                                                    data-bs-image-src="{{ url('/assets') }}/images/users/female/1.jpg">
                                                </span>
                                                <a href="chat.html" class="ms-2 p-0">
                                                    <h6 class="mt-1 mb-0 fw-semibold">Sahar Darya</h6>
                                                    <small class="text-muted fs-12">No rush meet! lets go</small>
                                                </a>
                                            </div>
                                            <div class="ms-auto text-end">
                                                <span class="text-dark fw-semibold fs-12">08:00 PM</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex border-0">
                                            <div class="d-flex">
                                                <span class="avatar brround avatar-md cover-image"
                                                    data-bs-image-src="{{ url('/assets') }}/images/users/female/23.jpg">
                                                    <span class="avatar-status bg-green"></span>
                                                </span>
                                                <a href="chat.html" class="ms-2 p-0">
                                                    <h6 class="mt-1 mb-0 fw-semibold">Maryam Naz</h6>
                                                    <small class="text-muted fs-12">Okay. I'll tell him about
                                                        it!</small>
                                                </a>
                                            </div>
                                            <div class="ms-auto text-end">
                                                <span class="text-dark fw-semibold fs-12">07:40 PM</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex border-0">
                                            <div class="d-flex">
                                                <span class="avatar brround avatar-md cover-image"
                                                    data-bs-image-src="{{ url('/assets') }}/images/users/female/25.jpg">
                                                </span>
                                                <a href="chat.html" class="ms-2 p-0">
                                                    <h6 class="mt-1 mb-0 fw-semibold">Yolduz Rafi</h6>
                                                    <small class="text-muted fs-12">I will text you later.</small>
                                                </a>
                                            </div>
                                            <div class="ms-auto text-end">
                                                <span class="text-dark fw-semibold fs-12">07:20 PM</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex border-0">
                                            <div class="d-flex">
                                                <span class="avatar brround avatar-md cover-image"
                                                    data-bs-image-src="{{ url('/assets') }}/images/users/male/33.jpg">
                                                    <span class="avatar-status bg-green"></span>
                                                </span>
                                                <a href="chat.html" class="ms-2 p-0">
                                                    <h6 class="mt-1 mb-0 fw-semibold">Nargis Hawa</h6>
                                                    <small class="text-muted fs-12">Yesterday we make fun a
                                                        lot..</small>
                                                </a>
                                            </div>
                                            <div class="ms-auto text-end mt-1">
                                                <h6 class="text-dark fw-semibold fs-12 mb-0">07:00 PM</h6>
                                                <span
                                                    class="p-1 fs-7 lh-1 bg-success fws-emibold rounded-circle">10</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex border-0">
                                            <div class="d-flex">
                                                <span class="avatar brround avatar-md cover-image"
                                                    data-bs-image-src="{{ url('/assets') }}/images/users/male/15.jpg">
                                                </span>
                                                <a href="chat.html" class="ms-2 p-0">
                                                    <h6 class="mt-1 mb-0 fw-semibold">Khadija Mehr</h6>
                                                    <small class="text-muted fs-12">Hey! buddy what's up...</small>
                                                </a>
                                            </div>
                                            <div class="ms-auto text-end">
                                                <span class="text-dark fw-semibold fs-12">06:10 PM</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex border-0">
                                            <div class="d-flex">
                                                <span class="avatar brround avatar-md cover-image"
                                                    data-bs-image-src="{{ url('/assets') }}/images/users/female/14.jpg">
                                                    <span class="avatar-status bg-green"></span>
                                                </span>
                                                <a href="chat.html" class="ms-2 p-0">
                                                    <h6 class="mt-1 mb-0 fw-semibold">Khadija Mehr</h6>
                                                    <small class="text-muted fs-12">Yeah! I knew..!</small>
                                                </a>
                                            </div>
                                            <div class="ms-auto text-end mt-1">
                                                <h6 class="text-dark fw-semibold fs-12 mb-0">05:20 PM</h6>
                                                <span
                                                    class="p-1 fs-7 lh-1 bg-success fws-emibold rounded-circle">06</span>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="px-4 text-dark fw-semibold">Yesterday</div>
                                    <ul class="mb-0 list-group list-group-flush">
                                        <li class="list-group-item d-flex border-0">
                                            <div class="d-flex">
                                                <span class="avatar brround avatar-md cover-image"
                                                    data-bs-image-src="{{ url('/assets') }}/images/users/male/10.jpg">
                                                </span>
                                                <a href="chat.html" class="ms-2 p-0">
                                                    <h6 class="mt-1 mb-0 fw-semibold">Rishab</h6>
                                                    <small class="text-muted fs-12">I have to go...!</small>
                                                </a>
                                            </div>
                                            <div class="ms-auto text-end">
                                                <span class="text-dark fw-semibold fs-12">11:20 AM</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex border-0">
                                            <div class="d-flex">
                                                <span class="avatar brround avatar-md cover-image"
                                                    data-bs-image-src="{{ url('/assets') }}/images/users/female/1.jpg">
                                                </span>
                                                <a href="chat.html" class="ms-2 p-0">
                                                    <h6 class="mt-1 mb-0 fw-semibold">Scarlet</h6>
                                                    <small class="text-muted fs-12">Hey! there I' am
                                                        available....</small>
                                                </a>
                                            </div>
                                            <div class="ms-auto text-end">
                                                <span class="text-dark fw-semibold fs-12">10:00 AM</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex border-0">
                                            <div class="d-flex">
                                                <span class="avatar brround avatar-md cover-image"
                                                    data-bs-image-src="{{ url('/assets') }}/images/users/female/9.jpg">
                                                </span>
                                                <a href="chat.html" class="ms-2 p-0">
                                                    <h6 class="mt-1 mb-0 fw-semibold">Willson</h6>
                                                    <small class="text-muted fs-12">Today I completed my
                                                        work.!</small>
                                                </a>
                                            </div>
                                            <div class="ms-auto text-end">
                                                <span class="text-dark fw-semibold fs-12">09:50 AM</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex border-0">
                                            <div class="d-flex">
                                                <span class="avatar brround avatar-md cover-image"
                                                    data-bs-image-src="{{ url('/assets') }}/images/users/female/11.jpg">
                                                </span>
                                                <a href="chat.html" class="ms-2 p-0">
                                                    <h6 class="mt-1 mb-0 fw-semibold">Yolduz Rafi</h6>
                                                    <small class="text-muted fs-12">Okay...I will be wait for
                                                        you</small>
                                                </a>
                                            </div>
                                            <div class="ms-auto text-end">
                                                <span class="text-dark fw-semibold fs-12">09:20 AM</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <div class="text-dark fw-semibold bg-light px-4 py-2">Today</div>
                            <ul class="list-group list-group-flush mb-2 mt-4">
                                <li class="list-group-item d-flex border-0">
                                    <div>
                                        <i class="fe fe-file-plus p-3 fs-6 bg-primary-transparent rounded-circle"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold">
                                            New websites is created
                                        </h6>
                                        <span class="text-muted fs-13">
                                            <i class="mdi mdi-clock me-1"></i>36s
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex border-0">
                                    <div>
                                        <i class="fe fe-briefcase p-3 fs-6 bg-danger-transparent rounded-circle"></i>
                                    </div>
                                    <div class=" w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold">
                                            Prepare for the next project
                                        </h6>
                                        <span class="text-muted fs-13">
                                            <i class="mdi mdi-clock ed me-1"></i>2 mins
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex border-0">
                                    <div>
                                        <i class="fe fe-clock p-3 fs-6 bg-info-transparent rounded-circle"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold">
                                            Decide the live discussion time
                                        </h6>
                                        <span class="text-muted fs-13">
                                            <i class="mdi mdi-clock me-1"></i>10 mins
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex border-0">
                                    <div>
                                        <i class="fe fe-users p-3 fs-6 bg-success-transparent rounded-circle"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold">
                                            Team review meeting at yesterday at 3:00 pm
                                        </h6>
                                        <span class="text-muted fs-13">
                                            <i class="mdi mdi-clock me-1"></i>1 hr
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex border-0">
                                    <div>
                                        <i class="fe fe-book-open p-3 fs-6 bg-pink-transparent rounded-circle"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold">
                                            Prepare for presentation
                                        </h6>
                                        <span class="text-muted fs-12">
                                            <i class="mdi mdi-clock me-1"></i>3 hr
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex border-0">
                                    <div>
                                        <i
                                            class="fe fe-check-circle p-3 fs-6 bg-purple-transparent rounded-circle"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold">
                                            Willson jake was created a task
                                        </h6>
                                        <span class="text-muted">
                                            <i class="mdi mdi-clock me-1"></i>5 hr
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex border-0">
                                    <div>
                                        <i class="fe fe-mail p-3 fs-6 bg-orange-transparent rounded-circle"></i>
                                    </div>
                                    <div class=" w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold">
                                            Barina kilton commented on your designs
                                        </h6>
                                        <span class="text-muted fs-13">
                                            <i class="mdi mdi-clock me-1"></i>10 hr
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex border-0">
                                    <div>
                                        <i class="fe fe-zap p-3 fs-6 bg-secondary-transparent rounded-circle"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold">
                                            Juline klept shared a file-attachments
                                        </h6>
                                        <span class="text-muted fs-13">
                                            <i class="mdi mdi-clock text-muted me-1"></i>12 hr
                                        </span>
                                    </div>
                                </li>
                            </ul>

                            <div class="text-dark fw-semibold bg-light px-4 py-2">Last 7 Days</div>
                            <ul class="list-group list-group-flush mt-4">
                                <li class="list-group-item d-flex border-0">
                                    <div>
                                        <i class="fe fe-calendar p-3 fs-6 bg-primary-transparent rounded-circle"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold">
                                            Robert veltz was completed project
                                        </h6>
                                        <span class="text-muted fs-13">
                                            <i class="mdi mdi-clock me-1"></i>14 May
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex border-0">
                                    <div>
                                        <i class="fe fe-user-check p-3 fs-6 bg-danger-transparent rounded-circle"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold">
                                            Completed for the next meeting
                                        </h6>
                                        <span class="text-muted fs-13">
                                            <i class="mdi mdi-clock me-1"></i>16 May
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex border-0">
                                    <div>
                                        <i class="fe fe-clock p-3 fs-6 bg-info-transparent rounded-circle"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold">
                                            Decide the live discussion time
                                        </h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted fs-13">
                                                <i class="mdi mdi-clock me-1"></i>20 May
                                            </span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex border-0">
                                    <div>
                                        <i class="fe fe-users p-3 fs-6 bg-success-transparent rounded-circle"></i>
                                    </div>
                                    <div class=" w-100 ms-3">
                                        <h6 class="mb-0 fw-semibold">
                                            Team review meeting at yesterday at 3:00 pm
                                        </h6>
                                        <span class="text-muted fs-13">
                                            <i class="mdi mdi-clock me-1"></i>22 May
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane" id="tab3">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="custom-control-input" name="example-checkbox1"
                                            value="option1" checked="">
                                        <span class="custom-control-label fw-semibold fw-semibold">Do Even
                                            More..</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="custom-control-input" name="example-checkbox2"
                                            value="option2" checked="">
                                        <span class="custom-control-label fw-semibold">Find an idea.</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="custom-control-input" name="example-checkbox3"
                                            value="option3" checked="">
                                        <span class="custom-control-label fw-semibold">Hangout with friends</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="custom-control-input" name="example-checkbox4"
                                            value="option4">
                                        <span class="custom-control-label fw-semibold">Do Something else</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="custom-control-input" name="example-checkbox5"
                                            value="option5">
                                        <span class="custom-control-label fw-semibold">Eat healthy, Eat Fresh..</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="custom-control-input" name="example-checkbox6"
                                            value="option6" checked="">
                                        <span class="custom-control-label fw-semibold">Finsh something more..</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="custom-control-input" name="example-checkbox7"
                                            value="option7" checked="">
                                        <span class="custom-control-label fw-semibold">Do something more</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="custom-control-input" name="example-checkbox8"
                                            value="option8">
                                        <span class="custom-control-label fw-semibold">Updated more files</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="custom-control-input" name="example-checkbox9"
                                            value="option9">
                                        <span class="custom-control-label fw-semibold">System updated</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="list-group-item border-bottom d-flex">
                                    <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="custom-control-input" name="example-checkbox10"
                                            value="option10">
                                        <span class="custom-control-label fw-semibold">Settings Changings...</span>
                                    </label>
                                    <div class="ms-auto">
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-edit text-primary me-2"></i>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <i class="fe fe-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                            <div class="text-center p-4">
                                <a href="javascript:void(0);" class="btn btn-primary btn-block">Upgrade more</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- End Rightsidebar-->

        <!--footer-->
        @include('template.footer')
        <!-- End Footer-->

    </div>
    <!-- End Page -->

    <!-- Back to top -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    @include('template.component.style_js')
    @include('sweetalert::alert')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @yield('script')

</body>

</html>
