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
        var base_url = (api_url || '') + 'api/';

        function getModelResource(url) {
            return $resource(url, {id: '@id'}, {
                update: {method: 'PUT'},
                create: {method: 'POST', transformRequest: angular.identity, headers: {'Content-Type': undefined}},
                patch: {method: 'PATCH', transformRequest: angular.identity, headers: {'Content-Type': undefined}}
            });
        }

        return {
            UsersTypes: getModelResource(base_url + 'usersTypes/:id/'),
            Users: getModelResource(base_url + 'users/:id/'),
            path: base_url
        };
    });