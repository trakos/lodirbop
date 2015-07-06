'use strict';

angular.module('trkControllers', ['ui.bootstrap']);
angular.module('trkDirectives', []);
angular.module('trkFilters', []);
angular.module('trkServices', ['ngResource']);
angular.module('trkApp', [
    'ui.bootstrap.showErrors',
    'ui.bootstrap',
    'ngRoute',
    'ngAnimate',
    'trkControllers',
    'trkFilters',
    'trkServices',
    'trkDirectives'
]).config([
    '$routeProvider',
    function ($routeProvider) {
        $routeProvider
            .when('/', {
                templateUrl: Translator.locale + '/templates/main',
                controller: 'HomeCtrl'
            })
            .when('/player-list', {
                templateUrl: Translator.locale + '/templates/player-list',
                controller: 'PlayerListCtrl'
            })
            .when('/steam-login', {
                templateUrl: Translator.locale + '/templates/steam-login',
                controller: 'SteamLoginCtrl'
            })
            .when('/edit-entry', {
                templateUrl: Translator.locale + '/templates/edit-entry',
                controller: 'EditEntryCtrl'
            })
            .otherwise({
                redirectTo: '/'
            });
    }
]);