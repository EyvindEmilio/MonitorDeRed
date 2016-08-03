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
                context.extra_query_params = config.query_params || {};
                context.default_fields = config.default_fields || {};
                context.disabled_fields = config.disabled_fields || {};
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

        var Users = function (config) {
            return new BaseModel(function (context) {
                    context.id_name = 'id';
                    context.resource = $API.Users;
                    context.fields = [
                        {label: 'Nombres', name: 'first_name', type: 'string', required: true},
                        {label: 'Apellidos', name: 'last_name', type: 'string', required: true},
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
                    context.showFields = ['first_name', 'last_name', 'email', 'status', 'user_type'];
                    context.nameView = 'email';
                    context.config = {title: 'Lista de usuarios'};
                    context.add_new = true;
                    context.delete = true;
                    context.editable = true;
                    context.searchEnabled = true;
                },
                config
            );
        };

        return {
            UsersTypes: UsersTypes,
            Users: Users
        };
    });
