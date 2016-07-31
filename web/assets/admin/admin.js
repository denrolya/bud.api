(function() {
    'use strict';

    angular
        .module('admin', ['ui.router', 'ngResource', 'ui.bootstrap', 'formly', 'formlyBootstrap', 'summernote', 'dropzone'])
        .run(function(formlyConfig, formlyValidationMessages) {
            formlyConfig.setType([{
                name: 'wysiwyg',
                extends: 'textarea',
                template: '<div summernote ng-model="model[options.key]" ng-required="options.templateOptions.required" config="config"></div>'
            }]);
        });
})();