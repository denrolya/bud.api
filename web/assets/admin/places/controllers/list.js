(function () {
    'use strict';

    angular
        .module('admin')
        .controller('ListPlaceController', ListPlaceController);

    ListPlaceController.$inject = ['$state', 'Place', 'SweetAlert'];
    function ListPlaceController($state, Place, SweetAlert) {
        var vm = this;

        vm.places = [];

        vm.getPlaces = getPlaces;
        vm.removePlace = removePlace;

        vm.getPlaces();

        function getPlaces() {
            Place.get(function sc(response) {
                if (response.places.length === 0) {
                    $state.go('place.new');
                }
                vm.places = response.places;
            });
        }

        function removePlace(place) {
            SweetAlert.swal({
                    title: "Are you sure?",
                    text: "Your will not be able to recover this shit!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel pls!",
                    closeOnConfirm: false,
                    closeOnCancel: false },
                function(isConfirm){
                    if (isConfirm) {
                        Place.delete({placeSlug: place.slug}, function sc(response) {
                            vm.places.splice(vm.places.indexOf(place), 1);
                            SweetAlert.swal("Deleted!", "Removed place.", "success");
                        });
                    } else {
                        SweetAlert.swal("Cancelled", "Place is safe.", "error");
                    }
                });
        }
    }
})();