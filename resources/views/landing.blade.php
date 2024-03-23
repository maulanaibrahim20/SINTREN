<!DOCTYPE html>
<html lang="en">

<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>Agropro</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    @include('landing.component.style_css')
</head>
<!-- body -->

<body class="main-layout">
    <!-- loader  -->
    <div class="loader_bg">
        <div class="loader"><img src="{{ url('/landing') }}/images/loading.gif" alt="#" /></div>
    </div>
    <!-- end loader -->
    <div class="full_bg">
        <!-- header -->
        @include('landing.header')
        <!-- end header inner -->
        <!-- top -->
        <div class="slider_main">
            <!-- carousel code -->
            @include('landing.carousel')
            {{-- end carousel code --}}
        </div>
    </div>
    <!-- end banner -->
    <!-- about -->
    @include('landing.about')
    <!-- end about -->
    <!-- services -->
    @include('landing.services')
    <!-- end services -->
    <!-- customers -->
    @include('landing.customers')
    <!-- end customers -->
    <!-- choose -->
    @include('landing.choose')
    <!-- choose -->
    <!-- news -->
    @include('landing.news')
    <!-- end news -->
    <!-- contact -->
    @include('landing.contact')
    <!-- end contact -->
    <!--  footer -->
    @include('landing.footer')
    <!-- end footer -->
    @include('landing.component.style_js')
</body>

</html>
