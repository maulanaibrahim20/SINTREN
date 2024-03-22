<nav class="navbar navbar-expand-lg navbar-light fixed-top py-3 bg-light opacity-85"
    data-navbar-on-scroll="data-navbar-on-scroll">
    <div class="container"><a class="navbar-brand" href="index.html"><img class="d-inline-block align-top img-fluid"
                src="{{ url('/landing/assets/') }}/img/gallery/logo-icon.png" alt="" width="50" /><span
                class="text-theme font-monospace fs-4 ps-2">Zou</span></a>
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item px-2"><a class="nav-link fw-medium active" aria-current="page"
                        href="#header">Home</a></li>
                <li class="nav-item px-2"><a class="nav-link fw-medium" href="#Opportuanities">Opportuanities</a></li>
                <li class="nav-item px-2"><a class="nav-link fw-medium" href="#testimonial">Testimonial</a></li>
                <li class="nav-item px-2"><a class="nav-link fw-medium" href="#invest">Invest</a></li>
                <li class="nav-item px-2"><a class="nav-link fw-medium" href="#contact">Contact </a></li>
            </ul>
            <form class="d-flex">
                <a href="{{ route('login.index') }}" class="btn btn-lg btn-dark bg-gradient order-0">Sign Up</a>
            </form>
        </div>
    </div>
</nav>
