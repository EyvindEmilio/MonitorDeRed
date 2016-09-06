'use strict';

/**
 * @ngdoc function
 * @name simuladorTiroApp.controller:ProfileController
 * @description
 * # PracticesController
 * Controller of the simuladorTiroApp
 */
angular.module('Monitor')
    .controller('ProfileController', function ($scope, $rootScope, $http, user, ModelService, toastr, $timeout, $uibModalInstance, Model) {
        $scope.is_new = false;
        user.status = user.status == 'Y';

        var id_user = user.id,
            account_model = new ModelService.Users(),
            user_type = new ModelService.UsersTypes();

        var is_new_user = !(id_user && true);
        $scope.is_new = is_new_user;
        $scope.user = {};
        $scope.list_user_type = [];

        (new user_type.resource()).$get().then(function (data) {
            $scope.list_user_type = data.data;
        });

        $scope.new_password = "";
        $scope.update_password = false;//collapse update password

        if (id_user == $rootScope.currentUser.id) {
            $scope.disable_active_account = true;
            $scope.disable_user_type = true;
        } else {
            $scope.disable_active_account = false;
            $scope.disable_user_type = false;
        }
        if (!is_new_user) {
            (new account_model.resource()).$get({id: id_user, is_complete_serializer: 1}).then(function (data) {
                data.status = data.status == 'Y';
                $scope._user = data;
                $scope.user = angular.copy(data);
            });
        }

        if (is_new_user) {
            $scope.user = {};
            angular.forEach(Model.initValues, function (value, key) {
                if (typeof (value) === 'number') {
                    $scope.user[key] = {};
                    $scope.user[key].id = value;
                } else if (typeof (value) === 'object') {
                    $scope.user[key] = {};
                    $scope.user[key].id = value.id;
                }
            });
            $scope.update_password = true;
            $scope.user.user_type = {};
            $scope.user.user_type.id = 3;//jefe
            $scope.user.status = false;
        }

        $scope.text_save = "Guardar";
        $scope.save = function () {
            var formData = new FormData(),
                data = $scope.user;

            formData.append("first_name", data.first_name);
            formData.append("last_name", data.last_name);
            formData.append("email", data.email);
            formData.append("user_type", data.user_type.id);
            formData.append("status", data.status ? 'Y' : 'N');

            if (typeof (data.image) !== "string" && data.image != null)
                formData.append("image", data.image);

            if ($scope.update_password) {
                formData.append("password", $scope.new_password);
            }

            $scope.text_save = "Guardando";
            function success() {
                if (is_new_user) {
                    toastr.success("Registro de usuario exitoso");
                } else {
                    toastr.success("Información de usuario actualizada");
                }
                $timeout(function () {
                    $uibModalInstance.close();
                }, 500);
                $scope.text_save = "Guardardado";
            }

            function error() {
                if (is_new_user) {
                    toastr.warning("No se pudo crear cuenta de usuario");
                } else {

                    toastr.warning("No se pudo modificar");
                }
                $scope.text_save = "Guardar";
            }

            if (is_new_user) {
                (new ModelService.Users()).resource.create(formData, success, error);
            } else {
                formData.append('id', id_user);
                (new ModelService.Users()).resource.patch({}, formData, success, error);
            }
        };
        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };
    });