'use strict';

describe('directive trkBindFocus', function () {
    var $scope,
        element,
        $timeout,
        elementHtml = '<input id="testInput" name="testName" type="text" ibu-bind-focus="testVariable">',
        flushTimeouts = function () {
            try {
                $timeout.verifyNoPendingTasks();
            } catch (aException) {
                $timeout.flush();
            }
        },
        apply = function () {
            $scope.$digest();
            flushTimeouts();
        };

    beforeEach(module('trkDirectives'));
    beforeEach(inject(function ($rootScope, $compile, _$timeout_) {
        $timeout = _$timeout_;
        $scope = $rootScope.$new();
        element = $compile(elementHtml)($scope);
        // Append to body because otherwise it can't be focused
        element = element.appendTo(document.body);
    }));
    afterEach(function () {
        dealoc(element);
    });

    it('should assign false to given variable when it was not defined',
        function () {
            apply();
            expect($scope.testVariable).toBe(false);
        });

    it("should focus and keep given variable value's if initially it's true, and it should lose focus when value is changed to false",
        function () {
            $scope.testVariable = true;
            apply();
            expect($scope.testVariable).toBe(true);
            expect(element).toHaveFocus();
            $scope.testVariable = false;
            apply();
            expect($scope.testVariable).toBe(false);
            expect(element).not.toHaveFocus();
        });

    it("should lose focus when given variable's value is changed to false",
        function () {
            $scope.testVariable = true;
            apply();
            expect($scope.testVariable).toBe(true);
            expect(element).toHaveFocus();
        });

    it("should change given variable's value appropriately with focusing and blurring",
        function () {
            apply();
            expect($scope.testVariable).toBe(false);
            element.triggerHandler('focus');
            apply();
            expect($scope.testVariable).toBe(true);
            expect(element).toHaveFocus();
            element.triggerHandler('blur');
            apply();
            expect($scope.testVariable).toBe(false);
            expect(element).not.toHaveFocus();
        });

    it("should not change given variable's value when focused and already true",
        function () {
            $scope.testVariable = true;
            apply();
            expect($scope.testVariable).toBe(true);
            element.triggerHandler('focus');
            apply();
            expect($scope.testVariable).toBe(true);
            expect(element).toHaveFocus();
        });

    it("should not change given variable's value when blurred and already false",
        function () {
            $scope.testVariable = false;
            apply();
            expect($scope.testVariable).toBe(false);
            element.triggerHandler('blur');
            apply();
            expect($scope.testVariable).toBe(false);
            expect(element).not.toHaveFocus();
        });
});