@extends('layouts.base')

@section('body-content')

    <section id="container">
        <!--header start-->
        <header class="header fixed-top clearfix">
            <!--logo start-->
            <div class="brand">

                <a href="index.html" class="logo">
                    <img src="{{ asset('images/icono-emi.svg') }} " alt="Icono emi" style="max-height: 40px">
                    EMI
                </a>
                <div class="sidebar-toggle-box">
                    <div class="fa fa-bars"></div>
                </div>
            </div>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                <!--  notification start -->
                <ul class="nav top-menu">
                {{--<h4 class="pull-left">SISTEMA DE MONITOREO DE RED</h4>--}}
                <!-- notification dropdown start-->
                    <li id="header_notification_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="fa fa-bell-o"></i>
                            <span class="badge bg-warning" ng-show="GLOBALS.alerts.length>0"
                                  ng-bind="GLOBALS.alerts.length"></span>
                        </a>
                        <ul class="dropdown-menu extended notification">
                            <li>
                                <p>Alertas</p>
                            </li>
                            <li ng-repeat="alert in GLOBALS.alerts">
                                <div class="alert alert-info clearfix">
                                    <span class="alert-icon"><i class="fa fa-bolt"></i></span>
                                    <div class="noti-info">
                                        <a href="" ng-bind="alert.message"></a>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </li>
                    <!-- notification dropdown end -->
                </ul>
                <!--  notification end -->
            </div>
            <div class="top-nav clearfix">
                <!--search & user info start-->
                <ul class="nav pull-right top-menu">
                {{--<li>
                    <input type="text" class="form-control search" placeholder=" Search">
                </li>--}}
                <!-- user login dropdown start-->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <img alt="" ng-src="@{{ currentUser.image || 'images/icon_default_user.png' }}" height="40"
                                 width="40">
                            <span class="username">{{ Auth::user()->first_name }}</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <li><a href="" ng-click="show_profile()"><i class=" fa fa-suitcase"></i>Perfil de
                                    usuario</a></li>
                            <li><a href="{{ url('/settings') }}"><i class="fa fa-cog"></i> Configuracion</a></li>
                            <li><a href="/logout"><i class="fa fa-key"></i> Salir</a></li>
                        </ul>
                    </li>
                    <!-- user login dropdown end -->
                </ul>
                <!--search & user info end-->
            </div>
        </header>
        <!--header end-->
        <!--sidebar start-->
        <aside>
            <div id="sidebar" class="nav-collapse">
                <!-- sidebar menu start-->
                <div class="leftside-navigation">
                    <ul class="sidebar-menu" id="nav-accordion">
                        <li class="sub-menu">
                            <a class="active">
                                <i class="fa fa-laptop"></i>
                                <span>Dashboard</span>
                            </a>
                            <ul class="sub">
                                <li class="{{ (Request::is('/'))?'active':'' }}">
                                    <a href="{{ url('/') }}">Dashboard</a>
                                </li>

                                <li class="{{ (Request::is('/monitor'))?'active':'' }}">
                                    <a href="{{ url('/monitor') }}">Monitor</a>
                                </li>
                                @if(\App\User::isAdmin())
                                    <li class="{{ (Request::is('/consumo'))?'active':'' }}">
                                        <a href="{{ url('/consumo') }}">Consumo</a>
                                    </li>
                                @endif
                                <li class="{{ (Request::is('/attacks'))?'active':'' }}">
                                    <a href="{{ url('/attacks') }}">Alertas</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a class="{{ (Request::is('standard'))?'active':'' }}" href="{{ url('/standard') }}">
                                <i class="fa fa-legal"></i>
                                <span>Base Normativa</span>
                            </a>
                        </li>

                        @if(\App\User::isAdmin() || \App\User::isJefe())
                            <li class="sub-menu">
                                <a href="javascript:;"
                                   class="{{ (Request::is('users')||Request::is('users_types')||Request::is('logs'))?'active':'' }}">
                                    <i class="fa fa-users"></i>
                                    <span>Usuarios</span>
                                </a>
                                <ul class="sub">
                                    <li class="{{ (Request::is('users'))?'active':'' }}">
                                        <a href="{{ url('/users') }}">Usuarios registrados</a>
                                    </li>
                                    <li class="{{ (Request::is('users_types'))?'active':'' }}">
                                        <a href="{{ url('/users_types') }}">Tipos de usuarios</a>
                                    </li>


                                    @if(\App\User::isAdmin())
                                        <li>
                                            <a class="{{ (Request::is('logs'))?'active':'' }}"
                                               href="{{ url('/logs') }}">
                                                <i class="fa fa-legal"></i>
                                                <span>Acciones de usuarios</span>
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif

                        <li class="sub-menu">
                            <a href="javascript:;"
                               class="{{ (Request::is('devices')||Request::is('device_types')||Request::is('areas'))?'active':'' }}">
                                <i class="fa fa-laptop"></i>
                                <span>Dispositivos y Areas</span>
                            </a>
                            <ul class="sub">
                                <li class="{{ (Request::is('devices'))?'active':'' }}">
                                    <a href="{{ url('/devices') }}">Dispositivos</a>
                                </li>
                                @if(\App\User::isAdmin())
                                    <li class="{{ (Request::is('device_types'))?'active':'' }}">
                                        <a href="{{ url('/device_types') }}">Tipos de dispositivos</a>
                                    </li>

                                    <li class="{{ (Request::is('areas'))?'active':'' }}">
                                        <a href="{{ url('/areas') }}">Areas</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        @if(\App\User::isAdmin())
                            <li>
                                <a class="{{ (Request::is('/settings'))?'active':'' }}" href="{{ url('/settings') }}">
                                    <i class="fa fa-shield"></i>
                                    <span>Configuracion</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
                <!-- sidebar menu end-->
            </div>
        </aside>
        <!--sidebar end-->
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        {{ Session::get('flash_message') }}
                    </div>
                @endif

                @yield('main-content')
            </section>
        </section>
        <!--main content end-->
    </section>
