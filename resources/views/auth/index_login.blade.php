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
    <link rel="icon" href="{{ url('/landings') }}/img/favicon_io/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('/landings') }}/img/favicon_io/logo.png">

    <!-- Title -->
    <title> Sintrenayu | @yield('title')</title>

    @include('auth.component.style_css')
    @yield('css')

</head>

<body class="bg-account app sidebar-mini ltr">
    <!--Global-Loader-->
    <div id="global-loader">
        <img src="{{ url('/assets') }}/images/loader.svg" alt="loader">
    </div>

    <!-- page -->
    <div class="page h-100">

        <!-- page-content -->
        @yield('content')
        <!-- page-content end -->

    </div>
    <!-- page End-->
    @yield('script')
    @include('auth.component.style_js')
    @include('sweetalert::alert')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @if (session('success'))
        <script type="text/javascript">
            Swal.fire({
                title: "Berhasil",
                text: "{{ session('success') }}",
                icon: "success"
            });
        </script>
    @endif
    @if (session('error'))
        <script type="text/javascript">
            Swal.fire({
                title: "{{ session('error') }}",
                icon: "error"
            });
        </script>
    @endif


</body>

</html>
