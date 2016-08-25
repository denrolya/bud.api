(function() {
    'use strict';

    angular
        .module('admin')
        .constant('PlaceFormFields', [{
            key: 'name',
            type: 'input',
            templateOptions: {
                type: 'text',
                label: 'Place name',
                required: true,
                placeholder: 'Place name',
            }
        }, {
            key: 'shortDescription',
            type: 'textarea',
            templateOptions: {
                label: 'shortDescription',
                required: true
            },
        }, {
            key: 'rating',
            type: 'input',
            templateOptions: {
                type: 'number',
                label: "Place's rating",
                placeholder: 'Rating from 1 to 5',
                required: true,
                min: 1, max: 5
            },
        }, {
            key: 'priceRange',
            type: 'input',
            templateOptions: {
                type: 'number',
                label: "Price range",
                placeholder: 'Price range from 1 to 5',
                required: true,
                min: 1, max: 5
            },
        }, {
            key: 'category',
            type: 'typeahead',
            templateOptions: {
                label: 'Category Name',
                placeholder: 'Start typing category',
                options: [],
                required: true
            },
            controller: /*@ngInject*/ function($scope, Category) {
                Category.get(function(response) {
                    $scope.to.options = $scope.to.options.concat(response.categories);
                })
            },
            validators: {
                isInList: {
                    expression: function(viewValue, modelValue, scope) {
                        var value = modelValue || viewValue;
                        return (scope.to.options.length > 0) ? (scope.to.options.indexOf(value) !== -1) : true;
                    },
                    message: '$viewValue + " is not in the list'
                }
            }
        }, {
            key: 'descriptionBlock1',
            type: 'wysiwyg',
            className: 'col-md-6',
            templateOptions: {
                label: 'Description Block 1',
                required: true,
                config: {
                    styleTags: ['p', 'pre', 'blockquote', 'h4', 'h5', 'h6'],
                    height: 150,
                    disableDragAndDrop: true,
                    toolbar: [
                        ['style', ['style']],
                        ['edit',['undo','redo']],
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['alignment', ['ul', 'ol']],
                        ['table', ['table']],
                        ['insert', ['link', 'hr']],
                        ['view', ['fullscreen', 'codeview']]
                    ]
                }
            },
        }, {
            key: 'descriptionBlock2',
            type: 'wysiwyg',
            className: 'col-md-6',
            templateOptions: {
                label: 'Description Block 2',
                required: true,
                config: {
                    styleTags: ['p', 'pre', 'blockquote', 'h4', 'h5', 'h6'],
                    height: 150,
                    disableDragAndDrop: true,
                    toolbar: [
                        ['style', ['style']],
                        ['edit',['undo','redo']],
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['alignment', ['ul', 'ol']],
                        ['table', ['table']],
                        ['insert', ['link', 'hr']],
                        ['view', ['fullscreen', 'codeview']]
                    ]
                }
            },
        }, {
            key: 'address',
            type: 'input',
            templateOptions: {
                type: 'string',
                label: 'Place address',
                required: false,
                placeholder: 'Address'
            }
        }, {
            key: 'latitude',
            type: 'input',
            templateOptions: {
                type: 'hidden',
                required: false,
            }
        }, {
            key: 'longitude',
            type: 'input',
            templateOptions: {
                type: 'hidden',
                required: false,
            }
        }, {
            key: 'phonenumber',
            type: 'input',
            templateOptions: {
                type: 'string',
                label: 'Phonenumber',
                required: false,
                placeholder: 'Phonenumber'
            }
        }, {
            key: 'website',
            type: 'input',
            templateOptions: {
                type: 'string',
                label: 'Event WebSite',
                required: false,
                placeholder: 'Website'
            }
        }, {
            key: 'opened',
            type: 'input',
            templateOptions: {
                type: 'string',
                label: 'Opened hours',
                required: false,
                placeholder: 'Opened'
            }
        }, {
            key: 'googleID',
            type: 'input',
            templateOptions: {
                type: 'hidden',
                required: false,
            }
        }, {
            key: 'images',
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