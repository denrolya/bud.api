(function () {
    'use strict';

    angular
        .module('admin')
        .controller('CreateController', CreateController);

    CreateController.$inject = ['$scope', 'PlaceFormFields', 'Place', 'PlaceService'];
    function CreateController($scope, PlaceFormFields, Place, PlaceService) {
        var vm = this;

        vm.newPlace = {};

        vm.placeFormFields = PlaceFormFields;

        vm.submitPlace = submitPlace;

        vm.dropzoneConfig = {
            'options': {
                url: "/app_dev.php/api/secure/places/files",
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
                    vm.newPlace.images = vm.newPlace.images || [];

                    vm.newPlace.images.push(response);

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

        function submitPlace() {
            var newPlace = new Place(PlaceService.formatPlaceToEdit(vm.newPlace));
            newPlace.$save(function sc(response) {
                console.log(response);
            });
        }
    }
})();