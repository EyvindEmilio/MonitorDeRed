@extends('layouts.appMonitor')

@section('main-content')
    <div ng-controller="DashboardController">
        <div class="col-md-12">
            <section class="panel">
                <div class="panel-heading"> Uso de la red</div>
                <div class="panel-body">
                    <div class="IMAGE_CHART">
                        <highchart id="chart1" config="chart_monitor"></highchart>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-7">
            <section class="panel">
                <div class="panel-heading"> Dispositivos
                    <img src="{{ asset('images/infinite_loader.gif')}}" width="30" class="pull-right">
                    <span class="badge pull-right" ng-bind="GLOBALS.active_pcs.date | amParse:'HH:mm:ss'"></span>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-condensed table-hover cf small">
                        <thead class="cf">
                        <tr>
                            <th>#</th>
                            <th>Maquina</th>
                            <th>IP</th>
                            <th>MAC</th>
                            <th>Fabricante</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="pc in GLOBALS.active_pcs.data" ng-click="scan_pc(pc)"
                            style="cursor: pointer;@{{ (pc.name=='-- desconocido --')?'background: rgba(241, 196, 15,0.1) !important;':'' }}">
                            <td ng-bind="$index +1 "></td>
                            <td ng-bind="pc.name"></td>
                            <td ng-bind="pc.ip"></td>
                            <td ng-bind="pc.mac"></td>
                            <td ng-bind="pc.manufacturer"></td>
                            <td>
                                <button class="btn btn-xs btn-@{{pc.status_network=='Y'?'success':'danger'}}"
                                        style="width: 100%">
                                    @{{pc.status_network=='Y'?'Activo':'inactivo'}}
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr>
                    <p>** Haga click en el dispositivo a escanear puertos</p>
                </div>
            </section>
        </div>

        <div class="col-md-5">
            <section class="panel">
                <div class="panel-heading"> Escaneo de dispositvo @{{pc_scanned.name}}
                    <img src="/images/gif_loader.gif" width="20" class="pull-right"
                         ng-show="!finish_loading_scan_device">
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-condensed cf small">
                        <thead class="cf">
                        <tr>
                            <th>#</th>
                            <th>Puerto</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Servicio</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="pc_scan in pc_scanned.list_ports">
                            <td ng-bind="$index +1 "></td>
                            <td ng-bind="pc_scan.port" class="text-right"></td>
                            <td ng-bind="pc_scan.type"></td>
                            <td>
                                <button class="btn btn-xs btn-@{{pc_scan.status=='open'?'success':'danger'}}"
                                        style="width: 100%">
                                    @{{pc_scan.status=='open'?'Abierto':'Cerrado'}}
                                </button>
                            </td>
                            <td ng-bind="pc_scan.service"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('angular-scripts')
    <script type="text/javascript">
        angular.module('Monitor')
                .controller('DashboardController', function ($rootScope, $API, $resource, $http, $interval, toastr, SocketService) {
                    var socket = SocketService.socket;

                    $rootScope.finish_loading_list_status = false;
                    $rootScope.pcs = [];

                    $rootScope.pc_scanned = {};
                    $rootScope.finish_loading_scan_device = true;
                    $rootScope.scan_pc = function (pc) {
                        $rootScope.finish_loading_scan_device = false;
                        if (pc.status_network == 'Y') {
                            $rootScope.pc_scanned = angular.copy(pc);
                            $http.get($API.path + 'monitor/scan_ports?ip=' + pc.ip)
                                    .success(function (data) {
                                        if (data.length == 0) {
                                            toastr.success('Todos los puertos cerrados');
                                        }
                                        $rootScope.pc_scanned.list_ports = data;
                                        $rootScope.finish_loading_scan_device = true;
                                    });
                        } else {
                            toastr.warning('Dispositivo inactivo');
                            $rootScope.finish_loading_scan_device = true;
                        }
                    };
                    Highcharts.setOptions({
                        global: {
                            //        useUTC: false
                        }
                    });
                    $rootScope.chart_monitor = {
                        options: {
                            chart: {
                                type: 'spline',
                                heiht: 10
                            }
                        },
                        series: [],
                        title: {
                            text: 'Monitoreo de red'
                        },
                        xAxis: {
                            title: {text: 'Tiempo '},
                            lineWidth: 1,
                            type: 'datetime',
                            dateTimeLabelFormats: { // don't display the dummy year
                                minute: '%H:%M'
                            },
                            tickInterval: 1000,
                            gridLineWidth: 1,
                            maxZoom: 30 * 1000,
                            minZoom: 30 * 1000,
//                            min: (new Date(moment().subtract(30, 'seconds'))).getUTCDate(),
//                            max: (new Date(moment())).getUTCDate()

                        },
                        yAxis: {
                            title: {
                                text: 'Tasa (kbps)'
                            },
                            min: 0,
                            max: 20,
                        }
                    };

                    $rootScope.add_series = function (series_array_name) {
                        $rootScope.chart_monitor.series.push(series_array_name);
                    };

                    function getNameFromIp(ip) {
                        var pcs = $rootScope.GLOBALS.active_pcs.data;
                        var ip_name = false;
                        for (var index = 0; index < pcs.length; index++) {
                            if (pcs[index].ip == ip) {
                                ip_name = pcs[index].name;
                            }
                        }
                        return name;
                    }

                    $rootScope.chart_monitor_tmp = {};
                    $rootScope.chart_monitor_tmp.series = [];
                    setInterval(function () {
                        $rootScope.chart_monitor.series = $rootScope.chart_monitor_tmp.series;
                        /*$rootScope.chart_monitor.series = [];
                         for (var i = 0; i < $rootScope.chart_monitor_tmp.series.length; i++) {
                         for (var j = 0; j < $rootScope.chart_monitor_tmp.series[i].data.length; j++) {
                         //$rootScope.chart_monitor_tmp.series[i].data[j].x = (new Date()).getTime();
                         }
                         $rootScope.chart_monitor.series.push($rootScope.chart_monitor_tmp.series[i]);
                         }

                         $rootScope.chart_monitor_tmp.series = [];
                         $rootScope.$apply();*/
                        $rootScope.chart_monitor.xAxis.max = (new Date(moment())).getTime();
                        $rootScope.chart_monitor.xAxis.min = (new Date(moment().subtract(30, 'seconds'))).getTime();
                        $rootScope.$apply();
                    }, 1000);


                    socket.on('captured_packets', function (data) {
                        console.log(data);

                        var packet_received = data;
                        var exist_pc = false;
                        var index_exist_pc = 0;
                        for (var j = 0; j < $rootScope.chart_monitor_tmp.series.length; j++) {
                            if ($rootScope.chart_monitor_tmp.series[j].ip == packet_received.dst.ip) {
                                exist_pc = true;
                                index_exist_pc = j;
                                break;
                            }
                        }
                        var pcs = $rootScope.GLOBALS.active_pcs.data;
                        var exist_in_list = false;
                        if (pcs == 'undefined')return;

                        for (var index = 0; index < pcs.length; index++) {
                            if (packet_received.dst.ip == pcs[index].ip) {
                                exist_in_list = true;
                            }
                        }
                        if (!exist_in_list) {
                            return;
                        }

                        if (!exist_pc) {
                            $rootScope.chart_monitor_tmp.series.push({
                                data: [{x: (new Date()).getTime(), y: data.size}],
                                name: getNameFromIp(data.dst.ip) || data.dst.ip,
                                ip: data.dst.ip
                            });
                        } else {
                            if ($rootScope.chart_monitor_tmp.series[index_exist_pc].data.length >= 60) {
                                $rootScope.chart_monitor_tmp.series[index_exist_pc].data.splice(0, 1);
                            }
                            console.log($rootScope.chart_monitor_tmp.series[index_exist_pc].data);
                            if ($rootScope.chart_monitor_tmp.series[index_exist_pc].data.length > 1 && new Date($rootScope.chart_monitor_tmp.series[index_exist_pc].data[$rootScope.chart_monitor_tmp.series[index_exist_pc].data.length - 2].x).getSeconds() != (new Date()).getSeconds()) {
                                $rootScope.chart_monitor_tmp.series[index_exist_pc].data.push({
                                    x: (new Date()).getTime(),
                                    y: data.size
                                });
                            } else if ($rootScope.chart_monitor_tmp.series[index_exist_pc].data.length < 2) {
                                $rootScope.chart_monitor_tmp.series[index_exist_pc].data.push({
                                    x: (new Date()).getTime(),
                                    y: data.size
                                });
                            }

                            function sortFunction(a, b) {
                                var dateA = new Date(a.x).getTime();
                                var dateB = new Date(b.x).getTime();
                                return dateA > dateB ? 1 : -1;
                            }

                            $rootScope.chart_monitor_tmp.series[index_exist_pc].data.sort(sortFunction);
                        }
//                        $rootScope.chart_monitor.xAxis.max = (new Date(moment())).getUTCDate();
//                        $rootScope.chart_monitor.xAxis.min = (new Date(moment().subtract(1, 'hour'))).getUTCDate();

                        //$rootScope.chart_monitor.redraw();
//                        $rootScope.chart_monitor.series = $rootScope.chart_monitor_tmp.series;
                        $rootScope.$apply();
                    });

                });
    </script>
@endsection



