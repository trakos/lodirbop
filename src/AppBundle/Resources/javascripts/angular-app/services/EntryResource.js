'use strict';

angular.module('trkServices').factory('EntryResource', [
    '$resource',
    function ($resource) {

        var transformResponse = function(object) {

            var dictionaries = {
                games      : angular.copy(DATABASE_DICTIONARIES.games),
                mercs      : angular.copy(DATABASE_DICTIONARIES.mercs),
                communities: angular.copy(DATABASE_DICTIONARIES.communities)
            };

            angular.forEach(dictionaries, function(fullList, key) {
                angular.forEach(fullList, function(element) {
                    element.selected = false;
                });

                angular.forEach(object[key], function(element) {
                    angular.forEach(fullList, function(elementWithSelected) {
                        if (elementWithSelected.id == element.id) {
                            elementWithSelected.selected = true;
                        }
                    });
                });

                object[key] = fullList;
            });

            return object;
        };

        var transformRequest = function(object) {
            object = angular.copy(object);
            if (object.steamId) {
                object.id = object.steamId;
                delete object.steamId;
            }
            angular.forEach(['games', 'mercs', 'communities'], function(value) {
                object[value] = object[value].filter(function (element, index, array) {
                    return element.selected;
                });
                angular.forEach(object[value], function(element, key){
                    object[value][key] = element.id;
                });
            });
            delete object.steamProfileData;
            return object;
        };

        // GLOBAL_CSRF_TOKEN defined in layout.html.twig by generateJavascriptConstants
        return $resource('api/entry/:csrfToken/:id', { csrfToken: GLOBAL_CSRF_TOKEN, id: '@id' }, {
            query: {
                method: 'GET',
                params: {
                    id: 'all'
                },
                isArray: true
            },
            get: {
                transformResponse: function(data, header) {
                    return transformResponse(JSON.parse(data));
                }
            },
            save: {
                method: 'POST',
                transformRequest: function(data) {
                    console.log('requestdata', data);
                    return JSON.stringify(transformRequest(data));
                }
            }
        });
    }
]);