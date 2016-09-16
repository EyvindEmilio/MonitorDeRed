'use strict';

/**
 * @ngdoc service
 * @name Monitor.ModelService
 * @description
 * # ModelService
 * Service in the planillasApp.
 */

angular.module('Monitor')
    .service('ModelService', function ($API, $rootScope) {
        /**
         #Model object example:
         {
            resource: $resource,
            params: Array-FieldObjects
            showFields: Array-string-Fields-show
         }

         #Resource
         $resource
         #FieldObjects
         fieldObject = {
                label:'String',//render in html
                name:'String',//service endpoint field
                type:string|largeString|boolean|date|time|datetime|email|file|color|image|password|range
                required:true|false,
                Model:ModelObject,
                list:['stringA','stringB','stringC'],
                results:[promiseResultArray]
            }
         #ShowFields
         showFields = ['StringField','StringField',....]
         #filterFields = ['','',..]
         **/
        var BaseModel = function (child_model, config) {
            config = config || {};
            this.id_name = 'id';
            this.fields = [];
            this.extra_fields = [];
            this.showFields = [];
            this.filterFields = [];
            this.disabled_fields = [];
            this.default_fields = [];
            this.dataResponse = {};
            this.nameView = 'name';
            this.config = {
                title: '',
                subTitle: ''
            };
            this.searchEnabled = false;
            this.editable = true;
            this.delete = true;
            this.add_new = true;
            var context = this;
            if (typeof (child_model) === 'function') {
                child_model(context);
                angular.forEach(config, function (value, key) {
                    context[key] = value;
                });
                context.extra_query_params = config.query_params || [];
                context.default_fields = config.default_fields || [];
                context.disabled_fields = config.disabled_fields || [];
            }
        };


        var UsersTypes = function (config) {
            return new BaseModel(function (context) {
                    context.id_name = 'id';
                    context.resource = $API.UsersTypes;
                    context.fields = [
                        {label: 'Nombre', name: 'name', type: 'string', required: true},
                        {label: 'Descripcion', name: 'description', type: 'string', required: true}
                    ];
                    context.showFields = ['name', 'description'];
                    context.nameView = 'name';
                    context.config = {title: 'Lista de usuarios'};
                    context.add_new = false;
                    context.delete = false;
                    context.editable = false;
                    context.searchEnabled = true;
                }, config
            );
        };

        var Alerts = function (config) {
            return new BaseModel(function (context) {
                    context.id_name = 'id';
                    context.resource = $API.Alerts;
                    context.fields = [
                        {label: 'Tipo de ataque registado', name: 'type', type: 'string', required: true},
                        {label: 'Direccion IP Origen', name: 'ip_src', type: 'string', required: true},
                        {label: 'Direccion IP Destino', name: 'ip_dst', type: 'string', required: true}
                    ];
                    context.extra_fields = [{label: 'Fecha de incidente', name: 'created_at'}];
                    context.showFields = ['type', 'ip_src', 'ip_dst'];
                    context.nameView = 'type';
                    context.config = {title: 'Alertas de ataques detectados'};
                    context.add_new = false;
                    context.delete = false;
                    context.editable = false;
                    context.searchEnabled = false;
                    context.view_template = 'view_attacks.html';
                    context.view_config = function (scope) {
                        scope.query_per_area = '';
                        scope.paginationParams['start_date'] = new Date((new moment()).subtract(7, 'days'));
                        scope.paginationParams['end_date'] = new Date(new moment());
                        scope.$watch('paginationParams', function () {
                            scope.query_per_area = '&start_date=' + moment(scope.paginationParams['start_date']).format('Y-M-D') + '&end_date=' + moment(scope.paginationParams['end_date']).format('Y-M-D');
                        }, true);
                    }
                }, config
            );
        };

        var Areas = function (config) {
            return new BaseModel(function (context) {
                    context.id_name = 'id';
                    context.resource = $API.Areas;
                    context.fields = [
                        {label: 'Nombre', name: 'name', type: 'string', required: true},
                        {label: 'Descripcion', name: 'description', type: 'string', required: true}
                    ];
                    context.showFields = ['name', 'description'];
                    context.nameView = 'name';
                    context.config = {title: 'Areas de trabajo'};
                    context.add_new = true;
                    context.delete = true;
                    context.editable = true;
                    context.searchEnabled = true;
                }, config
            );
        };

        var DeviceTypes = function (config) {
            return new BaseModel(function (context) {
                    context.id_name = 'id';
                    context.resource = $API.DeviceTypes;
                    context.fields = [
                        {label: 'Nombre', name: 'name', type: 'string', required: true},
                        {label: 'Logo', name: 'image', type: 'image', required: false, width: 120, height: 120},
                        {label: 'Descripcion', name: 'description', type: 'string', required: true},
                        {label: 'Fabricante', name: 'manufacturer', type: 'string', required: true}
                    ];
                    context.showFields = ['image', 'name', 'description', 'manufacturer'];
                    context.nameView = 'name';
                    context.config = {title: 'Tipos de dispositivos'};
                    context.add_new = true;
                    context.delete = true;
                    context.editable = true;
                    context.searchEnabled = true;
                }, config
            );
        };

        var Devices = function (config) {
            return new BaseModel(function (context) {
                    context.id_name = 'id';
                    context.resource = $API.Devices;
                    context.fields = [
                        {label: 'Nombre', name: 'name', type: 'string', required: true},
                        {label: 'Ip', name: 'ip', type: 'string', required: true},
                        {label: 'Descripcion', name: 'description', type: 'string', required: true},
                        {
                            label: 'Estado',
                            name: 'status',
                            type: 'selecto',
                            choices: [{value: 'Y', label: 'Activo'}, {value: 'N', label: 'Inactivo'}],
                            required: true
                        },
                        {
                            label: 'Area',
                            name: 'area',
                            type: 'select',
                            model: new Areas(),
                            required: true,
                            custom: function (data) {
                                return data.name
                            }
                        },
                        {
                            label: 'Tipo de dispositivo',
                            name: 'device_type',
                            type: 'select',
                            model: new DeviceTypes(),
                            required: true, custom: function (data) {
                            return data.name;
                        }
                        },
                        {label: 'Notas', name: 'notes', type: 'string', required: false}
                    ];
                    context.extra_fields = [{label: 'Fecha de registro', name: 'created_at'}, {
                        label: 'Ultima modificacion',
                        name: 'updated_at'
                    }];
                    context.showFields = ['name', 'description', 'ip', 'status', 'area', 'device_type', 'notes'];
                    context.nameView = 'name';
                    context.config = {title: 'Dispositivos registrados'};
                    context.add_new = true;
                    context.delete = true;
                    context.editable = true;
                    context.searchEnabled = true;
                }, config
            );
        };

        var Logs = function (config) {
            return new BaseModel(function (context) {
                    context.id_name = 'id';
                    context.resource = $API.Logs;
                    context.fields = [
                        {
                            label: 'Usuario', name: 'user', type: 'string', required: true,
                            custom: function (data) {
                                var user_type = '';
                                if (data.user_type == 1) {
                                    user_type = 'Administrador';
                                } else if (data.user_type == 2) {
                                    user_type = 'Colaborador';
                                } else {
                                    user_type = 'Jefe';
                                }
                                return data.first_name + ' ' + data.last_name + ' (' + user_type + ')';
                            }
                        },
                        {label: 'Ip Origen', name: 'ip', type: 'string', required: true},
                        {label: 'Tipo', name: 'type', type: 'string', required: true},
                        {label: 'Descripcion', name: 'description', type: 'string', required: true}
                    ];
                    context.extra_fields = [{label: 'Fecha de registro', name: 'created_at'}, {
                        label: 'Ultima modificacion',
                        name: 'updated_at'
                    }];
                    context.showFields = ['user', 'ip', 'type', 'description'];
                    context.nameView = 'name';
                    context.config = {title: 'Registros del sistema (Logs)'};
                    context.add_new = false;
                    context.delete = false;
                    context.editable = false;
                    context.searchEnabled = true;
                }, config
            );
        };

        var Users = function (config) {
            return new BaseModel(function (context) {
                    context.id_name = 'id';
                    context.resource = $API.Users;
                    context.fields = [
                        {label: 'Nombres', name: 'first_name', type: 'string', required: true},
                        {label: 'Apellidos', name: 'last_name', type: 'string', required: true},
                        {label: 'Foto de Perfil', name: 'image', type: 'image', required: false},
                        {label: 'Correo', name: 'email', type: 'string', required: true},
                        {
                            label: 'Estado', name: 'status', type: 'boolean', required: false,
                            custom: function (data) {
                                if (data === 'Y') {
                                    return '<button class="btn btn-success btn-xs">Activo</button> ';
                                } else {
                                    return '<button class="btn btn-warning btn-xs">Inactivo</button> ';
                                }
                            }
                        },
                        {
                            label: 'Tipo de usuario',
                            name: 'user_type',
                            type: 'select',
                            model: new UsersTypes(),
                            custom: function (data) {

                                return data.name;
                            },
                            required: false
                        }
                    ];
                    context.extra_fields = [{label: 'Fecha de creacion', name: 'created_at'}];
                    context.showFields = ['image', 'first_name', 'last_name', 'email', 'status', 'user_type'];
                    context.nameView = 'email';
                    context.config = {title: 'Lista de usuarios'};
                    context.add_new = true;
                    context.delete = true;
                    context.editable = true;
                    context.searchEnabled = true;
                    context.table_name = 'account';
                    context.name = 'accounts';
                    // context.view_template = 'view_2.html';
                },
                config
            );
        };

        return {
            UsersTypes: UsersTypes,
            Areas: Areas,
            DeviceTypes: DeviceTypes,
            Devices: Devices,
            Users: Users,
            Alerts: Alerts,
            Logs: Logs
        };
    });
