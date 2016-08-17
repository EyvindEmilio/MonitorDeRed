@extends('layouts.appMonitor')

@section('main-content')
    <div class="col-md-12">
        <section class="panel">
            <div class="panel-heading"> Ataques detectados</div>
            <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed cf small">
                    <thead class="cf">
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Origen</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tr>
                        <td>1</td>
                        <td>Denegacion de servicios</td>
                        <td>--</td>
                        <td>
                            <button class="btn btn-xs  btn-warning">Alerta</button>
                        </td>
                        <td>192.168.1.1</td>
                    </tr>
                </table>
            </div>
        </section>
    </div>
    <div class="col-md-7">
        <section class="panel">
            <div class="panel-heading">
                Captura de paquetes <a href="" ng-click="show_capture()"> (presione aqui)</a>
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
                        <td ng-bind="cap.src.ip+':'+cap.src.port"></td>
                        <td ng-bind="cap.dst.ip+':'+cap.dst.port"></td>
                        <td ng-bind="cap.size+' kb'"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="col-md-5">
        <section class="panel">
            <div class="panel-heading"> Escaneo de dispositvo @{{pc_scanned.name}}
                <img src="images/gif_loader.gif" width="20" class="pull-right" ng-show="!finish_loading_scan_device">
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

@endsection
@section('angular-scripts')
    <script type="text/javascript">
        angular.module('Monitor').run(function ($rootScope, $API, $resource, $http, $interval, toastr) {
            $rootScope.data_capture = {};
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
