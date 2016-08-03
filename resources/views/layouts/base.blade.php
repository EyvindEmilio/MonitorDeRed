<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <meta name="description" content="Sistema de Monitoreo de Red, Direccion Nacional de Informatica">
    <title>Monitoreo de Red</title>

    <link href="{{ asset('bs3/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/jquery-ui/jquery-ui-1.10.1.custom.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-reset.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('js/jvector-map/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/clndr.css') }}" rel="stylesheet">
    <link href="{{ asset('js/css3clock/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('js/morris-chart/morris.css') }}">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-responsive.css') }}" rel="stylesheet"/>
    <!--[if lt IE 9]>
    <script src="{{ asset('js/ie8-responsive-file-warning.js') }}"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="{{ asset('html5shiv.js') }}"></script>
    <script src="{{ asset('respond.min.js') }}"></script>
    <![endif]-->
</head>
<body>

@yield('body-content')

<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/jquery-ui/jquery-ui-1.10.1.custom.min.js') }}"></script>
<script src="{{ asset('bs3/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.dcjqaccordion.2.7.js') }}"></script>
<script src="{{ asset('js/jquery.scrollTo.min.js') }}"></script>
<script src="{{ asset('js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('js/jquery.nicescroll.js') }}"></script>
<!--[if lte IE 8]>
<script language="javascript" type="text/javascript" src="{{ asset('js/flot-chart/excanvas.min.js') }}"></script>
<![endif]-->
<script src="{{ asset('js/skycons/skycons.js') }}"></script>
<script src="{{ asset('js/jquery.scrollTo/jquery.scrollTo.js') }}"></script>
<script src="{{ asset('js/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/calendar/clndr.js') }}"></script>
<script src="{{ asset('js/underscore-min.js') }}"></script>
<script src="{{ asset('js/calendar/moment-2.2.1.js') }}"></script>
<script src="{{ asset('js/evnt.calendar.init.js') }}"></script>
<script src="{{ asset('js/jvector-map/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('js/jvector-map/jquery-jvectormap-us-lcc-en.js') }}"></script>
<script src="{{ asset('js/gauge/gauge.js') }}"></script>
<!--clock init-->
<script src="{{ asset('js/css3clock/js/css3clock.js') }}"></script>
<!--Easy Pie Chart-->
<script src="{{ asset('js/easypiechart/jquery.easypiechart.js') }}"></script>
<!--Sparkline Chart-->
<script src="{{ asset('js/sparkline/jquery.sparkline.js') }}"></script>
<!--Morris Chart-->
<script src="{{ asset('js/morris-chart/morris.js') }}"></script>
<script src="{{ asset('js/morris-chart/raphael-min.js') }}"></script>
<!--jQuery Flot Chart-->
<script src="{{ asset('js/jquery.customSelect.min.js') }}"></script>
<!--common script init for all pages-->
<script src="{{ asset('js/scripts.js') }}"></script>
<!--script for this page-->
</body>
</html>

