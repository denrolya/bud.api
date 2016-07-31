(function () {
    'use strict';

    angular
        .module('admin')
        .controller('EventCreateController', EventCreateController);

    EventCreateController.$inject = ['$scope', 'EventFormFields', 'Admin'];
    function EventCreateController($scope, EventFormFields, Admin) {
        var vm = this;

        vm.newEvent = {};

        vm.eventFormFields = EventFormFields;

        vm.submitEvent = submitEvent;

        vm.dropzoneConfig = {
            'options': {
                url: "/app_dev.php/api/secure/events/files",
                maxFilesize: 100,
                paramName: "uploadfile",
                maxThumbnailFilesize: 5,
                autoProcessQueue: true,
                maxFiles: 10,
                parallelUploads: 10,
                init: function() {
                    vm.dropzone = this;
                },
                dictDefaultMessage: "<strong class=\"text-uppercase\"><i class=\"fa fa-upload\"></i> Drop files to attach, or <a href=\"#\" class=\"text-green\">browse</a></strong>",
                thumbnailWidth: 160,
                thumbnailHeight: 90
            },
            'eventHandlers': {
                'sending': function (file, xhr, formData) {
                    formData.append("_token", angular.element.find('meta[name="csrf-token"]')[0].content);
                },
                'success': function(file, response) {
                    vm.newEvent.images = vm.newEvent.images || [];

                    vm.newEvent.images.push(response);

                    $scope.$apply();
                },
                'maxfilesexceeded': function(file){
                    this.removeFile(file);
                },
                'addedfile' : function(file) {
                    $scope.$digest();
                }
            }
        };

        function submitEvent() {
            Admin.createEvent(vm.newEvent, function sc(response) {
                console.log(response);
            });
        }
    }
})();