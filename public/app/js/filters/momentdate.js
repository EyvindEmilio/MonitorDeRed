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
    .filter('momentDate', function () {
        return function (mom, opt1) {
            var m = moment(mom);
            if (opt1) {
                m = m.format('LLL');
            } else {
                m = m.format('LL');
            }
            return m;
        };
    });
