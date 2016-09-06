(function() {
    'use strict';

    angular
        .module('admin')
        .constant('PlaceFormFields', [{
            className: 'row no-gutter',
            fieldGroup: [{
                key: 'latitude',
                type: 'input',
                className: 'col-xs-6',
                templateOptions: {
                    label: 'Latitude',
                    type: 'text',
                    required: true,
                    disabled: true
                }
            }, {
                key: 'longitude',
                type: 'input',
                className: 'col-xs-6',
                templateOptions: {
                    label: 'Longitude',
                    type: 'text',
                    required: true,
                    disabled: true
                }
            }]
        }, {
            key: 'name',
            type: 'input',
            templateOptions: {
                type: 'text',
                label: 'Place name',
                required: true,
                placeholder: 'Place name',
            }
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
            }
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
            key: 'shortDescription',
            type: 'textarea',
            templateOptions: {
                label: 'shortDescription',
                required: true
            },
        }, {
            className: 'row no-gutter',
            fieldGroup: [{
                key: 'rating',
                type: 'input',
                className: 'col-xs-6',
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
                className: 'col-xs-6',
                templateOptions: {
                    type: 'number',
                    label: "Price range",
                    placeholder: 'Price range from 1 to 5',
                    required: true,
                    min: 1, max: 5
                },
            }]
        }, {
            className: 'row no-gutter',
            fieldGroup: [{
                key: 'descriptionBlock1',
                type: 'wysiwyg',
                className: 'col-xs-6',
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
                className: 'col-xs-6',
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
            }]
        }, {
            className: 'row no-gutter',
            fieldGroup: [{
                key: 'phonenumber',
                type: 'input',
                className: 'col-xs-6',
                templateOptions: {
                    type: 'string',
                    label: 'Phonenumber',
                    required: false,
                    placeholder: 'Phonenumber'
                }
            }, {
                key: 'website',
                type: 'input',
                className: 'col-xs-6',
                templateOptions: {
                    type: 'string',
                    label: "Place's WebSite",
                    required: false,
                    placeholder: 'Website'
                }
            }]
        }, {
            key: 'opened',
            templateOptions: {
                label: 'Opened hours',
                required: false,
                placeholder: 'Opened'
            },
            fieldGroup: [{
                key: 'Mon',
                type: 'input',
                templateOptions: {
                    required: true,
                    type: 'text',
                    label: 'Monday'
                }
            }, {
                key: 'Tue',
                type: 'input',
                templateOptions: {
                    required: true,
                    type: 'text',
                    label: 'Tuesday'
                }
            }, {
                key: 'Wed',
                type: 'input',
                templateOptions: {
                    required: true,
                    type: 'text',
                    label: 'Wednesday'
                }
            }, {
                key: 'Thu',
                type: 'input',
                templateOptions: {
                    required: true,
                    type: 'text',
                    label: 'Thursday'
                }
            }, {
                key: 'Fri',
                type: 'input',
                templateOptions: {
                    required: true,
                    type: 'text',
                    label: 'Friday'
                }
            }, {
                key: 'Sat',
                type: 'input',
                templateOptions: {
                    required: true,
                    type: 'text',
                    label: 'Saturday'
                }
            }, {
                key: 'Sun',
                type: 'input',
                templateOptions: {
                    required: true,
                    type: 'text',
                    label: 'Sunday'
                }
            }]
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