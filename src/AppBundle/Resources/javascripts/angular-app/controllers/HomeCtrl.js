'use strict';

angular.module('trkControllers').controller('HomeCtrl', [
    '$scope', 'NotificationCenter', '$location',
    function ($scope, NotificationCenter, $location) {
        if (STEAM_AUTH && !STEAM_AUTH.success && STEAM_AUTH.errorMessage) {
            NotificationCenter.error(
                Translator.trans(
                    'Steam authorization failed: {{ error_message }}.',
                    {
                        '{{ error_message }}': STEAM_AUTH.errorMessage
                    }
                )

            );
        } else if (STEAM_AUTH && STEAM_AUTH.success) {
            $location.path('/edit-entry');
        }
    }
]);