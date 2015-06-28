module.exports = function (config) {
    config.set({
        basePath        : '../../',
        files           : [
            'vendor/bower/jquery/dist/jquery.min.js',
            'vendor/bower/angular/angular.js',
            'vendor/bower/uri.js/src/URI.min.js',
            // I am using helper from that file (dealoc), but it should help to include the whole file
            'vendor/bower/angular-latest/test/helpers/testabilityPatch.js',
            'vendor/bower/angular-route/angular-route.js',
            'vendor/bower/angular-resource/angular-resource.js',
            'vendor/bower/angular-animate/angular-animate.js',
            'vendor/bower/angular-mocks/angular-mocks.js',
            // small matchers additions (toHaveBeenCalledOnce etc.)
            'vendor/bower/jasmine-n-matchers/jasmine-n-matchers.js',
            'tests/js/helpers/**/*.js',
            'src/*Bundle/Resources/javascripts/angular-app/initModules.js',
            'src/*Bundle/Resources/javascripts/angular-app/**/*.js',
            'tests/js/unit/**/*.js'
        ],
        preprocessors   : {
            // source files included in coverage
            'src/*Bundle/Resources/javascripts/angular-app/**/*.js': ['coverage']
        },
        reporters       : ['progress', 'coverage'],
        autoWatch       : true,
        frameworks      : ['jasmine'],
        browsers        : ['Chrome', 'Firefox'],
        plugins         : [
            'karma-chrome-launcher',
            'karma-firefox-launcher',
            'karma-jasmine',
            'karma-coverage'
        ],
        junitReporter   : {
            outputFile: 'tests/js/unit.xml',
            suite     : 'unit'
        },
        coverageReporter: {
            type: 'html',
            dir : 'web/reports/js_coverage/'
        }
    });
};