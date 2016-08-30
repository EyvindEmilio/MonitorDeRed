@extends('layouts.base')

@section('body-content')

    <section id="container">
        <!--header start-->
        <header class="header fixed-top clearfix">
            <!--logo start-->
            <div class="brand">

                <a href="index.html" class="logo">
                    <img src="{{ asset('images/icono-emi.svg') }} " alt="Icono emi" style="max-height: 40px">
                    Red EMI
                </a>
                <div class="sidebar-toggle-box">
                    <div class="fa fa-bars"></div>
                </div>
            </div>
            <!--logo end-->

            <div class="nav notify-row" id="top_menu">
                <!--  notification start -->
                <ul class="nav top-menu">
                    <!-- settings start -->
                {{--  <li class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          <i class="fa fa-tasks"></i>
                          <span class="badge bg-success">8</span>
                      </a>
                      <ul class="dropdown-menu extended tasks-bar">
                          <li>
                              <p class="">You have 8 pending tasks</p>
                          </li>
                          <li>
                              <a href="#">
                                  <div class="task-info clearfix">
                                      <div class="desc pull-left">
                                          <h5>Target Sell</h5>
                                          <p>25% , Deadline 12 June’13</p>
                                      </div>
                                      <span class="notification-pie-chart pull-right" data-percent="45">
                          <span class="percent"></span>
                          </span>
                                  </div>
                              </a>
                          </li>
                          <li>
                              <a href="#">
                                  <div class="task-info clearfix">
                                      <div class="desc pull-left">
                                          <h5>Product Delivery</h5>
                                          <p>45% , Deadline 12 June’13</p>
                                      </div>
                                      <span class="notification-pie-chart pull-right" data-percent="78">
                          <span class="percent"></span>
                          </span>
                                  </div>
                              </a>
                          </li>
                          <li>
                              <a href="#">
                                  <div class="task-info clearfix">
                                      <div class="desc pull-left">
                                          <h5>Payment collection</h5>
                                          <p>87% , Deadline 12 June’13</p>
                                      </div>
                                      <span class="notification-pie-chart pull-right" data-percent="60">
                          <span class="percent"></span>
                          </span>
                                  </div>
                              </a>
                          </li>
                          <li>
                              <a href="#">
                                  <div class="task-info clearfix">
                                      <div class="desc pull-left">
                                          <h5>Target Sell</h5>
                                          <p>33% , Deadline 12 June’13</p>
                                      </div>
                                      <span class="notification-pie-chart pull-right" data-percent="90">
                          <span class="percent"></span>
                          </span>
                                  </div>
                              </a>
                          </li>

                          <li class="external">
                              <a href="#">See All Tasks</a>
                          </li>
                      </ul>
                  </li>--}}
                <!-- settings end -->
                    <!-- inbox dropdown start-->
                {{--<li id="header_inbox_bar" class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="fa fa-envelope-o"></i>
                        <span class="badge bg-important">4</span>
                    </a>
                    <ul class="dropdown-menu extended inbox">
                        <li>
                            <p class="red">You have 4 Mails</p>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="images/avatar-mini.jpg"></span>
                                <span class="subject">
                            <span class="from">Jonathan Smith</span>
                            <span class="time">Just now</span>
                            </span>
                                <span class="message">
                                Hello, this is an example msg.
                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="images/avatar-mini-2.jpg"></span>
                                <span class="subject">
                            <span class="from">Jane Doe</span>
                            <span class="time">2 min ago</span>
                            </span>
                                <span class="message">
                                Nice admin template
                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="images/avatar-mini-3.jpg"></span>
                                <span class="subject">
                            <span class="from">Tasi sam</span>
                            <span class="time">2 days ago</span>
                            </span>
                                <span class="message">
                                This is an example msg.
                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="photo"><img alt="avatar" src="images/avatar-mini.jpg"></span>
                                <span class="subject">
                            <span class="from">Mr. Perfect</span>
                            <span class="time">2 hour ago</span>
                            </span>
                                <span class="message">
                                Hi there, its a test
                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">See all messages</a>
                        </li>
                    </ul>
                </li>--}}
                <!-- inbox dropdown end -->
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
                            <img alt="" src="images/icon_default_user.png" width="33">
                            <span class="username">{{ Auth::user()->first_name }}</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <li><a href=""><i class=" fa fa-suitcase"></i>Perfil de usuario</a></li>
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
                        {{--     <li>
                                 <a class="{{ (Request::is('/'))?'active':'' }}" href="/">
                                     <i class="fa fa-dashboard"></i>
                                     <span>Dashboard</span>
                                 </a>
                             </li>--}}


                        <li class="sub-menu">
                            <a href="javascript:;" class="active">
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

                                <li class="{{ (Request::is('/attacks'))?'active':'' }}">
                                    <a href="{{ url('/attacks') }}">Ataques</a>
                                </li>
                            </ul>
                        </li>


                        <li>
                            <a class="{{ (Request::is('standard'))?'active':'' }}" href="{{ url('/standard') }}">
                                <i class="fa fa-legal"></i>
                                <span>Base Normativa</span>
                            </a>
                        </li>

                        <li class="sub-menu">
                            <a href="javascript:;"
                               class="{{ (Request::is('users')||Request::is('users_types'))?'active':'' }}">
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
                            </ul>
                        </li>

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

                                <li class="{{ (Request::is('device_types'))?'active':'' }}">
                                    <a href="{{ url('/device_types') }}">Tipos de dispositivos</a>
                                </li>

                                <li class="{{ (Request::is('areas'))?'active':'' }}">
                                    <a href="{{ url('/areas') }}">Areas</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a class="{{ (Request::is('/settings'))?'active':'' }}" href="{{ url('/settings') }}">
                                <i class="fa fa-shield"></i>
                                <span>Configuracion</span>
                            </a>
                        </li>

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
                .service('SocketService', function ($API, $rootScope) {
                    var socket = io.connect('http://{{ $_SERVER['SERVER_NAME']}}:8890');
                    return {
                        socket: socket
                    }
                });
        angular.module('Monitor').run(function ($rootScope, ModelService, SocketService) {
            $rootScope.GLOBALS = {};
            $rootScope.GLOBALS.active_pcs = {};
            $rootScope.GLOBALS.alerts = [];
            var socket = SocketService.socket;

            $rootScope.GLOBALS.alert_number_pc_inactive = 0;

            $rootScope.getNameFromIp = function (ip) {
                if (!$rootScope.GLOBALS.active_pcs) {
                    return ip;
                }
                var pcs = $rootScope.GLOBALS.active_pcs.data;
                var ip_name = ip;
                for (var index = 0; index < pcs.length; index++) {
                    if (pcs[index].ip == ip) {
                        ip_name = pcs[index].name;
                    }
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
                console.log('denial');
                removeAlert('alert_denial_service');
                $rootScope.GLOBALS.alerts.push({
                    name: 'alert_denial_service',
                    message: 'Se ha detectado, posible incidente de denegacion de servicios de ip: ' + data.ip
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
        });
    </script>
@endsection