@extends('layouts.appMonitor')

@section('main-content')
    <div class="col-md-12">
        <section class="panel">
            <div crud-directive="model_alerts"></div>
        </section>
    </div>

    <div class="col-md-12">
        <section class="panel">
            <div class="panel-heading">
                Listado de puertos en la red
                <img src="/images/gif_loader.gif" width="20" class="pull-right"
                     ng-show="!finish_loading_scan_all_ports">
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed cf small">
                    <thead class="cf">
                    <tr>
                        <th>#</th>
                        <th>Ip Host</th>
                        <th>Direccion MAC</th>
                        <th>Latencia</th>
                        <th>Puertos</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="list in GLOBALS.list_scan_all_ports">
                        <td ng-bind="$index+1"></td>
                        <td ng-bind="list.ip"></td>
                        <td ng-bind="list.mac"></td>
                        <td ng-bind="list.latency+' Seg'"></td>
                        <td>
                            <span class="btn btn-xs btn-success" ng-show="list.ports.length==0">
                                Todos los Puertos cerrados
                            </span>
                            <table ng-show="list.ports.length>0" class="table table-bordered cf">
                                <thead>
                                <tr>
                                    <td>#</td>
                                    <td>Puerto</td>
                                    <td>Estado</td>
                                    <td>Servicio</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="ports in list.ports">
                                    <td ng-bind="$index+1"></td>
                                    <td ng-bind="ports.port"></td>
                                    <td>
                                        <span ng-bind="ports.status == 'open'?'Abierto':'Cerrado'"></span>
                                    </td>
                                    <td>
                                        <span class="btn btn-xs btn-@{{ ports.service=='unknown'?'danger':'warning' }}"
                                              ng-bind="ports.service"></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="col-md-7">
        <section class="panel">
            <div class="panel-heading">
                Captura de paquetes (últimos)<a href="" ng-click="show_capture()"> (presione aqui)</a>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed cf small">
                    <thead class="cf">
                    <tr>
                        <th>#</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Tamaño del paquete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="cap in data_capture">
                        <td ng-bind="$index+1"></td>
                        <td ng-bind="cap.src.ip+' : '+cap.src.port"></td>
                        <td ng-bind="cap.dst.ip+' : '+cap.dst.port"></td>
                        <td ng-bind="cap.size+' kb'"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

@endsection
@section('angular-scripts')
    <script type="text/javascript">
        angular.module('Monitor').run(function ($rootScope, $API, $resource, $http, $interval, toastr, ModelService) {
            $rootScope.data_capture = {};
            $rootScope.model_alerts = new ModelService.Alerts();


            $rootScope.show_capture = function () {
                $http.get('/' + $API.path + 'monitor/denial_service')
                        .then(function (data) {
                            $rootScope.data_capture = data.data;
                        }, function (data) {

                        });
            }
        });
    </script>
@endsection
