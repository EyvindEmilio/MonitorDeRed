'use strict';

/**
 * @ngdoc filter
 * @name Monitor.filter:momentDate
 * @function
 * @description
 * # momentDate
 * Filter in the Monitor.
 */
angular.module('Monitor')
    .filter('ingenieria', function () {
        return function (mom) {
            return mom.replace('Ingenieria', 'ING.');
        };
    });
