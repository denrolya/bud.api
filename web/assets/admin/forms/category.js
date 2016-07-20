(function() {
    'use strict';

    angular
        .module('admin')
        .constant('CategoryFormFields', [{
            key: 'name',
            type: 'input',
            templateOptions: {
                type: 'text',
                label: 'Name',
                required: true,
                placeholder: 'Category name',
            }
        }, {
            key: 'coverImage',
            type: 'input',
            templateOptions: {
                type: 'hidden',
                required: true
            },
            validators: {
                newFiles: {
                    expression: function(viewValue, modelValue) {
                        var value = modelValue || viewValue, isValid = true;

                        return true;
                    },
                    message: "Error: Invalid file!"
                }
            }
        }]);
})();