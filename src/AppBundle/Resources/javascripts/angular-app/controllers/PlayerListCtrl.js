'use strict';

angular.module('trkControllers').controller('PlayerListCtrl', [
    '$scope', 'NotificationCenter', '$location', 'EntryResource',
    function ($scope, NotificationCenter, $location, EntryResource) {
        $scope.entries = EntryResource.query(angular.noop, function() {
            NotificationCenter.error(Translator.trans("Couldn't load players. Check your connection and refresh this page."));
        });
    }
]);