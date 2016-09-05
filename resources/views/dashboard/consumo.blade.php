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

        <div class="col-md-12">
            <section class="panel">
                <div class="panel-heading"> Consumo de maquinas por area area</div>
                <div class="panel-body">
                    <highchart id="chart_area" config="chart_area"></highchart>
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

                });
    </script>
@endsection



