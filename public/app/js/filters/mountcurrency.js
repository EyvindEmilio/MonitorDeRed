'use strict';

/**
 * @ngdoc filter
 * @name Monitor.filter:mountCurrency
 * @function
 * @description
 * # mountCurrency
 * Filter in the Monitor.
 */
angular.module('Monitor')
    .filter('mountCurrency', function () {
        return function (mount) {
            mount = parseFloat(mount);
            mount = mount.toFixed(2) + ' Bs/h';
            return mount;
        };
    });
