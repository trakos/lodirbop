'use strict';

angular.module('trkServices').factory('NotificationCenter', [
    function () {
        return {
            error: function (message) {
                $.notify({
                    message: message
                }, {type: 'danger'});
            },
            success: function (message) {
                $.notify({
                    message: message
                }, {type: 'success'});
            }
        };
    }
]);