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
