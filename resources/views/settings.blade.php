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
                            <label class="col-md-2">Interfaz de red</label>
                            <div class="col-md-6">
                                <select class="form-control" ng-model="settings.interface"
                                        ng-options="inter for inter in ['eth0','eth1','wlan0','wlan1']"
                                        required></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2">Intervalo de tiempo para envio de datos de monitoreo</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control" min="1" max="5" placeholder="En segundos"
                                       required ng-model="settings.time_interval_for_sending_monitoring_data">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2">Intervalo de tiempo para escaneo de puertos</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control" min="20" max="180" placeholder="En segundos"
                                       required ng-model="settings.time_interval_for_scan_ports">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2">Intervalo de tiempo de deteccion de Denegacion de servicios</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control" min="30" max="180" placeholder="En segundos"
                                       required ng-model="settings.dos_time_for_check_attacks">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2">Paquetes ICMP, para comprobacion de DoS</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control" min="1000" max="10000"
                                       placeholder="En segundos"
                                       required ng-model="settings.dos_max_packets_received">
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
                            (new $API.Settings()).$update(data)
                                    .then(function () {
                                        window.location.reload();
                                    })
                        };
                    });
                </script>
            @endsection
        </div>
    </div>
@endsection
