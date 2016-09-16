'use strict';

/**
 * @ngdoc service
 * @name Monitor.ApiService
 * @description
 * # ApiService
 * Service in the psicologiaApp.
 */
angular.module('Monitor')
    .service('$API', function ($q, $resource) {
        var api_url = localStorage.getItem('monitor_api');
        var base_url = (api_url || '') + '/api/';
        var base_url = (api_url || '') + 'api/';

        function getModelResource(url) {
            return $resource(url, {id: '@id'}, {
                update: {method: 'PUT'},
                create: {method: 'POST', transformRequest: angular.identity, headers: {'Content-Type': undefined}},
                patch: {method: 'POST', transformRequest: angular.identity, headers: {'Content-Type': undefined}}
            });
        }

        return {
            UsersTypes: getModelResource(base_url + 'usersTypes/:id/'),
            Areas: getModelResource(base_url + 'areas/:id/'),
            Alerts: getModelResource('/' + base_url + 'alerts/:id/'),
            Settings: getModelResource(base_url + 'settings/:id/'),
            Devices: getModelResource(base_url + 'devices/:id/'),
            DeviceTypes: getModelResource(base_url + 'device_types/:id/'),
            Users: getModelResource(base_url + 'users/:id/'),
            Monitor: getModelResource(base_url + 'monitor/'),
            Logs: getModelResource(base_url + 'logs/:id'),
            path: base_url
        };
    });
