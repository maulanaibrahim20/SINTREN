<header id="header" class="fixed-top">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <img src="{{ url('/landings') }}/img/imy.png"
                style="height: 50px; width: auto; margin-right: 5px; margin-top:5px;" alt="Logo Sintren">
            <img src="{{ url('/landings') }}/img/polindra.png"
                style="height: 50px; width: auto; margin-right: 10px; margin-top:5px;" alt="Logo Sintren">
            <div class="col-xl-9 d-flex align-items-center justify-content-lg-between">
                <h1 class="logo me-auto me-lg-0"><a href="{{ url('/') }}">Sintren</a></h1>
                <!-- Uncomment below if you prefer to use an image logo -->
                <!-- <a href="index.html" class="logo me-auto me-lg-0"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
                <nav id="navbar" class="navbar order-last order-lg-0">
                    <ul>
                        <li><a class="nav-link scrollto active" href="#hero">Dashboard</a></li>
                        <li><a class="nav-link scrollto" href="#about">Tentang</a></li>
                        <li><a class="nav-link scrollto" href="#features">Trend Pertanian</a></li>
                        <li><a class="nav-link scrollto" href="#pangan">Trend Pangan</a></li>
                        <li><a class="nav-link scrollto" href="#cta">Mobile Application</a></li>
                        <li><a class="nav-link scrollto" href="#contact">Kontak Kami</a></li>
                    </ul>
                    <i class="bi bi-list mobile-nav-toggle"></i>
                </nav><!-- .navbar -->

                <a href="{{ url('/login') }}" class="get-started-btn scrollto"><i class="fa fa-sign-in"></i> Login</a>
            </div>
        </div>
    </div>
</header>
