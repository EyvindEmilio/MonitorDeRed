'use strict';

/**
 * @ngdoc filter
 * @name planillasEmiApp.filter:momentDate
 * @function
 * @description
 * # momentDate
 * Filter in the planillasEmiApp.
 */
angular.module('planillasApp')
    .filter('ingenieria', function () {
        return function (mom) {
            return mom.replace('Ingenieria', 'ING.');
        };
    });
