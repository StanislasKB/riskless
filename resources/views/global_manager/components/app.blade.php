<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>@yield('title')</title>
    <!--favicon-->
    <link rel="icon" href="/admin/assets/images/favicon-32x32.png" type="image/png" />
    <!-- Vector CSS -->
    <link href="/admin/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <!--plugins-->
    <link href="/admin/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="/admin/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="/admin/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    {{-- <link href="/admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"> --}}
    <link href="/admin/assets/plugins/datatable/css/datatables.css" rel="stylesheet" type="text/css">
    {{-- <link href="/admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css"> --}}
    <!-- loader-->
    <link href="/admin/assets/css/pace.min.css" rel="stylesheet" />
    <script src="/admin/assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/admin/assets/css/bootstrap.min.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&amp;family=Roboto&amp;display=swap" />
    <!-- Icons CSS -->
    <link rel="stylesheet" href="/admin/assets/css/icons.css" />
    <!-- App CSS -->
    <link rel="stylesheet" href="/admin/assets/css/app.css" />
    <link rel="stylesheet" href="/admin/assets/css/dark-sidebar.css" />
    <link rel="stylesheet" href="/admin/assets/css/dark-theme.css" />
    @yield('page_css')
</head>

<body>
    <!-- wrapper -->
    <div class="wrapper">

        <!--header-->
        @include('global_manager.components.layouts.header')
        <!--end header-->

        <!--navigation-->
        @include('global_manager.components.layouts.navbar')
        <!--end navigation-->

        <!--page-wrapper-->
        <div class="page-wrapper">
            <!--page-content-wrapper-->
            <div class="page-content-wrapper">
                <div class="page-content">
                    @yield('main_content')
                </div>
            </div>
            <!--end page-content-wrapper-->
        </div>
        <!--end page-wrapper-->
        <!--start overlay-->
        <div class="overlay toggle-btn-mobile"></div>
        <!--end overlay-->
        <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i
                class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <!--footer -->
        <div class="footer">
            <p class="mb-0">Syndash @2020 | Developed By : <a href="https://themeforest.net/user/codervent"
                    target="_blank">codervent</a>
            </p>
        </div>
        <!-- end footer -->
    </div>
    <!-- end wrapper -->
   
    <!-- JavaScript -->

    <!-- Bootstrap JS -->
    <script src="/admin/assets/js/bootstrap.bundle.min.js"></script>

    <!--plugins-->
    <script src="/admin/assets/js/jquery.min.js"></script>
    {{-- <script src="/admin/assets/js/jquery-3.7.1.min.js"></script> --}}
    <script src="/admin/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="/admin/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="/admin/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!-- Vector map JavaScript -->
    <script src="/admin/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="/admin/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="/admin/assets/plugins/vectormap/jquery-jvectormap-in-mill.js"></script>
    <script src="/admin/assets/plugins/vectormap/jquery-jvectormap-us-aea-en.js"></script>
    <script src="/admin/assets/plugins/vectormap/jquery-jvectormap-uk-mill-en.js"></script>
    <script src="/admin/assets/plugins/vectormap/jquery-jvectormap-au-mill.js"></script>
    <script src="/admin/assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
    <script src="/admin/assets/js/index.js"></script>
    {{-- <script src="/admin/assets/plugins/datatable/js/jquery.dataTables.min.js"></script> --}}
    <script src="/admin/assets/plugins/datatable/js/datatables.js"></script>
    <!-- App JS -->
    <script src="/admin/assets/js/app.js"></script>
    <script>
        new PerfectScrollbar('.dashboard-social-list');
        new PerfectScrollbar('.dashboard-top-countries');
    </script>
    @yield('page_js')
</body>

</html>
