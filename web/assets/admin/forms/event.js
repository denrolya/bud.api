(function() {
    'use strict';

    angular
        .module('admin')
        .constant('EventFormFields', [{
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
            key: 'title',
            type: 'input',
            templateOptions: {
                type: 'text',
                label: 'Event title',
                required: true,
                placeholder: 'Event title',
            }
        }, {
            key: 'address',
            type: 'input',
            templateOptions: {
                type: 'string',
                label: 'Event address',
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
            key: 'dateFrom',
            type: 'datetime',
            className: 'col-md-6',
            templateOptions: {
                label: 'Date Starting',
                required: true,
                placeholder: 'Date from'
            }
        }, {
            key: 'dateTo',
            type: 'datetime',
            className: 'col-md-6',
            templateOptions: {
                label: 'Date Ending',
                required: false,
                placeholder: 'Date to'
            }
        }, {
            key: 'rating',
            type: 'input',
            templateOptions: {
                type: 'number',
                label: "Event's rating",
                placeholder: 'Rating from 1 to 5',
                required: true,
                min: 1, max: 5
            },
        }, {
            key: 'fullDescription',
            type: 'wysiwyg',
            templateOptions: {
                label: 'Full Description',
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