@endsection

@section('angular-run-script')
    <!--suppress JSUnresolvedVariable -->
    <script type="text/javascript">
        angular.module('Monitor')
                .service('SocketService', function () {
                    var socket = io.connect('http://{{ $_SERVER['SERVER_NAME']}}:8890');
                    return {
                        socket: socket
                    }
                });
        angular.module('Monitor').run(function ($rootScope, ModelService, SocketService, $uibModal) {
            $rootScope.currentUser =  {!! Auth::user() !!};
            $rootScope.currentUser.image = "{!! Auth::user()->image?('http://'.$_SERVER['HTTP_HOST'].'/images/users/'.Auth::user()->image):null !!}";
            Highcharts.setOptions({global: {useUTC: false, timezone: 'America/La_Paz'}});
            $rootScope.GLOBALS = {};
            $rootScope.GLOBALS.active_pcs = {};
            $rootScope.GLOBALS.alerts = [];
            var socket = SocketService.socket;

            $rootScope.GLOBALS.alert_number_pc_inactive = 0;

            $rootScope.getNameFromIp = function (ip) {
                if (!$rootScope.GLOBALS.active_pcs) {
                    return ip;
                }
                try {
                    var pcs = $rootScope.GLOBALS.active_pcs.data;
                    var ip_name = ip;
                    for (var index = 0; index < pcs.length; index++) {
                        if (pcs[index].ip == ip) {
                            ip_name = pcs[index].name;
                        }
                    }
                } catch (err) {
                    console.log('Err');
                }

                return ip_name;
            };

            function removeAlert(name) {
                for (var i = 0; i < $rootScope.GLOBALS.alerts.length; i++) {
                    if ($rootScope.GLOBALS.alerts[i].name == name) {
                        $rootScope.GLOBALS.alerts.splice(i, 1);
                    }
                }
                $rootScope.$apply();
            }

            socket.on('alert_denial_service', function (data) {
                removeAlert('alert_denial_service');
                $rootScope.GLOBALS.alerts.push({
                    name: 'alert_denial_service',
                    message: 'Se ha detectado, posible incidente de denegacion de servicios de ip: ' + data.src
                });
                $rootScope.$apply();
            });

            socket.on('active_pcs', function (data) {
                $rootScope.GLOBALS.alert_number_pc_inactive = 0;
                $rootScope.GLOBALS.active_pcs = data;

                for (var index = 0; index < data.data.length; index++) {
                    if (data.data[index].status_network == 'N') {
                        $rootScope.GLOBALS.alert_number_pc_inactive++;
                    }
                }
                removeAlert('inactive_pcs');
                if ($rootScope.GLOBALS.alert_number_pc_inactive > 0) {
                    $rootScope.GLOBALS.alerts.push({
                        name: 'inactive_pcs',
                        message: 'Existen ' + $rootScope.GLOBALS.alert_number_pc_inactive + ' equipos inactivos'
                    });
                }
                $rootScope.$apply();
            });

            $rootScope.GLOBALS.list_scan_all_ports = [];

            $rootScope.finish_loading_scan_all_ports = false;
            socket.on('scan_all_ports', function (data) {
                $rootScope.GLOBALS.list_scan_all_ports = data;
                $rootScope.finish_loading_scan_all_ports = true;
                var number_services_unknown = 0;
                for (var index = 0; index < data.length; index++) {
                    data[index].ip = $rootScope.getNameFromIp(data[index].ip) + ' (' + data[index].ip + ')';
                    for (var j = 0; j < data[index].ports.length; j++) {
                        if (data[index].ports[j].service === 'unknown') {
                            number_services_unknown++;
                        }
                    }
                }
                removeAlert('unknown_ports');
                if (number_services_unknown > 0) {
                    $rootScope.GLOBALS.alerts.push({
                        name: 'unknown_ports',
                        message: 'Se ha detectado:' + number_services_unknown + ' puerto(s) abiertos con servicio desconocido'
                    });
                }
                $rootScope.$apply();
            });

            $rootScope.contracts_model = new ModelService.UsersTypes();

            $rootScope.show_profile = function () {
                var modalInstance = $uibModal.open({
                    templateUrl: 'app/views/crud/modals/profile_modal.html',
                    controller: 'ProfileController',
                    size: 'md',
                    resolve: {
                        user: function () {
                            return $rootScope.currentUser;
                        },
                        Model: function () {
                            return new ModelService.Users();
                        }
                    }
                });
            };
        });
    </script>
@endsection