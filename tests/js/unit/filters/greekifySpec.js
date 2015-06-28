'use strict';

describe('filter greekify', function () {

    beforeEach(module('trkFilters'));

    it('should convert numbers between 1 to 24 to greek letter accordingly',
        inject(function (greekifyFilter) {
            expect(greekifyFilter(1)).toBe('alpha');
            expect(greekifyFilter(2)).toBe('beta');
            expect(greekifyFilter(24)).toBe('omega');
        }));

    it('should convert numbers above 24 to hyphen-seperated pair: symbol - letter',
        inject(function (greekifyFilter) {
            expect(greekifyFilter(25)).toBe('β-alpha');
            expect(greekifyFilter(26)).toBe('β-beta');
            expect(greekifyFilter(49)).toBe('γ-alpha');
            expect(greekifyFilter(50)).toBe('γ-beta');
            expect(greekifyFilter(72)).toBe('γ-omega');
            expect(greekifyFilter(576)).toBe('ω-omega');
        }));

    it('should leave other values unchanged',
        inject(function (greekifyFilter) {
            expect(greekifyFilter(0)).toBe(0);
            expect(greekifyFilter(-1)).toBe(-1);
            expect(greekifyFilter(577)).toBe(577);
            expect(greekifyFilter('string')).toBe('string');
        }));
});