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
                vm.dropzone.createThumbnailFromUrl(mockFile, file.relativePath);
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
            // TODO: Move this shit to separate service
            vm.event.dateFrom = moment(vm.event.dateFrom);
            vm.event.dateTo = vm.event.dateTo ? moment(vm.event.dateTo) : null;

            Event.edit({slug: vm.event.slug}, {
                event: {
                    title: vm.event.title,
                    descriptionBlock1: vm.event.descriptionBlock1,
                    descriptionBlock2: vm.event.descriptionBlock2,
                    dateFrom: {
                        date: vm.event.dateFrom.format('YYYY-MM-DD'),
                        time: {
                            hour: vm.event.dateFrom.get('hour'),
                            minute: vm.event.dateFrom.get('minute')
                        }
                    },
                    dateTo: vm.event.dateTo ? {
                        date: vm.event.dateTo.format('YYYY-MM-DD'),
                        time: {
                            hour: vm.event.dateTo.get('hour'),
                            minute: vm.event.dateTo.get('minute')
                        }
                    } : null,
                    location: vm.event.location,
                }
            }, function sc(response) {
                console.log(response);
            });
        }
    }
})();