'use strict';

// based on http://stackoverflow.com/questions/15219717/to-test-a-custom-validation-angular-directive
describe('directive trkInteger', function () {
    var $scope,
        element,
        elementHtml = '<form name="testForm">'
            + '<input id="testInput" name="testName" type="text" ibu-integer ng-model="testVariable">'
            + '</form>';

    beforeEach(module('trkDirectives'));
    beforeEach(inject(function ($rootScope, $compile) {
        $scope = $rootScope.$new();
        element = $compile(elementHtml)($scope);
        element = element.appendTo(document.body);
    }));
    afterEach(function () {
        dealoc(element);
    });

    it('should validate any integer', function () {
        $scope.$digest();
        $scope.testForm.testName.$setViewValue('3');
        expect($scope.testVariable).toEqual('3');
        expect($scope.testForm.testName.$valid).toBe(true);
    });
    it("shouldn't validate string", function () {
        $scope.$digest();
        $scope.testForm.testName.$setViewValue('a');
        expect($scope.testVariable).toBeUndefined();
        expect($scope.testForm.testName.$valid).toBe(false);
    });
    it("shouldn't validate float", function () {
        $scope.$digest();
        $scope.testForm.testName.$setViewValue('2.5');
        expect($scope.testVariable).toBeUndefined();
        expect($scope.testForm.testName.$valid).toBe(false);
    });
    it('should validate when empty', function () {
        $scope.$digest();
        $scope.testForm.testName.$setViewValue('');
        expect($scope.testVariable).toBeFalsy();
        expect($scope.testForm.testName.$valid).toBe(true);
    });
});