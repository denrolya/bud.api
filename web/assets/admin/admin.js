(function() {
    'use strict';

    angular
        .module('admin', ['ui.router', 'ngResource', 'oc.lazyLoad', 'ui.bootstrap', 'formly', 'formlyBootstrap', 'summernote', 'ui.bootstrap.datetimepicker', 'dropzone'])
        .run(function(formlyConfig, formlyValidationMessages) {
            formlyConfig.setType([{
                name: 'typeahead',
                template: '<input type="text" ng-model="model[options.key]" uib-typeahead="item as item.name for item in to.options | filter:$viewValue" class="form-control" autocomplete="off" typeahead-editable="false">',
                wrapper: ['bootstrapLabel', 'bootstrapHasError']
            },{
                name: 'wysiwyg',
                extends: 'textarea',
                template: '<div summernote ng-model="model[options.key]" ng-required="options.templateOptions.required" config="config"></div>'
            }, {
                name: 'datetime',
                extends: 'input',
                template: '<div class="dropdown">'
                    + '<a class="dropdown-toggle" id="{{ options.key }}" role="button" data-toggle="dropdown">'
                    + '<div class="input-group">'
                    + '<input type="text" class="form-control" ng-model="model[options.key]">'
                    + '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>'
                    + '</div>'
                    + '</a>'
                    + '<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">'
                    + '<datetimepicker data-ng-model="model[options.key]" data-datetimepicker-config="{ dropdownSelector: options.key }"/>'
                    + '</ul>'
                    + '</div>'
            }]);
        });
})();