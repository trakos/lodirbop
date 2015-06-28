'use strict';

angular.module('trkFilters').filter('greekify', [
    function () {

        var greekLetterNames = [
            'alpha',
            'beta',
            'gamma',
            'delta',
            'epsilon',
            'zeta',
            'eta',
            'theta',
            'iota',
            'kappa',
            'lambda',
            'mu',
            'nu',
            'xi',
            'omicron',
            'pi',
            'rho',
            'sigma',
            'tau',
            'upsilon',
            'phi',
            'chi',
            'psi',
            'omega'
        ];

        var greekSymbols = ['α', 'β', 'γ', 'δ', 'ε', 'ζ', 'η ', 'θ', 'ι', 'κ',
            'λ', 'μ', 'ν', 'ξ', 'ο', 'π', 'ρ', 'σ', 'τ', 'υ', 'φ', 'χ', 'ψ', 'ω'];

        // if input is smaller than 24, returns just the letter name
        // else if smaller than 24^2 return letter name prepended with letter symbol (starting with β - alpha)
        // kind of like excel column names, only the first letter is as a symbol, second is a letter name
        // else returns input unchanged

        return function (input) {
            var number = parseInt(input);
            if (!number || number < 0) {
                return input;
            } else if (number <= greekLetterNames.length) {
                return greekLetterNames[number - 1];
            } else if (number <= greekLetterNames.length * greekLetterNames.length) {

                return greekSymbols[Math.floor((number - 1) / greekLetterNames.length)]
                    + '-' + greekLetterNames[(number - 1) % greekLetterNames.length];
            } else {
                return input;
            }
        };
    }
]);