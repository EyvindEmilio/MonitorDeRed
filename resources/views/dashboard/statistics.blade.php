@extends('layouts.appMonitor')

@section('main-content')
    <div ng-controller="DashboardController">
        <div class="col-md-12">
            <section class="panel">
                <div class="panel-heading"> Informacion</div>
                <div class="panel-body">
                    <div class="profile-nav alt col-sm-4">
                        <section class="panel  text-center">
                            <div class="user-heading alt wdgt-row bg-green">
                                <i class="fa fa-shield "></i>
                            </div>
                            <div class="panel-body">
                                <div class="wdgt-value">
                                    <h1 class="count" ng-bind="alerts_today.length"></h1>
                                    <p ng-bind="alerts_today.length+' alertas(s) detectadas el dia de hoy'"></p>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-sm-4 text-center pull-right">
                        <b>Velocidad en red</b>
                        <highchart id="chart_max_current_usage" config="chart_max_current_usage"></highchart>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-md-12">
            <section class="panel">
                <div class="panel-heading"> SISTEMA DE MONITOREO DE RED</div>
                <div class="panel-body">
                    <div class="profile-nav alt col-sm-3" ng-repeat="list_con in list_connected">
                        <section class="panel  text-center">
                            <div class="user-heading alt wdgt-row terques-bg">
                                <img ng-src="@{{ list_con.image }}" height="90">
                            </div>
                            <div class="panel-body">
                                <div class="wdgt-value">
                                    <h1 class="count" ng-bind="list_con.connected+' / '+list_con.total"></h1>
                                    <p ng-bind="list_con.connected+' '+list_con.device_type+'(s) conectados'"></p>
                                </div>
                            </div>
                        </section>
                    </div>
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
                    $rootScope.list_connected = {!! json_encode($list_connected) !!};
                    $rootScope.alerts_today = {!! json_encode($alerts_today) !!};

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



