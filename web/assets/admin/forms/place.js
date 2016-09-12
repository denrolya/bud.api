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
            key: 'categories',
            type: 'tags',
            templateOptions: {
                label: 'Category Name',
                placeholder: 'Start typing category',
                options: [],
                keyProperty: 'name',
                displayProperty: 'name',
                required: true
            },
            controller: /*@ngInject*/ function($scope, Category) {
                $scope.to.getOptions = function (query) {
                    return Category.get(function(response) {
                        return response.categories;
                    })
                }
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
            fieldGroup: [{
                className: 'row no-gutter',
                fieldGroup: [{
                    key: 'Mon.from',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Mon from',
                        required: true,
                        type: 'text',
                        placeholder: 'From'
                    }
                }, {
                    key: 'Mon.to',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Mon to',
                        type: 'text',
                        required: true,
                        placeholder: 'To'
                    }
                }]
            }, {
                className: 'row no-gutter',
                fieldGroup: [{
                    key: 'Tue.from',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Tue from',
                        required: true,
                        type: 'text',
                        placeholder: 'From'
                    }
                }, {
                    key: 'Tue.to',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Tue to',
                        type: 'text',
                        required: true,
                        placeholder: 'To'
                    }
                }]
            }, {
                className: 'row no-gutter',
                fieldGroup: [{
                    key: 'Wed.from',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Wed from',
                        required: true,
                        type: 'text',
                        placeholder: 'From'
                    }
                }, {
                    key: 'Wed.to',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Wed to',
                        type: 'text',
                        required: true,
                        placeholder: 'To'
                    }
                }]
            }, {
                className: 'row no-gutter',
                fieldGroup: [{
                    key: 'Thu.from',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Thu from',
                        required: true,
                        type: 'text',
                        placeholder: 'From'
                    }
                }, {
                    key: 'Thu.to',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Thu to',
                        type: 'text',
                        required: true,
                        placeholder: 'To'
                    }
                }]
            }, {
                className: 'row no-gutter',
                fieldGroup: [{
                    key: 'Fri.from',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Fri from',
                        required: true,
                        type: 'text',
                        placeholder: 'From'
                    }
                }, {
                    key: 'Fri.to',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Fri to',
                        type: 'text',
                        required: true,
                        placeholder: 'To'
                    }
                }]
            }, {
                className: 'row no-gutter',
                fieldGroup: [{
                    key: 'Sat.from',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Sat from',
                        required: true,
                        type: 'text',
                        placeholder: 'From'
                    }
                }, {
                    key: 'Sat.to',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Sat to',
                        type: 'text',
                        required: true,
                        placeholder: 'To'
                    }
                }]
            }, {
                className: 'row no-gutter',
                fieldGroup: [{
                    key: 'Sun.from',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Sun from',
                        required: true,
                        type: 'text',
                        placeholder: 'From'
                    }
                }, {
                    key: 'Sun.to',
                    type: 'input',
                    className: 'col-xs-6',
                    templateOptions: {
                        label: 'Sun to',
                        type: 'text',
                        required: true,
                        placeholder: 'To'
                    }
                }]
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