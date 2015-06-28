'use strict';

describe('directive trkGo', function () {
    var $scope,
        element,
        elementHtml = '<p ibu-go="/givenTestValue"></p>',
        $location;

    beforeEach(module('trkDirectives'));
    beforeEach(inject(function ($rootScope, $compile, _$location_) {
        $scope = $rootScope.$new();
        $location = _$location_;
        element = $compile(elementHtml)($scope);
        element = element.appendTo(document.body);
    }));
    afterEach(function () {
        dealoc(element);
    });

    it('should change location to given value on click on the element it is attached to',
        function () {
            $scope.$digest();
            element.triggerHandler('click');
            $scope.$digest();
            expect($location.path()).toBe('/givenTestValue');
        });
});