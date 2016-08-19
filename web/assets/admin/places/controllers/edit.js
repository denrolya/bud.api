(function () {
    'use strict';

    angular
        .module('admin')
        .controller('EditController', EditController);

    EditController.$inject = ['$scope', '$stateParams', 'PlaceFormFields', 'Place', 'place', 'PlaceService'];
    function EditController($scope, $stateParams, PlaceFormFields, Place, place, PlaceService) {
        var vm = this;

        place.$promise.then(function(r) {
            angular.forEach(vm.place.images, function (file, index) {
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

        vm.place = place;
        vm.placeFormFields = PlaceFormFields;

        vm.editPlace = editPlace;

        vm.dropzoneConfig = {
            'options': {
                url: "/app_dev.php/api/secure/places/" + $stateParams.slug + "/files",
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
                    vm.place.images = vm.place.images || [];
                    vm.place.images.push(response);
                },
                'maxfilesexceeded': function (file) {
                    this.removeFile(file);
                },
                'removedfile': function (file) {
                    if (file.id) {
                        Place.removeFile({slug: $stateParams.slug, fileId: file.id}, function sc(response) {
                            console.log('File successfully removed!');
                        })
                    }
                }
            }
        }

        function editPlace() {
            Place.edit({slug: vm.place.slug}, PlaceService.formatPlaceToEdit(vm.place), function sc(response) {
                console.log(response);
            });
        }
    }
})();