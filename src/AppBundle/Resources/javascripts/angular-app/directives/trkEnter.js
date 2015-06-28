'use strict';

// bind to enter press
angular.module('trkDirectives').directive('trkEnter', [
    function () {

        var ENTER_KEY_CODE = 13;

        return function (scope, element, attrs) {

            element.bind("keydown keypress", function (event) {
                if (event.which === ENTER_KEY_CODE) {
                    scope.$apply(function () {
                        scope.$eval(attrs.trkEnter);
                    });

                    event.preventDefault();
                }
            });
        };
    }
]);