<!DOCTYPE html>
<!--[if IE 9]>
<html class="ie9 no-focus" lang="en"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-focus" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>SPECifIC - Sub-Pathway Extractor and Enricher</title>
    <meta name="description"
          content="SPECifIC - Sub-Pathway Extractor and Enricher">
    <meta name="author" content="S. Alaimo, Ph.D.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <!-- <link rel="shortcut icon" href="assets/img/favicons/favicon.png"> -->
    <!-- END Icons -->
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ elixir('css/all.css') }}">
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
</head>
<body>
<!-- Page Container -->
<!--
    Available Classes:

    'enable-cookies'             Remembers active color theme between pages (when set through color theme list)

    'sidebar-l'                  Left Sidebar and right Side Overlay
    'sidebar-r'                  Right Sidebar and left Side Overlay
    'sidebar-mini'               Mini hoverable Sidebar (> 991px)
    'sidebar-o'                  Visible Sidebar by default (> 991px)
    'sidebar-o-xs'               Visible Sidebar by default (< 992px)

    'side-overlay-hover'         Hoverable Side Overlay (> 991px)
    'side-overlay-o'             Visible Side Overlay by default (> 991px)

    'side-scroll'                Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (> 991px)

    'header-navbar-fixed'        Enables fixed header
    'header-navbar-transparent'  Enables a transparent header (if also fixed, it will get a solid dark background color on scrolling)
