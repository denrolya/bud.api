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

        vm.initMap = initMap;
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

        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: -33.8688, lng: 151.2195},
                zoom: 13
            });
            var input = document.getElementById('pac-input');

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow();
            var marker = new google.maps.Marker({
                map: map,
                anchorPoint: new google.maps.Point(0, -29)
            });

            autocomplete.addListener('place_changed', function() {
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);  // Why 17? Because it looks good.
                }
                marker.setIcon(/** @type {google.maps.Icon} */({
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(35, 35)
                }));
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                var address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }

                infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                infowindow.open(map, marker);

                vm.newPlace.name = place.name;
                vm.newPlace.address = place.formatted_address;
                vm.newPlace.phonenumber = place.international_phone_number;
                vm.newPlace.website = place.website;
                vm.newPlace.latitude = place.geometry.location.lat();
                vm.newPlace.longitude = place.geometry.location.lng();
                $scope.$apply();
            });
        }
    }
})();