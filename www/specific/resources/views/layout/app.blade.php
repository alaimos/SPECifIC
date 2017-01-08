@include('layout.header')
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
                    <a class="h5 text-white" href="{{ url('/') }}">
                        <span class="h4 font-w600 text-primary">S</span><span
                                class="h4 font-w600 sidebar-mini-hide"><span class="">P</span><span
                                    class="text-primary">E</span><span class="">C</span><span
                                    class="text-primary">i</span><span class="">f</span><span
                                    class="text-primary">I</span><span class="">C</span></span>
                    </a>
                </div>
                <!-- END Side Header -->

                <!-- Side Content -->
                <div class="side-content">
                    <ul class="nav-main">
                        <li>
                            <a href="{{ url('/') }}">
                                <i class="si si-home"></i>
                                <span class="sidebar-mini-hide">Home</span></a>
                        </li>
                        <li>
                            <a href="{{ url('/history') }}"><i class="si si-clock"></i><span
                                        class="sidebar-mini-hide">Results History</span></a>
                        </li>
                        <li>
                            <a href="{{ url('/api') }}"><i class="si si-energy"></i><span
                                        class="sidebar-mini-hide">API</span></a>
                        </li>
                        <li>
                            <a href="{{ url('/help') }}"><i class="si si-question"></i><span
                                        class="sidebar-mini-hide">Help</span></a>
                        </li>
                        <li>
                            <a href="{{ url('/references') }}"><i class="si si-book-open"></i><span
                                        class="sidebar-mini-hide">References</span></a>
                        </li>
                        <li>
                            <a href="{{ url('/contacts') }}"><i class="si si-envelope-open"></i><span
                                        class="sidebar-mini-hide">Contacts</span></a>
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
                    <a class="h5 text-white" href="{{ url('/') }}">
                        <span class="h4 font-w600 text-primary">S</span><span
                                class="h4 font-w600 sidebar-mini-hide"><span class="">P</span><span
                                    class="text-primary">E</span><span class="">C</span><span
                                    class="text-primary">i</span><span class="">f</span><span
                                    class="text-primary">I</span><span class="">C</span></span>
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
    <footer id="page-footer" class="content-mini content-mini-full font-s12 bg-gray-lighter clearfix">
        <div class="pull-right">
            Template: <a class="font-w600" href="http://goo.gl/6LF10W" target="_blank">OneUI 3.0</a> by
            <a class="font-w600" href="http://goo.gl/vNS3I" target="_blank">pixelcave</a>
        </div>
        <div class="pull-left">
            &copy; <span class="js-year-copy"></span> -
            Developed by: <span class="font-w600">S. Alaimo, Ph.D.</span>
        </div>
    </footer>
    {{--<footer id="page-footer" class="bg-white">
        <div class="content content-boxed">
        <!-- Footer Navigation
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
                <div class="pull-left">
                    &copy; <span class="js-year-copy"></span> -
                    Developed by: <span class="font-w600">S. Alaimo, Ph.D.</span> - Template:
                    <a class="font-w600" href="http://goo.gl/6LF10W" target="_blank">OneUI 3.0</a> by
                    <a class="font-w600" href="http://goo.gl/vNS3I" target="_blank">pixelcave</a>
                </div>
            </div>
            <!-- END Copyright Info -->
        </div>
    </footer>--}}
    <!-- END Footer -->
</div>
<!-- END Page Container -->
@include('layout.footer')