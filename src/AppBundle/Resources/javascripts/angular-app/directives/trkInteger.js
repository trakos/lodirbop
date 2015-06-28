'use strict';

// validate if integer - based on https://docs.angularjs.org/guide/forms (paragraph Custom Validation)
angular.module('trkDirectives').directive('trkInteger', [
    function () {
        var INTEGER_REGEXP = /^\-?\d+$/;
        return {
            require: 'ngModel',
            link: function (scope, elm, attrs, ctrl) {
                ctrl.$validators.integer = function (modelValue, viewValue) {

                    // consider empty values to be valid
                    // other than that, only strictly integers (no dots and commas)
                    return ctrl.$isEmpty(modelValue) || INTEGER_REGEXP.test(viewValue);
                };
            }
        };
    }
]);