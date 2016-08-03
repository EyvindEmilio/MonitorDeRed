'use strict';

/**
 * @ngdoc service
 * @name Monitor.InterceptorService
 * @description
 * # InterceptorService
 * Service in the planillasApp.
 */
angular.module('Monitor')
    .service('InterceptorService', function ($rootScope, $q) {
        var numberRequest = 0;
        return {
            'request': function (config) {
                numberRequest++;
                $rootScope.LOADER_ASYNC = true;
                return config;
            },
            'requestError': function (rejection) {
                numberRequest--;
                if (numberRequest <= 0) {
                    $rootScope.LOADER_ASYNC = false;
                    numberRequest = 0;
                }
                return $q.reject(rejection);
            },
            'responseError': function (rejection) {
                numberRequest--;
                if (numberRequest <= 0) {
                    $rootScope.LOADER_ASYNC = false;
                    numberRequest = 0;
                }
                return $q.reject(rejection);
            },
            'response': function (response) {
                numberRequest--;
                if (numberRequest <= 0) {
                    $rootScope.LOADER_ASYNC = false;
                    numberRequest = 0;
                }
                return response;
            }
        };
    });
