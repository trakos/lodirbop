'use strict';

// just a basic mock for translator from symfony's BazingaJsTranslatorBundle
var Translator = {
    trans      : function (message, params, domain) {
        return '<translated>' + message + '</translated><params>' + JSON.stringify(params) + '</params>';
    },
    transchoice: function (message, params, domain) {
        return '<translated>' + message + '</translated><params>' + JSON.stringify(params) + '</params>';
    }
};