(function () {
    'use strict';

    angular
        .module('admin')
        .controller('EditController', EditController);

    EditController.$inject = ['$scope', '$stateParams', 'EventFormFields', 'Event', 'event'];
    function EditController($scope, $stateParams, EventFormFields, Event, event) {
        var vm = this;

        event.$promise.then(function(r) {
            angular.forEach(vm.event.images, function (file, index) {
                var mockFile = {
                    id: file.id,
                    name: file.name,
                    size: file.size
                };

                vm.dropzone.emit("addedfile", mockFile);
                vm.dropzone.createThumbnailFromUrl(mockFile, file.relative_path);
                vm.dropzone.files.push(mockFile);
                vm.dropzone.emit("complete", mockFile);
            }, vm.dropzone);
        });

        vm.event = event;
        vm.eventFormFields = EventFormFields;

        vm.editEvent = editEvent;

        vm.dropzoneConfig = {
            'options': {
                url: "/app_dev.php/api/secure/events/" + $stateParams.slug + "/files",
                maxFilesize: 100,
                paramName: "uploadfile",
                addRemoveLinks: true,
                maxThumbnailFilesize: 5,
                autoProcessQueue: true,
                maxFiles: 10,
                parallelUploads: 10,
                init: function () {
                    vm.dropzone = this;
                }
            },
            'eventHandlers': {
                'sending': function (file, xhr, formData) {
                    formData.append("_token", angular.element.find('meta[name="csrf-token"]')[0].content);
                },
                'success': function (file, response) {
                    vm.event.images = vm.event.images || [];
                    vm.event.images.push(response);
                },
                'maxfilesexceeded': function (file) {
                    this.removeFile(file);
                },
                'removedfile': function (file) {
                    if (file.id) {
                        Event.removeFile({slug: $stateParams.slug, fileId: file.id}, function sc(response) {
                            console.log('File successfully removed!');
                        })
                    }
                }
            }
        }

        function editEvent() {
            Event.edit({slug: vm.event.slug}, vm.event, function sc(response) {
                console.log(response);
            });
        }
    }
})();