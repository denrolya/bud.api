(function () {
    'use strict';

    angular
        .module('admin')
        .controller('CreateController', CreateController);

    CreateController.$inject = ['$scope', 'EventFormFields', 'Event'];
    function CreateController($scope, EventFormFields, Event) {
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
            var newEvent = new Event(vm.newEvent);
            newEvent.$save(function sc(response) {
                console.log(response);
            });
        }
    }
})();