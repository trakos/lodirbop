beforeEach(function () {
    function isNgElementHidden(element) {
        // we need to check element.getAttribute for SVG nodes
        var hidden = true;
        forEach(angular.element(element), function(element) {
            if ((' '  + (element.getAttribute('class') || '') + ' ').indexOf(' ng-hide ') === -1) {
                hidden = false;
            }
        });
        return hidden;
    }

    jasmine.addMatchers({
        toHaveFocus: function () {
            return {
                compare: function (actual) {
                    return {
                        pass: document.activeElement === actual[0]
                    };
                }
            };
        },
        toEqualAccordingToAngular: function () {
            return {
                compare: function(actual, expected) {
                    return {
                        pass: angular.equals(actual, expected)
                    };
                }
            }
        },
        toBeShown: function() {
            return {
                compare: function(actual) {
                    return {
                        pass: !isNgElementHidden(actual)
                    };
                }
            }
        },
        toBeHidden: function() {
            return {
                compare: function(actual) {
                    return {
                        pass: isNgElementHidden(actual)
                    };
                }
            }
        }
    });
});

function matchesURI(expectedBaseUrl, expectedParams) {
    var expectedURI = (new URI(expectedBaseUrl)).search(expectedParams);
    return function(actualFullUrl) {
        return expectedURI.equals(actualFullUrl);
    }
}

