(function () {
    'use strict';

    angular
        .module('admin')
        .controller('ListController', ListController);

    ListController.$inject = ['Place'];
    function ListController(Place) {
        var vm = this;

        vm.places = [];

        vm.getPlaces = getPlaces;

        vm.getPlaces();

        function getPlaces() {
            Place.get(function sc(response) {
                vm.places = response.places;
            });
        }
    }
})();