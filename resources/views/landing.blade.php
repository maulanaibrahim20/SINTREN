<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Zoufarm | Landing, Corporate &amp; Business Templatee</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/landing/assets/') }}/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ url('/landing/assets/') }}/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ url('/landing/assets/') }}/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('/landing/assets/') }}/img/favicons/favicon.ico">
    <link rel="manifest" href="{{ url('/landing/assets/') }}/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="{{ url('/landing/assets/') }}/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="{{ url('/landing/assets/') }}/css/theme.css" rel="stylesheet" />

</head>


<body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        {{-- navbar landing start --}}
        @include('landing.navbar')
        {{-- navbar landing end --}}


        {{-- header landing start --}}
        @include('landing.header')
        {{-- header landing end --}}


        @include('landing.opportunites')


        <!-- ============================================-->
        <!-- <section> begin ============================-->
        @include('landing.invest')
        <!-- <section> close ============================-->
        <!-- ============================================-->


        @include('landing.how_works')
        <section class="py-8" id="testimonial">
            <div class="container-lg">
                <div class="row flex-center">
                    <div class="col-12 col-lg-10 col-xl-12">
                        <div class="bg-holder"
                            style="background-image:url({{ url('/landing/assets/') }}/img/illustrations/testimonial-bg.png);background-position:top left;background-size:120px 83px;">
                        </div>
                        <!--/.bg-holder-->

                        <h6 class="fs-3 fs-lg-4 fw-bold lh-sm">What investors like you <br />are saying about Zou</h6>
                    </div>
                    <div class="carousel slide pt-3" id="carouselExampleDark" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active" data-bs-interval="10000">
                                <div class="row h-100 mx-3 mx-sm-5 mx-md-4 my-md-7 m-lg-7 mt-7">
                                    <div class="col-md-4 mb-5 mb-md-0">
                                        <div class="card h-100 shadow">
                                            <div class="card-body my-3">
                                                <div class="align-items-xl-center d-block d-xl-flex px-3"><img
                                                        class="img-fluid me-3 me-md-2 me-lg-3"
                                                        src="{{ url('/landing/assets/') }}/img/gallery/user-1.png"
                                                        width="50" alt="" />
                                                    <div class="flex-1 align-items-center pt-2">
                                                        <h5 class="mb-0 fw-bold text-success">Fernando Soler</h5>
                                                        <p class="fw-normal text-black">Telecommunication Engineer</p>
                                                    </div>
                                                </div>
                                                <p class="mb-0 px-3 px-md-2 px-xxl-3">&quot;Quis autem vel eum iure
                                                    reprehenderit qui in ea voluptate velit esse quam nihil molestiae
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-5 mb-md-0">
                                        <div class="card h-100 shadow">
                                            <div class="card-body my-3">
                                                <div class="align-items-xl-center d-block d-xl-flex px-3"><img
                                                        class="img-fluid me-3 me-md-2 me-lg-3"
                                                        src="{{ url('/landing/assets/') }}/img/gallery/user-2.png"
                                                        width="50" alt="" />
                                                    <div class="flex-1 align-items-center pt-2">
                                                        <h5 class="mb-0 fw-bold text-success">Ilone Pickford</h5>
                                                        <p class="fw-normal text-black">Head of Agrogofund </p>
                                                    </div>
                                                </div>
                                                <p class="mb-0 px-3 px-md-2 px-xxl-3">&quot;At vero eos et accusamus et
                                                    iusto odio dignissimos ducimus qui blanditiis praesentium </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-5 mb-md-0">
                                        <div class="card h-100 shadow">
                                            <div class="card-body my-3">
                                                <div class="align-items-xl-center d-block d-xl-flex px-3"><img
                                                        class="img-fluid me-3 me-md-2 me-lg-3"
                                                        src="{{ url('/landing/assets/') }}/img/gallery/user-3.png"
                                                        width="50" alt="" />
                                                    <div class="flex-1 align-items-center pt-2">
                                                        <h5 class="mb-0 fw-bold text-success">Ed O’Brien</h5>
                                                        <p class="fw-normal text-black">Herbalist</p>
                                                    </div>
                                                </div>
                                                <p class="mb-0 px-3 px-md-2 px-xxl-3">&quot;At vero eos et accusamus et
                                                    iusto odio dignissimos ducimus qui blanditiis praesentium </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="2000">
                                <div class="row h-100 mx-3 mx-sm-5 mx-md-4 my-md-7 m-lg-7 mt-7">
                                    <div class="col-md-4 mb-5 mb-md-0">
                                        <div class="card h-100 shadow">
                                            <div class="card-body my-3">
                                                <div class="align-items-xl-center d-block d-xl-flex px-3"><img
                                                        class="img-fluid me-3 me-md-2 me-lg-3"
                                                        src="{{ url('/landing/assets/') }}/img/gallery/user-1.png"
                                                        width="50" alt="" />
                                                    <div class="flex-1 align-items-center pt-2">
                                                        <h5 class="mb-0 fw-bold text-success">Fernando Soler</h5>
                                                        <p class="fw-normal text-black">Telecommunication Engineer</p>
                                                    </div>
                                                </div>
                                                <p class="mb-0 px-3 px-md-2 px-xxl-3">&quot;Quis autem vel eum iure
                                                    reprehenderit qui in ea voluptate velit esse quam nihil molestiae
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-5 mb-md-0">
                                        <div class="card h-100 shadow">
                                            <div class="card-body my-3">
                                                <div class="align-items-xl-center d-block d-xl-flex px-3"><img
                                                        class="img-fluid me-3 me-md-2 me-lg-3"
                                                        src="{{ url('/landing/assets/') }}/img/gallery/user-2.png"
                                                        width="50" alt="" />
                                                    <div class="flex-1 align-items-center pt-2">
                                                        <h5 class="mb-0 fw-bold text-success">Ilone Pickford</h5>
                                                        <p class="fw-normal text-black">Head of Agrogofund Groups </p>
                                                    </div>
                                                </div>
                                                <p class="mb-0 px-3 px-md-2 px-xxl-3">&quot;At vero eos et accusamus et
                                                    iusto odio dignissimos ducimus qui blanditiis praesentium </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-5 mb-md-0">
                                        <div class="card h-100 shadow">
                                            <div class="card-body my-3">
                                                <div class="align-items-xl-center d-block d-xl-flex px-3"><img
                                                        class="img-fluid me-3 me-md-2 me-lg-3"
                                                        src="{{ url('/landing/assets/') }}/img/gallery/user-3.png"
                                                        width="50" alt="" />
                                                    <div class="flex-1 align-items-center pt-2">
                                                        <h5 class="mb-0 fw-bold text-success">Ed O’Brien</h5>
                                                        <p class="fw-normal text-black">Herbalist</p>
                                                    </div>
                                                </div>
                                                <p class="mb-0 px-3 px-md-2 px-xxl-3">&quot;Ui dolorem eum fugiat quo
                                                    voluptas nulla pariatur? At vero eos et accusamus et iusto odio</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="row h-100 mx-3 mx-sm-5 mx-md-4 my-md-7 m-lg-7 mt-7">
                                    <div class="col-md-4 mb-5 mb-md-0">
                                        <div class="card h-100 shadow">
                                            <div class="card-body my-3">
                                                <div class="align-items-xl-center d-block d-xl-flex px-3"><img
                                                        class="img-fluid me-3 me-md-2 me-lg-3"
                                                        src="{{ url('/landing/assets/') }}/img/gallery/user-1.png"
                                                        width="50" alt="" />
                                                    <div class="flex-1 align-items-center pt-2">
                                                        <h5 class="mb-0 fw-bold text-success">Fernando Soler</h5>
                                                        <p class="fw-normal text-black">Telecommunication Engineer</p>
                                                    </div>
                                                </div>
                                                <p class="mb-0 px-3 px-md-2 px-xxl-3">&quot;Quis autem vel eum iure
                                                    reprehenderit qui in ea voluptate velit esse quam nihil molestiae
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-5 mb-md-0">
                                        <div class="card h-100 shadow">
                                            <div class="card-body my-3">
                                                <div class="align-items-xl-center d-block d-xl-flex px-3"><img
                                                        class="img-fluid me-3 me-md-2 me-lg-3"
                                                        src="{{ url('/landing/assets/') }}/img/gallery/user-2.png"
                                                        width="50" alt="" />
                                                    <div class="flex-1 align-items-center pt-2">
                                                        <h5 class="mb-0 fw-bold text-success">Ilone Pickford</h5>
                                                        <p class="fw-normal text-black">Head of Agrogofund Groups </p>
                                                    </div>
                                                </div>
                                                <p class="mb-0 px-3 px-md-2 px-xxl-3">&quot;At vero eos et accusamus et
                                                    iusto odio dignissimos ducimus qui blanditiis praesentium </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-5 mb-md-0">
                                        <div class="card h-100 shadow">
                                            <div class="card-body my-3">
                                                <div class="align-items-xl-center d-block d-xl-flex px-3"><img
                                                        class="img-fluid me-3 me-md-2 me-lg-3"
                                                        src="{{ url('/landing/assets/') }}/img/gallery/user-3.png"
                                                        width="50" alt="" />
                                                    <div class="flex-1 align-items-center pt-2">
                                                        <h5 class="mb-0 fw-bold text-success">Ed O’Brien</h5>
                                                        <p class="fw-normal text-black">Herbalist</p>
                                                    </div>
                                                </div>
                                                <p class="mb-0 px-3 px-md-2 px-xxl-3">&quot;Ui dolorem eum fugiat quo
                                                    voluptas nulla pariatur? At vero eos et accusamus et iusto odio</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row px-3 px-sm-6 px-md-0 px-lg-5 px-xl-4">
                            <div class="col-12 position-relative"><a
                                    class="carousel-control-prev carousel-icon z-index-2" href="#carouselExampleDark"
                                    role="button" data-bs-slide="prev"><span class="carousel-control-prev-icon"
                                        aria-hidden="true"></span><span class="visually-hidden">Previous</span></a><a
                                    class="carousel-control-next carousel-icon z-index-2" href="#carouselExampleDark"
                                    role="button" data-bs-slide="next"><span class="carousel-control-next-icon"
                                        aria-hidden="true"></span><span class="visually-hidden">Next</span></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ============================================-->
        <!-- <section> begin ============================-->
        <section class="z-index-1 cta">

            <div class="container">
                <div class="row flex-center">
                    <div class="col-12">
                        <div class="card shadow h-100 py-5">
                            <div class="card-body text-center">
                                <h1 class="fw-semi-bold mb-4">The future of &nbsp;<span class="text-success">Farm
                                        Investing</span> &nbsp; is Zou</h1><a class="btn btn-lg btn-success px-6"
                                    href="#" role="button">Invest Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of .container-->

        </section>
        <!-- <section> close ============================-->
        <!-- ============================================-->


        <section class="py-0" id="contact">
            <div class="bg-holder"
                style="background-image:url({{ url('/landing/assets/') }}/img/illustrations/footer-bg.png);background-position:center;background-size:cover;">
            </div>
            <!--/.bg-holder-->

            <div class="container">
                <div class="row justify-content-lg-between min-vh-75" style="padding-top:21rem">
                    <div class="col-6 col-sm-4 col-lg-auto mb-3">
                        <h6 class="mb-3 text-1000 fw-semi-bold">COMPANY </h6>
                        <ul class="list-unstyled mb-md-4 mb-lg-0">
                            <li class="mb-3"><a class="text-700 text-decoration-none" href="#!">About Us</a>
                            </li>
                            <li class="mb-3"><a class="text-700 text-decoration-none" href="#!">Team</a></li>
                            <li class="mb-3"><a class="text-700 text-decoration-none" href="#!">Careers</a>
                            </li>
                            <li class="mb-3"><a class="text-700 text-decoration-none" href="#!">Contact</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-auto mb-3">
                        <h6 class="mb-3 text-1000 fw-semi-bold">INVEST </h6>
                        <ul class="list-unstyled mb-md-4 mb-lg-0">
                            <li class="mb-3"><a class="text-700 text-decoration-none" href="#!">Features</a>
                            </li>
                            <li class="mb-3"><a class="text-700 text-decoration-none" href="#!">How it
                                    works</a></li>
                            <li class="mb-3"><a class="text-700 text-decoration-none" href="#!">Pricing</a>
                            </li>
                            <li class="mb-3"><a class="text-700 text-decoration-none" href="#!">Login</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-auto mb-3">
                        <h6 class="mb-3 text-1000 fw-semi-bold">LEGAL </h6>
                        <ul class="list-unstyled mb-md-4 mb-lg-0">
                            <li class="mb-3"><a class="text-700 text-decoration-none" href="#!">Privacy</a>
                            </li>
                            <li class="mb-3"><a class="text-700 text-decoration-none" href="#!">Terms</a></li>
                            <li class="mb-3"><a class="text-700 text-decoration-none" href="#!">Security</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-lg-auto mb-3">
                        <div class="card bg-success">
                            <div class="card-body p-sm-4">
                                <h5 class="text-white">Blog Zou</h5>
                                <p class="mb-0 text-white">write email to us<span
                                        class="text-white fs--1 fs-sm-1">info@zoufarm.com</span></p>
                                <button class="btn btn-light text-success" type="button">
                                    <svg class="bi bi-person me-1" xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" fill="#76C279" viewBox="0 0 16 16">
                                        <path
                                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z">
                                        </path>
                                    </svg>Sing In
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="text-300 mb-0" />
                <div class="row flex-center py-5">
                    <div class="col-12 col-sm-8 col-md-6 text-center text-md-start"> <a class="text-decoration-none"
                            href="#"><img class="d-inline-block align-top img-fluid"
                                src="{{ url('/landing/assets/') }}/img/gallery/logo-icon.png" alt=""
                                width="40" /><span class="text-theme font-monospace fs-3 ps-2">Zou</span></a>
                    </div>
                    <div class="col-12 col-sm-8 col-md-6">
                        <p class="fs--1 text-dark my-2 text-center text-md-end">&copy; This template is made with&nbsp;
                            <svg class="bi bi-suit-heart-fill" xmlns="http://www.w3.org/2000/svg" width="16"
                                height="16" fill="#76C279" viewBox="0 0 16 16">
                                <path
                                    d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z">
                                </path>
                            </svg>&nbsp;by&nbsp;<a class="text-dark" href="https://themewagon.com/"
                                target="_blank">ThemeWagon </a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->



    @include('landing.component.style_js')
</body>

</html>
