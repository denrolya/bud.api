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
            key: 'descriptionBlock1',
            type: 'wysiwyg',
            templateOptions: {
                label: 'Description Block 1',
                required: true
            },
            controller: /*@ngInject*/ function($scope) {
                $scope.config = {
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
            }
        }, {
            key: 'descriptionBlock2',
            type: 'wysiwyg',
            templateOptions: {
                label: 'Description Block 2',
                required: true
            },
            controller: /*@ngInject*/ function($scope) {
                $scope.config = {
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
            }
        }, {
            key: 'dateFrom',
            type: 'datetime',
            templateOptions: {
                label: 'Date Starting',
                required: true,
                placeholder: 'Date from'
            }
        }, {
            key: 'dateTo',
            type: 'datetime',
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