-->
<div id="page-container"
     class="sidebar-l sidebar-mini sidebar-o side-scroll header-navbar-fixed header-navbar-transparent">
    <!-- Sidebar -->
    <nav id="sidebar">
        <!-- Sidebar Scroll Container -->
        <div id="sidebar-scroll">
            <!-- Sidebar Content -->
            <!-- Adding .sidebar-mini-hide to an element will hide it when the sidebar is in mini mode -->
            <div class="sidebar-content">
                <!-- Side Header -->
                <div class="side-header side-content">
                    <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                    <button class="btn btn-link text-gray pull-right visible-xs visible-sm" type="button"
                            data-toggle="layout" data-action="sidebar_close">
                        <i class="fa fa-times"></i>
                    </button>
                    <!-- Themes functionality initialized in App() -> uiHandleTheme() -->
                    <div class="btn-group pull-right visible-md visible-lg">
                        <button class="btn btn-link text-gray dropdown-toggle" data-toggle="dropdown" type="button">
                            <i class="si si-drop"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right font-s13 sidebar-mini-hide">
                            <li>
                                <a data-toggle="theme" data-theme="default" tabindex="-1" href="javascript:void(0)">
                                    <i class="fa fa-circle text-default pull-right"></i> <span
                                            class="font-w600">Default</span>
                                </a>
                            </li>
                            <li>
                                <a data-toggle="theme" data-theme="assets/css/themes/amethyst.min.css" tabindex="-1"
                                   href="javascript:void(0)">
                                    <i class="fa fa-circle text-amethyst pull-right"></i> <span class="font-w600">Amethyst</span>
                                </a>
                            </li>
                            <li>
                                <a data-toggle="theme" data-theme="assets/css/themes/city.min.css" tabindex="-1"
                                   href="javascript:void(0)">
                                    <i class="fa fa-circle text-city pull-right"></i> <span
                                            class="font-w600">City</span>
                                </a>
                            </li>
                            <li>
                                <a data-toggle="theme" data-theme="assets/css/themes/flat.min.css" tabindex="-1"
                                   href="javascript:void(0)">
                                    <i class="fa fa-circle text-flat pull-right"></i> <span
                                            class="font-w600">Flat</span>
                                </a>
                            </li>
                            <li>
                                <a data-toggle="theme" data-theme="assets/css/themes/modern.min.css" tabindex="-1"
                                   href="javascript:void(0)">
                                    <i class="fa fa-circle text-modern pull-right"></i> <span
                                            class="font-w600">Modern</span>
                                </a>
                            </li>
                            <li>
                                <a data-toggle="theme" data-theme="assets/css/themes/smooth.min.css" tabindex="-1"
                                   href="javascript:void(0)">
                                    <i class="fa fa-circle text-smooth pull-right"></i> <span
                                            class="font-w600">Smooth</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a class="h5 text-white" href="index.html">
                        <i class="fa fa-circle-o-notch text-primary"></i> <span class="h4 font-w600 sidebar-mini-hide">ne</span>
                    </a>
                </div>
                <!-- END Side Header -->

                <!-- Side Content -->
                <div class="side-content">
                    <ul class="nav-main">
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-home"></i><span
                                        class="sidebar-mini-hide">Home</span></a>
                            <ul>
                                <li>
                                    <a href="frontend_home.html">Default Navigation</a>
                                </li>
                                <li>
                                    <a href="frontend_home_header_nav.html">Header Navigation</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="frontend_features.html"><i class="si si-energy"></i><span
                                        class="sidebar-mini-hide">Features</span></a>
                        </li>
                        <li>
                            <a class="active" href="frontend_pricing.html"><i class="si si-wallet"></i><span
                                        class="sidebar-mini-hide">Pricing</span></a>
                        </li>
                        <li>
                            <a href="frontend_contact.html"><i class="si si-envelope-open"></i><span
                                        class="sidebar-mini-hide">Contact</span></a>
                        </li>
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i
                                        class="si si-book-open"></i><span class="sidebar-mini-hide">Pages</span></a>
                            <ul>
                                <li>
                                    <a href="frontend_team.html">Team</a>
                                </li>
                                <li>
                                    <a href="frontend_support.html">Support</a>
                                </li>
                                <li>
                                    <a href="frontend_search.html">Search</a>
                                </li>
                                <li>
                                    <a href="frontend_about.html">About</a>
                                </li>
                                <li>
                                    <a href="frontend_login.html">Log In</a>
                                </li>
                                <li>
                                    <a href="frontend_signup.html">Sign Up</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-main-heading"><span class="sidebar-mini-hide">Pages Packs</span></li>
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-pencil"></i><span
                                        class="sidebar-mini-hide">Blog</span></a>
                            <ul>
                                <li>
                                    <a href="frontend_blog_classic.html">Classic</a>
                                </li>
                                <li>
                                    <a href="frontend_blog_list.html">List</a>
                                </li>
                                <li>
                                    <a href="frontend_blog_grid.html">Grid</a>
                                </li>
                                <li>
                                    <a href="frontend_blog_story.html">Story</a>
                                </li>
                                <li>
                                    <a href="frontend_blog_story_cover.html">Story Cover</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i
                                        class="si si-graduation"></i><span
                                        class="sidebar-mini-hide">e-Learning</span></a>
                            <ul>
                                <li>
                                    <a href="frontend_elearning_courses.html">Courses</a>
                                </li>
                                <li>
                                    <a href="frontend_elearning_course.html">Course</a>
                                </li>
                                <li>
                                    <a href="frontend_elearning_lesson.html">Lesson</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-bag"></i><span
                                        class="sidebar-mini-hide">e-Commerce</span></a>
                            <ul>
                                <li>
                                    <a href="frontend_ecom_home.html">Home</a>
                                </li>
                                <li>
                                    <a href="frontend_ecom_search.html">Search Results</a>
                                </li>
                                <li>
                                    <a href="frontend_ecom_products.html">Products List</a>
                                </li>
                                <li>
                                    <a href="frontend_ecom_product.html">Product Page</a>
                                </li>
                                <li>
                                    <a href="frontend_ecom_checkout.html">Checkout</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-plane"></i><span
                                        class="sidebar-mini-hide">Travel</span></a>
                            <ul>
                                <li>
                                    <a href="frontend_travel_agency.html">Agency</a>
                                </li>
                                <li>
                                    <a href="frontend_travel_package.html">Package</a>
                                </li>
                                <li>
                                    <a href="frontend_travel_guide.html">Guide</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- END Side Content -->
            </div>
            <!-- Sidebar Content -->
        </div>
        <!-- END Sidebar Scroll Container -->
    </nav>
    <!-- END Sidebar -->

    <!-- Header -->
    <header id="header-navbar" class="content-mini content-mini-full hidden-md hidden-lg">
        <div class="content-boxed">
            <!-- Header Navigation Right -->
            <ul class="nav-header pull-right">
                <li>
                    <!-- Themes functionality initialized in App() -> uiHandleTheme() -->
                    <div class="btn-group">
                        <button class="btn btn-link text-white dropdown-toggle" data-toggle="dropdown" type="button">
                            <i class="si si-drop"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right sidebar-mini-hide font-s13">
                            <li>
                                <a data-toggle="theme" data-theme="default" tabindex="-1" href="javascript:void(0)">
                                    <i class="fa fa-circle text-default pull-right"></i> <span
                                            class="font-w600">Default</span>
                                </a>
                            </li>
                            <li>
                                <a data-toggle="theme" data-theme="assets/css/themes/amethyst.min.css" tabindex="-1"
                                   href="javascript:void(0)">
                                    <i class="fa fa-circle text-amethyst pull-right"></i> <span class="font-w600">Amethyst</span>
                                </a>
                            </li>
                            <li>
                                <a data-toggle="theme" data-theme="assets/css/themes/city.min.css" tabindex="-1"
                                   href="javascript:void(0)">
                                    <i class="fa fa-circle text-city pull-right"></i> <span
                                            class="font-w600">City</span>
                                </a>
                            </li>
                            <li>
                                <a data-toggle="theme" data-theme="assets/css/themes/flat.min.css" tabindex="-1"
                                   href="javascript:void(0)">
                                    <i class="fa fa-circle text-flat pull-right"></i> <span
                                            class="font-w600">Flat</span>
                                </a>
                            </li>
                            <li>
                                <a data-toggle="theme" data-theme="assets/css/themes/modern.min.css" tabindex="-1"
                                   href="javascript:void(0)">
                                    <i class="fa fa-circle text-modern pull-right"></i> <span
                                            class="font-w600">Modern</span>
                                </a>
                            </li>
                            <li>
                                <a data-toggle="theme" data-theme="assets/css/themes/smooth.min.css" tabindex="-1"
                                   href="javascript:void(0)">
                                    <i class="fa fa-circle text-smooth pull-right"></i> <span
                                            class="font-w600">Smooth</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                    <button class="btn btn-link text-white pull-right" type="button" data-toggle="layout"
                            data-action="sidebar_open">
                        <i class="fa fa-navicon"></i>
                    </button>
                </li>
            </ul>
            <!-- END Header Navigation Right -->

            <!-- Header Navigation Left -->
            <ul class="nav-header pull-left">
                <li class="header-content">
                    <a class="h5" href="index.html">
                        <i class="fa fa-circle-o-notch text-primary"></i> <span
                                class="h4 font-w600 text-white">ne</span>
                    </a>
                </li>
            </ul>
            <!-- END Header Navigation Left -->
        </div>
    </header>
    <!-- END Header -->

    <!-- Main Container -->
    <main id="main-container">
        <div id="app">
            @yield('content')
        </div>
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    <footer id="page-footer" class="bg-white">
        <div class="content content-boxed">
            <!-- Footer Navigation -->
            <div class="row push-30-t items-push-2x">
                <div class="col-sm-4">
                    <h3 class="h5 font-w600 text-uppercase push-20">Company</h3>
                    <ul class="list list-simple-mini font-s13">
                        <li>
                            <a class="font-w600" href="frontend_home.html">Home</a>
                        </li>
                        <li>
                            <a class="font-w600" href="frontend_features.html">Features</a>
                        </li>
                        <li>
                            <a class="font-w600" href="frontend_pricing.html">Pricing</a>
                        </li>
                        <li>
                            <a class="font-w600" href="frontend_about.html">About Us</a>
                        </li>
                        <li>
                            <a class="font-w600" href="frontend_contact.html">Contact Us</a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-4">
                    <h3 class="h5 font-w600 text-uppercase push-20">Support</h3>
                    <ul class="list list-simple-mini font-s13">
                        <li>
                            <a class="font-w600" href="frontend_login.html">Log In</a>
                        </li>
                        <li>
                            <a class="font-w600" href="frontend_signup.html">Sign Up</a>
                        </li>
                        <li>
                            <a class="font-w600" href="frontend_support.html">Support Center</a>
                        </li>
                        <li>
                            <a class="font-w600" href="javascript:void(0)">Privacy Policy</a>
                        </li>
                        <li>
                            <a class="font-w600" href="javascript:void(0)">Terms &amp; Conditions</a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-4">
                    <h3 class="h5 font-w600 text-uppercase push-20">Get In Touch</h3>
                    <div class="font-s13 push">
                        <strong>Company, Inc.</strong><br>
                        980 Folsom Ave, Suite 1230<br>
                        San Francisco, CA 94107<br>
                        <abbr title="Phone">P:</abbr> (123) 456-7890
                    </div>
                    <div class="font-s13">
                        <i class="si si-envelope-open"></i> company@example.com
                    </div>
                </div>
            </div>
            <!-- END Footer Navigation -->

            <!-- Copyright Info -->
            <div class="font-s12 push-20 clearfix">
                <hr class="remove-margin-t">
                <div class="pull-right">
                    Crafted with <i class="fa fa-heart text-city"></i> by <a class="font-w600"
                                                                             href="http://goo.gl/vNS3I" target="_blank">pixelcave</a>
                </div>
                <div class="pull-left">
                    <a class="font-w600" href="http://goo.gl/6LF10W" target="_blank">OneUI 3.0</a> &copy; <span
                            class="js-year-copy"></span>
                </div>
            </div>
            <!-- END Copyright Info -->
        </div>
    </footer>
    <!-- END Footer -->
</div>
<!-- END Page Container -->
<!-- Page JS Code -->
<script src="{{ elixir('js/all.js') }}"></script>
<script src="{{ elixir('js/app.js') }}"></script>
<script>
    jQuery(function () {
        // Init page helpers (Appear + CountTo plugins)
        App.initHelpers(['appear', 'appear-countTo']);
    });
</script>
</body>
</html>