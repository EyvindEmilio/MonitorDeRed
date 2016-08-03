'use strict';
/**
 * @ngdoc service
 * @name Monitor.JsonService
 * @description
 * # JsonService
 * Service in the emiApp.
 */
angular.module('Monitor')
    .service('FormDataService', function () {
        function objectToFormData(object) {
            var formData = new FormData();

            angular.forEach(object, function (value, key) {
                var keySubArray;
                /**
                 Verify value is type Date
                 **/
                if (angular.isDate(value)) {
                    //value = value.getFullYear() + '-' + (value.getMonth() + 1) + '-' + value.getDate();
                    value = value.toJSON();
                }
                if (angular.isArray(value)) {
                    for (keySubArray = 0; keySubArray < value.length; keySubArray++) {
                        formData.append(key, value[keySubArray]);
                    }
                } else {
                    formData.append(key, value);
                }
            });

            return formData;
        }


        return {
            objectToFormData: objectToFormData
        };
    });
