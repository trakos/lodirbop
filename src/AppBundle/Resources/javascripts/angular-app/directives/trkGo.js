'use strict';

// go to angular route on click
angular.module('trkDirectives').directive('trkGo', [
    '$location',
    function ($location) {
        return {
            link: function ($scope, element, attributes) {
                element.bind("click", function () {
                    $location.path(attributes.trkGo);
                    $scope.$apply();
                });
            }
        }
    }
]);