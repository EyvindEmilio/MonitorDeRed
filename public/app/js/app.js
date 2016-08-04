'use strict';

/**
 * @ngdoc overview
 * @name Monitor
 * @description
 * # planillasApp
 *
 * Main module of the application.
 */
angular
    .module('Monitor', [
        'ngResource',
        'ngAnimate',
        'ngSanitize',
        'ui.bootstrap',
        'angularMoment',
        'toastr',
        'highcharts-ng'
    ])
    .config(function ($httpProvider) {
        $httpProvider.defaults.useXDomain = true;
        delete $httpProvider.defaults.headers.common['X-Requested-With'];
        $httpProvider.interceptors.push('InterceptorService');
    })
    .constant('angularMomentConfig', {
        timezone: 'America/La_Paz' // e.g. 'Europe/London'
    });

angular.module('Monitor').controller('ModalConfirm', function ($scope, $uibModalInstance, message, title) {
    $scope.mensaje = message;
    $scope.title = title;
    $scope.ok = function () {
        $uibModalInstance.close(true);
    };
    $scope.cancel = function () {
        $uibModalInstance.dismiss(false);
    };
});
