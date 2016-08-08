@extends('layouts.appMonitor')

@section('main-content')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel">
                <div class="panel-heading">Configuracion</div>
                <div class="panel-body">
                    <form class="form-horizontal" data-ng-submit="save_params_settings()">
                        <div class="form-group">
                            <label class="col-md-2">Direccion de Red</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="192.168.1.0" required
                                       ng-model="settings.network_address">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2">Puerta de enlace (Gateway)</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="192.168.1.1" required
                                       ng-model="settings.gateway">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2">Mascara (/X)</label>
                            <div class="col-md-6">
                                <select class="form-control" ng-model="settings.mask"
                                        ng-options="mask.id as mask.name for mask in [{id:8,name:'8'},{id:16,name:'16'},{id:24,name:'24'},{id:32,name:'32'}]"
                                        required></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2">Tiempo de verificaion de red</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control" min="2" max="500" placeholder="En segundos"
                                       required ng-model="settings.time_check_network">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2">Sistema habilitado</label>
                            <div class="col-md-6">
                                <select class="form-control" ng-model="settings.active_system" required>
                                    <option value="Y" label="Habilitado"></option>
                                    <option value="N" label="Inhabilitado"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-success">Guardar Parametros</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @section('angular-scripts')
                <script type="text/javascript">
                    angular.module('Monitor').run(function ($rootScope, ModelService, $API) {
                        $rootScope.settings = {!! $settings !!};
                        $rootScope.save_params_settings = function () {
                            var data = angular.copy($rootScope.settings);
                            data.id = 1;
                            (new $API.Settings()).$patch(data)
                                    .then(function (data) {
                                        window.location.reload();
                                    })
                        };
                    });
                </script>
            @endsection
        </div>
    </div>
@endsection
