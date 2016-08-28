@extends('layouts.appMonitor')

@section('main-content')
    <div ng-controller="DashboardController">
        <div class="col-md-12">
            <section class="panel">
                <div class="panel-heading"> SISTEMA DE MONITOREO DE RED</div>
                <div class="panel-body">

                </div>
            </section>
        </div>
        <div class="col-md-6">
            <section class="panel">

                <div class="panel-body">
                    <uib-tabset active="active">
                        <uib-tab ng-repeat="area in areas" index="$index" heading="@{{ area.name }}">
                            <highchart id="chart@{{$index}}" config="areas[$index].chart"></highchart>
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

                    for (i = 0; i < $rootScope.areas.length; i++) {
                        $rootScope.areas[i].chart = {
                            options: {
                                chart: {
                                    type: 'column',
                                    height: 300
                                }, tooltip: {
//                                            headerFormat: '<b>{series.name}</b><br>',
                                    pointFormat: 'Transferencia: {point.y_name}'
                                }
                            },
                            title: {
                                text: $rootScope.areas[i].name
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {text: 'Transferencia total(kbps)'}
                            },
                            series: []
                        };
                    }


                    socket.on('daily_statistics', function (data) {
                        for (i = 0; i < $rootScope.areas.length; i++) {
                            var data_area = [];
                            for (var j = 0; j < data.length; j++) {
                                if ($rootScope.areas[i].id == data[j].area_id) {
                                    data_area.push({
                                        name: data[j].name + '(' + data[j].ip + ')',
                                        y: data[j].size,
                                        y_name: (data[j].size > 1024) ? (Math.round((data[j].size / 1024.0) * 100) / 100 + ' Mbps') : (data[j].size + ' Kbps'),
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
                        console.log(data);
                        $rootScope.$apply();
                    });
                    Highcharts.setOptions({
                        global: {
                            useUTC: false,
                            timezone: 'America/La_Paz'
                        }
                    });

                    $rootScope.add_series = function (series_array_name) {
                        $rootScope.chart_monitor.series.push(series_array_name);
                    };


                    socket.on('captured_packets_2', function (data_in) {
                    });


                });
    </script>
@endsection



