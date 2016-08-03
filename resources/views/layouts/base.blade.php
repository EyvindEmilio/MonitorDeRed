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
<!--suppress HtmlUnknownAttribute -->
<body ng-app="Monitor">

@yield('body-content')

<!-- Angular dependences -->
<script src="app/js/moment.min.js"></script>
<script src="app/js/angular.min.js"></script>
<script src="app/js/angular-moment.min.js"></script>
<script src="app/js/angular-resource.min.js"></script>
<script src="app/js/ui-bootstrap-tpls-2.0.1.min.js"></script>
<script src="app/js/app.js"></script>

<!-- services -->
<script src="app/js/services/InterceptorService.js"></script>
<script src="app/js/services/ModelService.js"></script>
<script src="app/js/services/ApiService.js"></script>
<!-- directives -->
<script src="app/js/directives/CrudGenerator.js"></script>
<script src="app/js/filters/CrudFilter.js"></script>

<!-- Angular Scripts -->
@yield('angular-scripts')

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

<!--common script init for all pages-->
<script src="{{ asset('js/scripts.js') }}"></script>
<!--script for this page-->
</body>
</html>

