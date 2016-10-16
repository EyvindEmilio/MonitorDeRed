@extends('layouts.appMonitor')

@section('main-content')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel">
                <div class="panel-heading">Configuración del sistema</div>
                <div class="panel-body">
                    <form class="form-horizontal" data-ng-submit="save_params_settings()">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}"/>
                        <div class="form-group">
                            <label class="col-md-3">Direccion de Red</label>
                            <div class="col-md-6">
                                @if(\App\User::isAdmin())
                                    <input type="text" class="form-control" placeholder="192.168.1.0" required
                                           ng-model="settings.network_address">
                                @else
                                    <span ng-bind="settings.network_address"></span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3">Puerta de enlace (Gateway)</label>
                            <div class="col-md-6">
                                @if(\App\User::isAdmin())
                                    <input type="text" class="form-control" placeholder="192.168.1.1" required
                                           ng-model="settings.gateway">
                                @else
                                    <span ng-bind="settings.gateway"></span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3">Mascara (/X)</label>
                            <div class="col-md-6">
                                @if(\App\User::isAdmin())
                                    <select class="form-control" ng-model="settings.mask"
                                            ng-options="mask.id as mask.name for mask in [{id:8,name:'8'},{id:16,name:'16'},{id:24,name:'24'},{id:32,name:'32'}]"
                                            required></select>
                                @else
                                    <span ng-bind="'/'+settings.mask"></span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3">Interfaz de red</label>
                            <div class="col-md-6">
                                @if(\App\User::isAdmin())
                                    <select class="form-control" ng-model="settings.interface"
                                            ng-options='inter for inter in {!! json_encode($interfaces) !!}'
                                            required></select>
                                @else
                                    <span ng-bind="settings.interface"></span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3">Intervalo de tiempo para envio de datos de monitoreo</label>
                            <div class="col-md-6">
                                @if(\App\User::isAdmin())
                                    <input type="number" class="form-control" min="1" max="5" placeholder="En segundos"
                                           required ng-model="settings.time_interval_for_sending_monitoring_data">
                                @else
                                    <span ng-bind="settings.time_interval_for_sending_monitoring_data +' Seg.'"></span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3">Intervalo de tiempo para escaneo de puertos</label>
                            <div class="col-md-6">
                                @if(\App\User::isAdmin())
                                    <input type="number" class="form-control" min="10" max="180"
                                           placeholder="En segundos"
                                           required ng-model="settings.time_interval_for_scan_ports">
                                @else
                                    <span ng-bind="settings.time_interval_for_scan_ports +' Seg.'"></span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3">Intervalo de tiempo de deteccion de Denegacion de servicios</label>
                            <div class="col-md-6">
                                @if(\App\User::isAdmin())
                                    <input type="number" class="form-control" min="30" max="180"
                                           placeholder="En segundos"
                                           required ng-model="settings.dos_time_for_check_attacks">
                                @else
                                    <span ng-bind="settings.dos_time_for_check_attacks +' Seg.'"></span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3">Paquetes ICMP, para comprobacion de DoS</label>
                            <div class="col-md-6">
                                @if(\App\User::isAdmin())
                                    <input type="number" class="form-control" min="1000" max="10000"
                                           placeholder="En segundos"
                                           required ng-model="settings.dos_max_packets_received">
                                @else
                                    <span ng-bind="settings.dos_max_packets_received +' paq.'"></span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3">Intervalo de escaneo SNMP</label>
                            <div class="col-md-6">
                                @if(\App\User::isAdmin())
                                    <input type="number" class="form-control" min="10" max="180"
                                           placeholder="En segundos"
                                           required ng-model="settings.interval_snmp_scan">
                                @else
                                    <span ng-bind="settings.interval_snmp_scan +' Seg.'"></span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3">Saturacion de red (Kbps/s)</label>
                            <div class="col-md-2">
                                @if(\App\User::isAdmin())
                                    <input type="number" class="form-control" min="100" max="700000"
                                           placeholder="En Kbps"
                                           required ng-model="settings.max_bandwidth_saturation">
                                @else
                                    <span ng-bind="settings.max_bandwidth_saturation +' Kbps.'"></span>
                                @endif
                            </div>
                            <div class="col-md-2">
                                @if(\App\User::isAdmin())
                                    <input type="number" class="form-control" min="10"
                                           placeholder="En segundos"
                                           required ng-model="settings.interval_send_saturation">
                                @else
                                    <span ng-bind="settings.interval_send_saturation +' Seg.'"></span>
                                @endif
                            </div>
                        </div>
                        @if(Auth::user()['user_type']==1)
                            <div style="background-color: #ecf0f1;padding: 10px 15px;margin: 0 0 10px 0;">
                                <h4>Envio de Correo</h4>
                                <hr>
                                <div class="form-group">
                                    <label class="col-md-3">Saturacion de red</label>
                                    <div class="col-md-5">
                                        <select class="form-control" ng-model="settings.send_mail_saturation" required>
                                            <option value="Y" label="Enviar correo de alerta"></option>
                                            <option value="N" label="No enviar correo"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Denegacion de Servicios</label>
                                    <div class="col-md-5">
                                        <select class="form-control" ng-model="settings.send_mail_dos" required>
                                            <option value="Y" label="Enviar correo de alerta"></option>
                                            <option value="N" label="No enviar correo"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Puerta Trasera</label>
                                    <div class="col-md-5">
                                        <select class="form-control" ng-model="settings.send_mail_backdoor" required>
                                            <option value="Y" label="Enviar correo de alerta"></option>
                                            <option value="N" label="No enviar correo"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Dispositivos Inactivos</label>
                                    <div class="col-md-5">
                                        <select class="form-control" ng-model="settings.send_mail_inactive_pc" required>
                                            <option value="Y" label="Enviar correo de alerta"></option>
                                            <option value="N" label="No enviar correo"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="col-md-3">Estado del sistema</label>
                            <div class="col-md-6">
                                @if(\App\User::isAdmin())
                                    <select class="form-control" ng-model="settings.active_system" required>
                                        <option value="Y" label="Abierto"></option>
                                        <option value="N" label="Cerrado"></option>
                                    </select>
                                @else
                                    <span ng-bind="settings.active_system =='Y'?'Abierto':'Cerrado'"></span>
                                @endif
                            </div>
                        </div>
                        @if(Auth::user()['user_type']==1)
                            <div class="form-group">
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-success">Guardar Parametros</button>
                                </div>
                            </div>
                        @endif
                        <div>

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
