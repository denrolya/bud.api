(function () {
    'use strict';

    angular
        .module('admin')
        .controller('ListController', ListController);

    ListController.$inject = ['Event'];
    function ListController(Event) {
        var vm = this;

        vm.events = [];

        vm.getEvents = getEvents;

        vm.getEvents();

        function getEvents() {
            Event.get(function sc(response) {
                vm.events = response.events;
            });
        }
    }
})();