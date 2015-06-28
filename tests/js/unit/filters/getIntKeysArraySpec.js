'use strict';

describe('filter getIntKeysArray', function () {

    beforeEach(module('trkFilters'));

    it('should for arrays just return keys',
        inject(function (getIntKeysArrayFilter) {
            expect(getIntKeysArrayFilter([56, 57, 102])).toEqual([0, 1, 2]);
            expect(getIntKeysArrayFilter([-1, 'test', {bla: 5}])).toEqual([0, 1, 2]);
            expect(getIntKeysArrayFilter([])).toEqual([]);
        }));

    it('should for objects return those keys that are numeric in ascending order',
        inject(function (getIntKeysArrayFilter) {

            expect(getIntKeysArrayFilter({
                    '5'   : 'test',
                    'test': 8,
                    '3'   : 2
                }
            )).toEqual([3, 5]);

            expect(getIntKeysArrayFilter(
                function () {
                }
            )).toEqual([]);

        }));

    it('should in other cases empty return array',
        inject(function (getIntKeysArrayFilter) {

            expect(getIntKeysArrayFilter({
                    'test': 8
                }
            )).toEqual([]);

            expect(getIntKeysArrayFilter(
                function () {
                }
            )).toEqual([]);

        }));
});