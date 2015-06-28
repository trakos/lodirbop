'use strict';

angular.module('trkServices').factory('SteamLogout', [
    '$http',
    function ($http) {
        return {
            perform: function () {
                STEAM_AUTH = null;
                return $http.get("/api/steam-logout/" + GLOBAL_CSRF_TOKEN);
            }
        };
    }
]);