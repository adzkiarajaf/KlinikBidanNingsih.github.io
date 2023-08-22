<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ config ('app.name') }}
            | @yield('title')</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta
            content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
            name="viewport">

        <link rel="icon" type="image/x-icon" href="{{ asset('img/nurse.png') }}">
        <!-- Bootstrap 3.3.7 -->
        <link
            rel="stylesheet"
            href="{{ asset('AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
        <!-- Font Awesome -->
        <link
            rel="stylesheet"
            href="{{ asset('AdminLTE-2/bower_components/font-awesome/css/font-awesome.min.css') }}">
        <!-- Ionicons -->
        <link
            rel="stylesheet"
            href="{{ asset('AdminLTE-2/bower_components/Ionicons/css/ionicons.min.css') }}">
        <!-- Theme style -->
        <link
            rel="stylesheet"
            href="{{ asset('AdminLTE-2/dist/css/AdminLTE.min.css') }}">
        <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of
        downloading all of them to reduce the load. -->
        <link
            rel="stylesheet"
            href="{{ asset('AdminLTE-2/dist/css/skins/_all-skins.min.css') }}">
        <!-- Morris chart -->
        <link
            rel="stylesheet"
            href="{{ asset('AdminLTE-2/bower_components/morris.js/morris.css') }}">
        <!-- jvectormap -->
        <link
            rel="stylesheet"
            href="{{ asset('AdminLTE-2/bower_components/jvectormap/jquery-jvectormap.css') }}">
        <!-- Date Picker -->
        <link
            rel="stylesheet"
            href="{{ asset('AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
        <!-- Daterange picker -->
        <link
            rel="stylesheet"
            href="{{ asset('AdminLTE-2/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
        <!-- bootstrap wysihtml5 - text editor -->
        <link
            rel="stylesheet"
            href="{{ asset('AdminLTE-2/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
        <!-- Data Table -->
        <link
            rel="stylesheet"
            href="{{ asset('AdminLTE-2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

        <link
            href="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.css"
            rel="stylesheet"/>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries
        -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]> <script
        src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script> <script
        src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script> <![endif]-->
        <!-- Google Font -->
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Metal&display=swap">
        @stack('css')
    </head>

    <body class="hold-transition skin-light-blue sidebar-mini">
        <div class="wrapper">

            @includeIf('layouts.header', ['some' => 'data'])
            <!-- Left side column. contains the logo and sidebar -->
            @includeIf('layouts.sidebar', ['some' => 'data'])

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <ol class="breadcrumb text-center">
                        <li class="active">Utama</li>
                    </ol>
                </section>
                <div class="box-body box-primary">
                    <div class="box-group" id="accordion box-primary">
                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        Klinik Bidan Ningsih
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <div class="box-body">
                                    Mewujudkan bidan praktek mandiri yang profesional dalam memberikan pelayanan
                                    kesehatan yang bermutu bagi masyarakat
                                </div>
                            </div>
                        </div>
                        <!-- Main content -->
                        <section class="content">
                            @yield('content')
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->

        <!-- /.content-wrapper -->
        @includeIf('layouts.footer', ['some' => 'data'])

        <!-- jQuery 3 -->
        <script
            src="{{ asset('AdminLTE-2/bower_components/jquery/dist/jquery.min.js') }}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script
            src="{{ asset('AdminLTE-2/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $
                .widget
                .bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.7 -->
        <script
            src="{{ asset('AdminLTE-2/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <!-- Morris.js charts -->
        <script src="{{ asset('AdminLTE-2/bower_components/raphael/raphael.min.js') }}"></script>
        <script
            src="{{ asset('AdminLTE-2/bower_components/morris.js/morris.min.js') }}"></script>
        <!-- Sparkline -->
        <script
            src="{{ asset('AdminLTE-2/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
        <!-- jvectormap -->
        <script
            src="{{ asset('AdminLTE-2/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script
            src="{{ asset('AdminLTE-2/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
        <!-- jQuery Knob Chart -->
        <script
            src="{{ asset('AdminLTE-2/bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
        <!-- daterangepicker -->
        <script
            src="{{ asset('AdminLTE-2/bower_components/moment/min/moment.min.js') }}"></script>
        <script
            src="{{ asset('AdminLTE-2/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
        <!-- datepicker -->
        <script
            src="{{ asset('AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script
            src="{{ asset('AdminLTE-2/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
        <!-- Slimscroll -->
        <script
            src="{{ asset('AdminLTE-2/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
        <!-- FastClick -->
        <script
            src="{{ asset('AdminLTE-2/bower_components/fastclick/lib/fastclick.js') }}"></script>
        <!-- Data Table -->
        <script
            src="{{ asset('AdminLTE-2/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script
            src="{{ asset('AdminLTE-2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('AdminLTE-2/dist/js/adminlte.min.js') }}"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="{{ asset('AdminLTE-2/dist/js/pages/dashboard.js') }}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{ asset('AdminLTE-2/dist/js/demo.js') }}"></script>
        <!-- Validator -->
        <script src="{{ asset('AdminLTE-2/dist/js//validator.min.js') }}"></script>
        <!-- Sweet Alert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Include Bootstrap Datepicker -->
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
        <script>
            function preview(selector, temporaryFile, width = 200) {
                $(selector).empty();
                $(selector).append(
                    `<img src="${window.URL.createObjectURL(temporaryFile)}" width="${width}">`
                );
            }
        </script>
        @stack('scripts')
    </body>
</html>