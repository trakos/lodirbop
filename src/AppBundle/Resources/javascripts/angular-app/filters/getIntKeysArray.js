'use strict';

angular.module('trkFilters').filter('getIntKeysArray', [
    function () {
        // angular by default orders objects alphabetically, which we want to avoid
        // and to do that we have to convert it to array

        return function (input) {
            var array = [];
            angular.forEach(input, function (value, key) {
                if (!isNaN(parseInt(key))) {
                    array.push(parseInt(key));
                }
            });
            array.sort(function (a, b) {
                return a - b;
            });
            return array;
        };
    }
]);