'use strict';

/**
 * @ngdoc filter
 * @name planillasEmiApp.filter:mountCurrency
 * @function
 * @description
 * # mountCurrency
 * Filter in the planillasEmiApp.
 */
angular.module('planillasApp')
    .filter('mountCurrency', function () {
        return function (mount) {
            mount = parseFloat(mount);
            mount = mount.toFixed(2) + ' Bs/h';
            return mount;
        };
    });
