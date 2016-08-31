(function () {
    'use strict';

    angular
        .module('admin')
        .controller('EditEventController', EditEventController);

    EditEventController.$inject = ['$scope', '$state', '$stateParams', 'EventFormFields', 'Event', 'event', 'EventService', 'SweetAlert'];
    function EditEventController($scope, $state, $stateParams, EventFormFields, Event, event, EventService, SweetAlert) {
        var vm = this;

        event.$promise.then(function(r) {
            angular.forEach(vm.event.images, function (file, index) {
                var mockFile = {
                    id: file.id,
                    name: file.name,
                    size: file.size,
                    path: file.uri,
                    accepted: true
                };

                vm.dropzone.emit("addedfile", mockFile);
                vm.dropzone.createThumbnailFromUrl(mockFile, mockFile.path);
                vm.dropzone.files.push(mockFile);
                vm.dropzone.emit("complete", mockFile);
            }, vm.dropzone);
        });

        vm.event = event;
        vm.eventFormFields = EventFormFields;

        vm.editEvent = editEvent;

        vm.dropzoneConfig = {
            'options': {
                url: "/app_dev.php/api/secure/events/" + $stateParams.eventSlug + "/files",
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
                        Event.removeFile({eventSlug: $stateParams.eventSlug, fileId: file.id}, function sc(response) {
                            SweetAlert.swal({
                                title: "Success!",
                                text: "File was fuckin deleted.",
                                timer: 1500,
                                showConfirmButton: true
                            })
                        })
                    }
                }
            }
        }

        function editEvent(goToListing) {
            Event.edit({eventSlug: vm.event.slug}, EventService.formatEventToEdit(vm.event), function sc(response) {
                vm.eventForm = undefined;
                if (goToListing) {
                    $state.go('event.list')
                } else {
                    SweetAlert.swal({
                        title: "Success!",
                        text: "Event was edited successfully.",
                        timer: 1500,
                        showConfirmButton: true
                    })
                }
            });
        }
    }
})();