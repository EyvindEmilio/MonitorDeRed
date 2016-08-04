@extends('layouts.appMonitor')

@section('main-content')
    <div class="col-md-6">
        <section class="panel">
            <div class="panel-heading"> Uso de la red</div>
            <div class="panel-body">
                <div class="IMAGE_CHART">
                    <highchart id="chart1" config="chart_monitor"></highchart>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-6">
        <section class="panel">
            <div class="panel-heading"> Dispositivos
                <img src="images/gif_loader.gif" width="20" class="pull-right" ng-show="!finish_loading_list_status">
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed table-hover cf">
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
                    <tr ng-repeat="pc in pcs" ng-click="scan_pc(pc)" style="cursor: pointer">
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
    <span class="clearfix"></span>
    <div class="col-md-4">
        <section class="panel">
            <div class="panel-heading"> Escaneo de dispositvo @{{pc_scanned.name}}
                <img src="images/gif_loader.gif" width="20" class="pull-right" ng-show="!finish_loading_scan_device">
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed cf">
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

@endsection
@section('angular-scripts')
    <script type="text/javascript">
        angular.module('Monitor').run(function ($rootScope, $API, $resource, $http, $interval, toastr) {
            $rootScope.finish_loading_list_status = false;
            $rootScope.pcs = [];

            $rootScope.alert_number_pc_inactive = 0;

            $rootScope.load_list_status = function () {
                $http.get($API.path + 'monitor/list_status')
                        .success(function (data) {
                            $rootScope.finish_loading_list_status = true;
                            $rootScope.pcs = data;
                            $rootScope.alert_number_pc_inactive = 0;
                            var list_pcs_for_monitoring = [];
                            for (var i = 0; i < $rootScope.pcs.length; i++) {
                                if ($rootScope.pcs[i].status_network == 'N') {
                                    $rootScope.alert_number_pc_inactive++;
                                } else {
                                    list_pcs_for_monitoring.push($rootScope.pcs[i]);
                                }
                            }
                            $rootScope.verify_pc_scan_monitoring(list_pcs_for_monitoring);
                        });
            };

            $interval(function () {
                $rootScope.load_list_status();
            }, {{ $settings['time_check_network']*1000 }});
            $rootScope.load_list_status();

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

            $rootScope.chart_monitor = {
                options: {
                    chart: {
                        type: 'line',
                        heiht: 100
                    }
                },
                series: [],
                title: {
                    text: 'Monitoreo de red'
                },
                xAxis: {
                    title: {text: 'Seg'},
                    min: 0,
                    max: 60,
                    lineWidth: 1
                },
                yAxis: {
                    title: {
                        text: 'Tasa (kbps)'
                    },
                    min: 0,
                    max: 100
                }
            };

            $rootScope.add_series = function (series_array_name) {
                $rootScope.chart_monitor.series.push(series_array_name);
            };

            $rootScope.verify_pc_scan_monitoring = function (list_pcs) {
//                for demo
                for (var i = 0; i < list_pcs.length; i++) {
                    var exist_pc = false;
                    var index_exist_pc = 0;
                    for (var j = 0; j < $rootScope.chart_monitor.series.length; j++) {
                        if ($rootScope.chart_monitor.series[j].name == list_pcs[i].name) {
                            exist_pc = true;
                            index_exist_pc = j;
                            break;
                        }
                    }

                    if (!exist_pc) {
                        $rootScope.chart_monitor.series.push({
                            data: [{
                                x: 1,
                                y: Math.random() * 100
                            }],
                            name: list_pcs[i].name
                        });
                    } else {
                        $rootScope.chart_monitor.series[j].data.push({x: 2, y: Math.random() * 100})
                    }
                }
            };
        });
    </script>
@endsection
