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
    <title>Sparic – Creative Admin Multipurpose Responsive Bootstrap5 Dashboard HTML Template</title>

    <!--Bootstrap.min css-->
    <link id="style" rel="stylesheet" href="{{ url('/assets') }}/plugins/bootstrap/css/bootstrap.min.css">

    <!-- Style css -->
    <link href="{{ url('/assets') }}/css/style.css" rel="stylesheet">

    <!-- Plugin css -->
    <link href="{{ url('/assets') }}/css/plugins.css" rel="stylesheet">

    <!-- Animate css -->
    <link href="{{ url('/assets') }}/css/animated.css" rel="stylesheet">

    <!---Icons css-->
    <link href="{{ url('/assets') }}/css/icons.css" rel="stylesheet">

    <!-- Switcher css -->
    <link href="{{ url('/assets') }}/switcher/css/switcher.css" rel="stylesheet" id="switcher-css" type="text/css"
        media="all">
    <link href="{{ url('/assets') }}/switcher/demo.css" rel="stylesheet">

</head>

<body class="bg-account">

    <!-- Start Switcher -->
    <div class="switcher-wrapper">
        <div class="demo_changer">
            <div class="form_holder sidebar-right1">
                <div class="row">
                    <div class="predefined_styles">
                        <div class="swichermainleft text-center">
                            <div class="p-3 d-grid gap-2">
                                <a href="../../index.html" class="btn btn-primary mt-0">View Demo</a>
                                <a href="https://themeforest.net/item/sparic-html-admin-template/27368151"
                                    class="btn btn-info">Buy Now</a>
                                <a href="https://themeforest.net/user/spruko/portfolio" class="btn btn-danger">Our
                                    Portfolio</a>
                            </div>
                        </div>
                        <div class="swichermainleft text-center">
                            <h4>LTR AND RTL VERSIONS</h4>
                            <div class="skin-body">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">LTR</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch25"
                                                id="myonoffswitch54" class="onoffswitch2-checkbox" checked>
                                            <label for="myonoffswitch54" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">RTL</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch25"
                                                id="myonoffswitch55" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch55" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swichermainleft">
                            <h4>Theme Style</h4>
                            <div class="skin-body">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex">
                                        <span class="me-auto">Light Theme</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch1"
                                                id="myonoffswitch1" class="onoffswitch2-checkbox" checked>
                                            <label for="myonoffswitch1" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Dark Theme</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch1"
                                                id="myonoffswitch2" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch2" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swichermainleft switcher-nav">
                            <h4>Navigation Style</h4>
                            <div class="skin-body">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex">
                                        <span class="me-auto">Vertical Menu</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch15"
                                                id="myonoffswitch34" class="onoffswitch2-checkbox" checked>
                                            <label for="myonoffswitch34" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Horizantal Click Menu</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch15"
                                                id="myonoffswitch35" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch35" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Horizantal Hover Menu</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch15"
                                                id="myonoffswitch111" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch111" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swichermainleft">
                            <h4>Theme Primary Color</h4>
                            <div class="skin-body">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex">
                                        <span class="me-auto">Primary Color</span>
                                        <div class="">
                                            <input class="input-color-picker color-primary-light" value="#4454c3"
                                                id="colorID" oninput="changePrimaryColor()" type="color"
                                                data-id="bg-color" data-id1="bg-hover" data-id2="bg-border"
                                                name="lightPrimary">
                                        </div>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Background Color</span>
                                        <div class="">
                                            <input class="input-bg-picker background-primary-light" value="#373c56"
                                                id="bgID" oninput="changeBackgroundColor()" type="color"
                                                data-id3="body" data-id4="theme" name="BackgroundPrimary">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swichermainleft leftmenu-styles">
                            <h4>Menu Styles</h4>
                            <div class="skin-body">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex">
                                        <span class="me-auto">Light Menu</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch2"
                                                id="myonoffswitch3" class="onoffswitch2-checkbox" checked>
                                            <label for="myonoffswitch3" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Color Menu</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch2"
                                                id="myonoffswitch4" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch4" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Dark Menu</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch2"
                                                id="myonoffswitch5" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch5" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swichermainleft header-styles">
                            <h4>Header Styles</h4>
                            <div class="skin-body">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex">
                                        <span class="me-auto">Light Header</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch3"
                                                id="myonoffswitch6" class="onoffswitch2-checkbox" checked>
                                            <label for="myonoffswitch6" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Color Header</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch3"
                                                id="myonoffswitch7" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch7" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Dark Header</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch3"
                                                id="myonoffswitch8" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch8" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swichermainleft">
                            <h4>Layout Width Styles</h4>
                            <div class="skin-body">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex">
                                        <span class="me-auto">Full Width</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch4"
                                                id="myonoffswitch9" class="onoffswitch2-checkbox" checked>
                                            <label for="myonoffswitch9" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Boxed</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch4"
                                                id="myonoffswitch10" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch10" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swichermainleft switcher-layout">
                            <h4>Layout Positions</h4>
                            <div class="skin-body">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex">
                                        <span class="me-auto">Fixed</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch5"
                                                id="myonoffswitch11" class="onoffswitch2-checkbox" checked>
                                            <label for="myonoffswitch11" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Scrollable</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch5"
                                                id="myonoffswitch12" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch12" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swichermainleft vertical-switcher">
                            <h4>Sidemenu layout Styles</h4>
                            <div class="skin-body">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex">
                                        <span class="me-auto">Default Menu</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch6"
                                                id="myonoffswitch13" class="onoffswitch2-checkbox default-menu"
                                                checked>
                                            <label for="myonoffswitch13" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Closed Menu</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch6"
                                                id="myonoffswitch30" class="onoffswitch2-checkbox default-menu">
                                            <label for="myonoffswitch30" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Icon with Text</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch6"
                                                id="myonoffswitch14" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch14" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Icon Overlay</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch6"
                                                id="myonoffswitch15" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch15" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Hover Submenu</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch6"
                                                id="myonoffswitch32" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch32" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">Hover Submenu style 1</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch6"
                                                id="myonoffswitch33" class="onoffswitch2-checkbox">
                                            <label for="myonoffswitch33" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swichermainleft">
                            <h4>Reset All Styles</h4>
                            <div class="skin-body">
                                <div class="switch_section px-2">
                                    <button class="btn btn-danger btn-block"
                                        onclick="localStorage.clear();
                                        document.querySelector('html').style = '';
                                        names() ;
                                        resetData()"
                                        type="button">Reset All
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Switcher -->

    <!-- page -->
    <div class="page h-100">

        <!-- page-content -->
        <div class="page-content">
            <div class="container text-center text-dark">
                <div class="construction ">
                    <div class="text-dark">
                        <div class="card-body ">
                            <h2 class="font mb-4">Coming Soon</h2>
                            <h4 class="mt-5 text-dark">Our Website is under construction, follows us for more updates !
                            </h4>
                            <br>
                            <div id="count-down" class="center-block text-white mt-3">
                                <div class="clock-presenter days_dash">
                                    <div class="digit"></div>
                                    <div class="digit"></div>
                                    <p class="mt-2 text-dark">Days</p>
                                </div>
                                <div class="clock-presenter hours_dash">
                                    <div class="digit"></div>
                                    <div class="digit"></div>
                                    <p class="mt-2 text-dark">Hours</p>
                                </div>
                                <div class="clock-presenter minutes_dash">
                                    <div class="digit"></div>
                                    <div class="digit"></div>
                                    <p class="mt-2 text-dark">Minutes</p>
                                </div>
                                <div class="clock-presenter seconds_dash">
                                    <div class="digit"></div>
                                    <div class="digit"></div>
                                    <p class="mt-2 text-dark">Seconds</p>
                                </div>
                            </div>
                            <div class="input-group ms-auto me-auto mt-6 construction-btn">
                                <input type="text" class="form-control " placeholder=" Email-id">
                                <button type="button" class="btn btn-primary">
                                    Subscribe
                                </button>
                            </div>
                            <div class="mt-6 btn-list">
                                <button type="button" class="btn btn-icon btn-facebook"><i
                                        class="fa fa-facebook"></i></button>
                                <button type="button" class="btn btn-icon btn-google"><i
                                        class="fa fa-google"></i></button>
                                <button type="button" class="btn btn-icon btn-twitter"><i
                                        class="fa fa-twitter"></i></button>
                                <button type="button" class="btn btn-icon btn-dribbble"><i
                                        class="fa fa-dribbble"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- page-content end -->

    <!-- page End-->

    <!-- Jquery js-->
    <script src="{{ url('/assets') }}/js/vendors/jquery.min.js"></script>

    <!--Bootstrap.min js-->
    <script src="{{ url('/assets') }}/plugins/bootstrap/popper.min.js"></script>
    <script src="{{ url('/assets') }}/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!--Moment js-->
    <script src="{{ url('/assets') }}/plugins/moment/moment.min.js"></script>

    <!-- Count Down-->
    <script src="{{ url('/assets') }}/plugins/count-down/jquery.lwtCountdown-1.0.js"></script>
    <script src="{{ url('/assets') }}/plugins/count-down/count-down.js"></script>

    <!-- Color Theme js -->
    <script src="{{ url('/assets') }}/js/themeColors.js"></script>

    <!-- Sticky js -->
    <script src="{{ url('/assets') }}/js/sticky.js"></script>

    <!-- Custom js-->
    <script src="{{ url('/assets') }}/js/custom.js"></script>

</body>

</html>
