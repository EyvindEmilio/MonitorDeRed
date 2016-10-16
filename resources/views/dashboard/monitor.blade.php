@extends('layouts.appMonitor')

@section('main-content')
    <div ng-controller="DashboardController">
        <div class="col-md-12">
            <section class="panel">
                <div class="panel-heading"> Consumo de Ancho de Banda</div>
                <div class="panel-body">
                    <div class="IMAGE_CHART">
                        <highchart id="chart1" config="chart_monitor"></highchart>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-8">
            <section class="panel">
                <div class="panel-heading"> Dispositivos
                    <input ng-model="filter_list_pcs_scanned" placeholder="Buscar.." class="form-control input-sm"
                           style="display: inline-block;width: auto">
                    <img src="{{ asset('images/infinite_loader.gif')}}" width="30" class="pull-right"
                         title="Escaneando...">
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
                            <th>Tipo de dispositivo / Area</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="pc in GLOBALS.active_pcs.data | filter: filter_list_pcs_scanned"
                            style="cursor: pointer;@{{ (pc.name=='-- Desconocido --')?'cursor: pointer;background: rgba(241, 196, 15,0.1) !important;':'' }}">
                            <td ng-bind="$index +1 "></td>
                            <td ng-bind="pc.name" data-ng-click="scan_pc(pc)"></td>
                            <td ng-bind="pc.ip" data-ng-click="scan_pc(pc)"></td>
                            <td ng-bind="pc.mac || '--'" data-ng-click="scan_pc(pc)"></td>
                            <td ng-bind="pc.manufacturer || '--'" data-ng-click="scan_pc(pc)"></td>
                            <td ng-bind="pc.device_type?(pc.device_type+' / '+pc.area):'--'"
                                data-ng-click="scan_pc(pc)"></td>
                            <td>
                                <button class="btn btn-xs btn-@{{pc.status_network=='Y'?'success':'danger'}}"
                                        style="width: 100%" ng-bind="pc.status_network=='Y'?'o':'-'"
                                        title="@{{ pc.status_network=='Y'?'Activo':'Inactivo' }}">
                                </button>

                                <button class="btn btn-xs btn-info"
                                        data-ng-click="appendRegister(pc)"
                                        data-ng-if="pc.name == '-- Desconocido --'"
                                        style="width: 100%;"
                                        title="Registrar Dispositivo">Reg.
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

        <div class="col-md-4">
            <section class="panel">
                <div class="panel-heading">
                    <span>Escaneo de dispositvo</span>
                    <br>
                    <span ng-show="pc_scanned.name">: @{{pc_scanned.name +' ('+pc_scanned.ip+')'}}</span>
                    <img src="/images/gif_loader.gif" width="20" class="pull-right"
                         ng-show="!finish_loading_scan_device">
                </div>
                <div class="panel-body">
                    <p ng-show="!pc_scanned.name" class="alert alert-info">
                        ** Selecciones el dispositivo a escanear puertos
                    </p>
                    <p ng-show="!finish_loading_scan_device" class="alert alert-info">
                        Escaneando . . .
                    </p>
                    <table class="table table-bordered table-striped table-condensed cf small"
                           ng-show="pc_scanned.name">
                        <thead class="cf">
                        <tr>
                            <th>Puerto</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Servicio</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="pc_scan in pc_scanned.list_ports">
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
                        <tfoot>
                        <tr ng-show="pc_scanned.name && pc_scanned.list_ports.length == 0 " class="alert-success">
                            <td colspan="5">No se ha detectado puertos abiertos en el dispositivo</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('angular-scripts')
    <script type="text/javascript">

        angular.module('Monitor')
                .controller('DashboardController', function ($rootScope, $API, $resource, $http, $interval, toastr, SocketService, ModelService, $uibModal) {
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
                            useUTC: false,
                            timezone: 'America/La_Paz'
                        }
                    });
                    $rootScope.chart_monitor = {
                        options: {
                            chart: {
                                type: 'spline',
                                height: 500
                            }, tooltip: {
                                headerFormat: '<b>{series.name}</b><br>',
                                pointFormat: 'Transferencia: {point.y:.2f} Kbps'
                            }, lang: {
                                loading: "Cargando.."
                            }
                        },
                        series: [],
                        loading: true,
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
                            min: (new Date(moment().subtract(30, 'seconds'))).getTime(),
                            max: (new Date(moment())).getTime()

                        },
                        yAxis: {
                            title: {
                                text: 'Tasa (kbps)'
                            },
                            min: 0,
//                            max: 2000
                        }
                    };

                    $rootScope.add_series = function (series_array_name) {
                        $rootScope.chart_monitor.series.push(series_array_name);
                    };

                    function getNameFromIp(ip) {
                        var pcs = $rootScope.GLOBALS.active_pcs.data;
                        var ip_name = ip;
                        for (var index = 0; index < pcs.length; index++) {
                            if (pcs[index].ip == ip) {
                                ip_name = pcs[index].name + ' <br>(' + ip + ')';
                            }
                        }

                        return ip_name;
                    }

                    $rootScope.chart_monitor_tmp = {};
                    $rootScope.chart_monitor_tmp.series = [];
                    setInterval(function () {
                        $rootScope.chart_monitor.series = $rootScope.chart_monitor_tmp.series;
                        $rootScope.chart_monitor.xAxis.max = (new Date(moment())).getTime();
                        $rootScope.chart_monitor.xAxis.min = (new Date(moment().subtract(30, 'seconds'))).getTime();
                        $rootScope.$apply();
                    }, 1000);

                    socket.on('captured_packets_2', function (data_in) {
                        $rootScope.chart_monitor.loading = false;
                        var data = data_in;
                        for (var k = 0; k < data_in.length; k++) {
                            data = data_in[k];
                            var packet_received = data_in[k];
                            var exist_pc = false;
                            var index_exist_pc = 0;
                            for (var j = 0; j < $rootScope.chart_monitor_tmp.series.length; j++) {
                                if ($rootScope.chart_monitor_tmp.series[j].ip == packet_received.src.ip) {
                                    exist_pc = true;
                                    index_exist_pc = j;
                                    break;
                                }
                            }
                            var pcs = $rootScope.GLOBALS.active_pcs.data;
                            var exist_in_list = false;
                            if (pcs == undefined)return;

                            for (var index = 0; index < pcs.length; index++) {
                                if (packet_received.src.ip == pcs[index].ip) {
                                    exist_in_list = true;
                                }
                            }
                            /*if (!exist_in_list) {
                             return;
                             }
                             */
                            if (!exist_pc) {
                                $rootScope.chart_monitor_tmp.series.push({
                                    data: [{x: (new Date()).getTime(), y: data.size}],
                                    name: getNameFromIp(data.src.ip) || data.src.ip,
                                    ip: data.src.ip, marker: {
                                        enabled: false
                                    }
                                });
                            } else {
                                if ($rootScope.chart_monitor_tmp.series[index_exist_pc].data.length >= 60) {
                                    $rootScope.chart_monitor_tmp.series[index_exist_pc].data.splice(0, 1);
                                }
//                                console.log($rootScope.chart_monitor_tmp.series[index_exist_pc].data);
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
                            $rootScope.$apply();
                        }
                    });

                    $rootScope.appendRegister = function (pc) {
                        var ModelDevices = (new ModelService.Devices({
                            default_fields: [
                                {name: 'ip', value: pc.ip},
                                {name: 'mac', value: pc.mac},
                                {name: 'description', value: pc.manufacturer}]
                        }));
                        ModelDevices.initValues = pc;
                        console.log(pc, ModelDevices);
                        var modalInstance = $uibModal.open({
                            templateUrl: 'app/views/crud/modals/crudModalCreate.html',
                            controller: 'ModalCreateCrudController',
                            size: 'md',
                            resolve: {
                                Model: function () {
                                    return ModelDevices;
                                }
                            }
                        });
                    }
                });
    </script>
@endsection



