@extends('layouts.appMonitor')

@section('main-content')
    <div ng-controller="DashboardController">
        {{--  <div class="col-md-10">
              <section class="panel">
                  <div class="panel-heading"> SISTEMA DE MONITOREO DE RED</div>
                  <div class="panel-body">

                  </div>
              </section>
          </div>--}}


        <div class="col-md-12">
            <section class="panel">
                <div class="panel-body">
                    Consumo de trafico de red por Areas
                </div>
                <div class="panel-heading">
                    <highchart id="consumo_por_areas" config="consumo_por_areas"></highchart>
                    <hr>
                    <table class="table table-bordered table-condensed table-hover cf small">
                        <thead>
                        <th>#</th>
                        <th>Area</th>
                        <th>Consumo hasta la fecha (Mb)</th>
                        </thead>
                        <tbody>
                        <tr ng-repeat="areas in consumo_por_areas.series" ng-click="info_per_area(areas.id_area)">
                            <td ng-bind="$index+1"></td>
                            <td ng-bind="areas.name"></td>
                            <td ng-bind="areas.data[0].y+ ' Mb'"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        {{--
                <div class="col-md-12">
                    <section class="panel">
                        <div class="panel-heading"> Consumo de maquinas por area area</div>
                        <div class="panel-body">
                            <highchart id="chart_area" config="chart_area"></highchart>
                        </div>
                    </section>
                </div>
                --}}


        <div class="col-md-8">
            <section class="panel">
                <div class="panel-body">
                    <uib-tabset active="active">
                        <uib-tab ng-repeat="area in areas" index="$index" heading="@{{ area.name }}">
                            <highchart id="chart@{{$index}}" config="areas[$index].chart"></highchart>
                            <hr>
                            <table class="table table-bordered table-condensed table-hover cf small">
                                <thead>
                                <th>#</th>
                                <th>Maquina</th>
                                <th>Dirección IP</th>
                                <th>Dispositivo/Area</th>
                                <th>Uso de red</th>
                                </thead>
                                <tbody>
                                <tr ng-repeat="pcs_areas in areas[$index].chart.series[0].data">
                                    <td ng-bind="$index+1"></td>
                                    <td ng-bind="pcs_areas.name_text"></td>
                                    <td ng-bind="pcs_areas.ip"></td>
                                    <td ng-bind="pcs_areas.area + ' / '+pcs_areas.type"></td>
                                    <td ng-bind="pcs_areas.y_name"></td>
                                </tr>
                                </tbody>

                                <tfoot ng-show="areas[$index].chart.series[0].data.length == 0">
                                <tr class="alert-info">
                                    <td colspan="7" class="text-center"> -- No se encontraron registros --</td>
                                </tr>
                                </tfoot>
                            </table>
                        </uib-tab>
                    </uib-tabset>
                </div>
            </section>
        </div>


    </div>
