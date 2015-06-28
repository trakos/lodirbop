'use strict';

describe('directive trkEnter', function () {
    var $scope,
        element,
        elementHtml = '<input id="testInput" name="testName" type="text" ibu-enter="functionSpy()">';

    beforeEach(module('trkDirectives'));
    beforeEach(inject(function ($rootScope, $compile) {
        $scope = $rootScope.$new();
        element = $compile(elementHtml)($scope);
        element = element.appendTo(document.body);
        $scope.functionSpy = jasmine.createSpy('trkEnterListener');
    }));
    afterEach(function () {
        dealoc(element);
    });

    it('should call given expression on event keydown with keycode 13 (enter)',
        function () {
            $scope.$digest();
            element.triggerHandler({type: 'keydown', which: 13});
            expect($scope.functionSpy).toHaveBeenCalled();
        });

    it('should not react to keypress with different keycodes',
        function () {
            $scope.$digest();
            element.triggerHandler({type: 'keydown', which: 9}); // 9 = shift
            expect($scope.functionSpy).not.toHaveBeenCalled();
        });
});