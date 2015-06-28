'use strict';

// this directive binds variable given in attribute to the state of being focused
angular.module('trkDirectives').directive('trkBindFocus', [
    '$timeout', '$parse',
    function ($timeout, $parse) {
        return {
            scope: {
                focusValue: '=trkBindFocus'
            },
            link: function (scope, element, attrs) {
                if (typeof scope.focusValue == 'undefined') {
                    scope.focusValue = false;
                }
                element.on('blur', function (event) {
                    if (scope.focusValue) {
                        scope.$applyAsync(function () {
                            scope.focusValue = false;
                        });
                    }
                });
                element.on('focus', function (event) {
                    if (!scope.focusValue) {
                        scope.$applyAsync(function () {
                            scope.focusValue = true;
                        });
                    }
                });
                var onValueChange = function (value) {
                    if (value && element[0] != document.activeElement) {
                        $timeout(function () {
                            element[0].focus();
                        });
                    } else if (!value && element[0] == document.activeElement) {
                        $timeout(function () {
                            element[0].blur();
                        });
                    }
                };
                scope.$watch('focusValue', onValueChange);
                // apply initial value
                onValueChange(scope.focusValue);
            }
        };
    }
]);