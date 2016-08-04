@extends('layouts.appMonitor')

@section('main-content')
    <div class="col-md-6">
        <section class="panel">
            <div class="panel-heading"> Dispositivos
                <img src="images/gif_loader.gif" width="20" class="pull-right" ng-show="!finish_loading_list_status">
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed cf">
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
                    <tr ng-repeat="pc in pcs">
                        <td ng-bind="index +1 "></td>
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
            </div>
        </section>
    </div>

    <div class="col-md-6">
        <section class="panel">
            <div class="panel-heading"> Uso de la red
                <img src="images/gif_loader.gif" width="20" class="pull-right" ng-show="!finish_loading_red_use">
            </div>
            <div class="panel-body">
                
            </div>
        </section>
    </div>

@endsection
@section('angular-scripts')
    <script type="text/javascript">
        angular.module('Monitor').run(function ($rootScope, $API, $resource, $http, $interval) {
            $rootScope.finish_loading_list_status = false;
            $rootScope.pcs = [];

            $rootScope.alert_number_pc_inactive = 0;
            $rootScope.load_list_status = function () {
                $http.get($API.path + 'monitor/list_status')
                        .success(function (data) {
                            $rootScope.finish_loading_list_status = true;
                            $rootScope.pcs = data;
                            $rootScope.alert_number_pc_inactive = 0;
                            for (var i = 0; i < $rootScope.pcs.length; i++) {
                                if ($rootScope.pcs[i].status_network == 'N') {
                                    $rootScope.alert_number_pc_inactive++;
                                }
                            }
                        });
            };

            $interval(function () {
                $rootScope.load_list_status();
            }, 60000);
            $rootScope.load_list_status();
        });
    </script>
@endsection
