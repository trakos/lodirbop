'use strict';

angular.module('trkControllers').controller('PlayerListCtrl', [
    '$scope', 'NotificationCenter', '$location', 'EntryResource',
    function ($scope, NotificationCenter, $location, EntryResource) {
        $scope.totalItems = 0;
        $scope.itemsPerPage = 25;
        $scope.currentPage = 1;
        $scope.totalItems = 1000;
        $scope.filters = {};
        $scope.dictionaries = DATABASE_DICTIONARIES;

        $scope.filterChange = function() {
            $scope.currentPage = 1;
            $scope.changePage();
        };
        $scope.changePage = function () {
            $scope.entries = EntryResource.query(
                {
                    limit: $scope.itemsPerPage,
                    offset: ($scope.currentPage - 1) * $scope.itemsPerPage,
                    filters: $scope.filters
                },
                function (data, responseHeaders) {
                    $scope.totalItems = responseHeaders('X-COUNT');
                },
                function () {
                    NotificationCenter.error(Translator.trans("Couldn't load players. Check your connection and refresh this page."));
                }
            );
        };

        $scope.changePage();
    }
]);