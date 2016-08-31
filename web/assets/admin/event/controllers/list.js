(function () {
    'use strict';

    angular
        .module('admin')
        .controller('ListEventController', ListEventController);

    ListEventController.$inject = ['$state', 'Event', 'SweetAlert'];
    function ListEventController($state, Event, SweetAlert) {
        var vm = this;

        vm.events = [];

        vm.getEvents = getEvents;
        vm.removeEvent = removeEvent;

        vm.getEvents();

        function getEvents() {
            Event.get(function sc(response) {
                if (response.events.length === 0) {
                    $state.go('event.new');
                }
                vm.events = response.events;
            });
        }

        function removeEvent(event) {
            SweetAlert.swal({
                    title: "Are you sure?",
                    text: "Your will not be able to recover this shit!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false },
                function(isConfirm){
                    if (isConfirm) {
                        Event.delete({slug: event.slug}, function(response) {
                            vm.events.splice(vm.events.indexOf(event), 1);
                            SweetAlert.swal("Deleted!", "Removed your fuckin event.", "success");
                        });
                    } else {
                        SweetAlert.swal("Cancelled", "Your imaginary file is safe :)", "error");
                    }
                });
        }
    }
})();