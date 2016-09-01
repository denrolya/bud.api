(function () {
    'use strict';

    angular
        .module('admin')
        .controller('EditPlaceController', EditPlaceController);

    EditPlaceController.$inject = ['$scope', '$state', '$stateParams', 'PlaceFormFields', 'Place', 'place', 'PlaceService', 'SweetAlert'];
    function EditPlaceController($scope, $state, $stateParams, PlaceFormFields, Place, place, PlaceService, SweetAlert) {
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
                url: "/app_dev.php/api/secure/places/" + $stateParams.placeSlug + "/files",
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
                        Place.removeFile({placeSlug: $stateParams.placeSlug, fileId: file.id}, function sc(response) {
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

        function editPlace(goToListing) {
            Place.edit({placeSlug: vm.place.slug}, PlaceService.formatPlaceToSubmit(vm.place), function sc(response) {
                vm.placeForm = undefined;
                if (goToListing) {
                    $state.go('place.list')
                } else {
                    SweetAlert.swal({
                        title: "Success!",
                        text: "Place was edited successfully.",
                        timer: 1500,
                        showConfirmButton: true
                    })
                }
            });
        }

        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: Number(vm.place.latitude), lng: Number(vm.place.longitude)},
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
                console.log(place);

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

                vm.place.name = place.name;
                vm.place.address = place.formatted_address;
                vm.place.phonenumber = place.international_phone_number;
                vm.place.website = place.website;
                vm.place.latitude = place.geometry.location.lat();
                vm.place.longitude = place.geometry.location.lng();
                vm.newPlace.googleID = place.place_id;
                $scope.$apply();
            });
        }
    }
})();