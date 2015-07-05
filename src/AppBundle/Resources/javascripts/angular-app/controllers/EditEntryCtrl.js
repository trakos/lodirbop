'use strict';

angular.module('trkControllers').controller('EditEntryCtrl', [
    '$scope', 'NotificationCenter', '$location', 'EntryResource', 'SteamProfileResource', 'SteamLogout',
    function ($scope, NotificationCenter, $location, EntryResource, SteamProfileResource, SteamLogout) {
        if (!STEAM_AUTH || !STEAM_AUTH.success) {
            NotificationCenter.error(Translator.trans('Session expired'));
            $location.path('/steam-login');
        }

        var errorHandlerResource = function(response) {
            var errorText = response && response.data && response.data.message ? response.data.message : Translator.trans('Unknown error');
            errorText += '; try refreshing the page if the error persists.';
            NotificationCenter.error(errorText);
        };
        var errorHandlerPromise = function(data) {
            errorHandlerResource({ data: data });
        };

        $scope.newEntry = false;
        $scope.steamProfileData = null;
        $scope.entry = EntryResource.get({id: STEAM_AUTH.steamId}, function() {
            $scope.steamProfileData = $scope.entry.steamProfileData;
        }, function(response) {
            if(response.status !== 404) {
                errorHandlerResource(response);
                $scope.cancel();
                return;
            }
            $scope.entry = new EntryResource();
            $scope.entry.steamId = STEAM_AUTH.steamId;
            $scope.entry.$resolved = true;
            angular.forEach(['games', 'mercs', 'communities'], function(value) {
                $scope.entry[value] = angular.copy(DATABASE_DICTIONARIES[value]);
            });
            $scope.steamProfileData = SteamProfileResource.get({}, angular.noop, function() {
                NotificationCenter.error(Translator.trans('Session expired'));
                $scope.cancel();
            });
            $scope.newEntry = true;
        });
        $scope.cancel = function() {
            $scope.entry.$resolved = false;
            SteamLogout.perform().success(function() {
                $location.path('/');
            }).error(errorHandlerPromise);
        };
        $scope.save = function() {
            if (!$scope.entryForm.$valid) {
                NotificationCenter.error("Form data invalid, please fix it.");
                return;
            }
            $scope.entry.$resolved = false;
            $scope.entry.$save(function() {
                NotificationCenter.success("Your entry has been saved.");
                $scope.cancel();
            }, errorHandlerResource);
        };
        $scope.remove = function() {
            $scope.entry.$resolved = false;
            $scope.entry.$remove(function() {
                NotificationCenter.success("Your entry has been removed.");
                $scope.cancel();
            }, errorHandlerResource);
        };
    }
]);