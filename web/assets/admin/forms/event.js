(function() {
    'use strict';

    angular
        .module('admin')
        .constant('EventFormFields', [{
            key: 'title',
            type: 'input',
            templateOptions: {
                type: 'text',
                label: 'Event title',
                required: true,
                placeholder: 'Event title',
            }
        }, {
            key: 'shortDescription',
            type: 'textarea',
            templateOptions: {
                label: 'shortDescription',
                required: true
            },
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
            key: 'location',
            type: 'input',
            templateOptions: {
                type: 'string',
                label: 'Event Location',
                required: false,
                placeholder: 'location'
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