@endsection
@section('angular-scripts')
    <script type="text/javascript">
        angular.module('Monitor')
                .controller('DashboardController', function ($rootScope, $API, $resource, $http, $interval, toastr, SocketService) {
                    var socket = SocketService.socket, i;
                    $rootScope.areas = {!! $areas !!};
                    $rootScope.consumo_por_areas = {
                        options: {
                            chart: {type: 'column', height: 400},
                            tooltip: {pointFormat: 'Consumo total: {point.y} Mb'}
                        },
                        title: {text: "Consumo por areas"},
                        xAxis: {type: 'category'},
                        yAxis: {title: {text: 'Transferencia total(Mb)'}},
                        series: []
                    };

                    var list_consumo = {!! json_encode($consumo_per_areas) !!};

                    for (i = 0; i < list_consumo.length; i++) {
                        var data_area = [];
                        data_area.push({
                            name: list_consumo[i]['area'],
                            y: convertToMbps(list_consumo[i]['network_usage'])
                        });
                        $rootScope.consumo_por_areas.series.push({
                            name: list_consumo[i]['area'],
                            id_area: list_consumo[i]['id'],
                            data: data_area
                        });
                    }

                    $rootScope.chart_area = {
                        options: {
                            chart: {type: 'spline', height: 400},
                            tooltip: {pointFormat: 'Transferencia: {point.y_name}'}
                        },
                        title: {text: 'Consumo por area'},
                        xAxis: {type: 'category'},
                        yAxis: {title: {text: 'Transferencia total(Mb)'}},
                        series: []
                    };

                    $rootScope.info_per_area = function (id_area) {
                        $http.get('/info_per_area?id=' + id_area).then(function (data) {
                            var list_ips = data.data;
                            $rootScope.chart_area.series = [];
                            var data_areas;
                            for (i = 0; i < list_ips.length; i++) {

                            }
                        })
                    };

                    for (i = 0; i < $rootScope.areas.length; i++) {
                        $rootScope.areas[i].chart = {
                            options: {
                                chart: {type: 'column', height: 400},
                                tooltip: {pointFormat: 'Transferencia: {point.y_name}'}
                            },
                            title: {text: $rootScope.areas[i].name},
                            xAxis: {type: 'category'},
                            yAxis: {title: {text: 'Transferencia total(kbps)'}},
                            series: []
                        };
                    }

                    socket.on('statistics', function (data) {
                        daily_statistics(data.daily_per_areas);
                        max_current_usage(data.current_max_usage);
                    });
                    $rootScope.chart_max_current_usage = {
                        options: {
                            chart: {type: 'gauge', height: 210, plotBackgroundColor: null},
                            pane: {startAngle: -90, endAngle: 180}
                        },
                        title: {text: null},
                        yAxis: {
                            min: 0, max: 60,
                            minorTickInterval: 'auto',
                            minorTickWidth: 1,
                            minorTickLength: 10,
                            minorTickPosition: 'inside',
                            minorTickColor: '#666',
                            tickPixelInterval: 30,
                            tickWidth: 2,
                            tickPosition: 'inside',
                            tickLength: 10,
                            tickColor: '#666',
                            labels: {step: 2, rotation: 'auto'},
                            title: {text: 'Mbps'},
                            label: {text: 33},
                            plotBands: [{from: 0, to: 30, color: '#55BF3B'},
                                {from: 30, to: 50, color: '#DDDF0D'},
                                {from: 50, to: 60, color: '#DF5353'}]
                        },

                        series: [{
                            name: 'Velocidad', data: [0],
                            tooltip: {valueSuffix: ' Mbps'},
                            dataLabels: {format: '{y} Mbps'}
                        }]
                    };
                    var max_current_usage = function (data) {
                        $rootScope.chart_max_current_usage.series[0].data[0] = convertToMbps(data);
                        $rootScope.$apply();
                    };

                    function convertToMbps(value) {
                        return Math.round(value * 100 / 1024.0) / 100.0;
                    }

                    var daily_statistics = function (data) {
                        $rootScope.data_daily_statistics = data;
                        for (i = 0; i < $rootScope.areas.length; i++) {
                            var data_area = [];
                            for (var j = 0; j < data.length; j++) {
                                if ($rootScope.areas[i].id == data[j].area_id) {
                                    data_area.push({
                                        name: data[j].name + ' (' + data[j].ip + ')',
                                        name_text: data[j].name,
                                        ip: data[j].ip,
                                        y: convertToMbps(data[j].size),
                                        area: data[j].area,
                                        type: data[j].type,
                                        y_name: convertToMbps(data[j].size) + ' Mbps',
                                        drilldown: data[j].name + '(' + data[j].ip + ')'
                                    });
                                }
                            }
                            if ($rootScope.areas[i].chart.series.length == 0) {
                                $rootScope.areas[i].chart.series.push({
                                    name: 'Transferencia de datos',
                                    colorByPoint: true,
                                    data: data_area
                                });
                            } else {
                                $rootScope.areas[i].chart.series[0].data = data_area;
                            }
                        }
                        $rootScope.$apply();
                    };

                    $rootScope.add_series = function (series_array_name) {
                        $rootScope.chart_monitor.series.push(series_array_name);
                    };

                });
    </script>
@endsection



