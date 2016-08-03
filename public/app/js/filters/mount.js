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
    .filter('mountC', function () {
        return function (mount) {
            if (mount) {
                mount = parseFloat(mount);
                mount = mount.toFixed(2);
                if (isNaN(mount)) {
                    mount = '-';
                }
            } else {
                mount = '-';
            }
            return mount;
        };
    });
