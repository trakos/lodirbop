'use strict';

angular.module('trkServices').factory('SteamProfileResource', [
    '$resource',
    function ($resource) {

        // GLOBAL_CSRF_TOKEN defined in layout.html.twig by generateJavascriptConstants
        return $resource('api/steam-profile/:csrfToken', { csrfToken: GLOBAL_CSRF_TOKEN }, {

        });
    }
]);