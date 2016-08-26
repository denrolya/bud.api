(function () {
    'use strict';

    angular
        .module('admin')
        .controller('ListController', ListController);

    ListController.$inject = ['Event', 'SweetAlert'];
    function ListController(Event, SweetAlert) {
        var vm = this;

        vm.events = [];

        vm.getEvents = getEvents;
        vm.removeEvent = removeEvent;

        vm.getEvents();

        function getEvents() {
            Event.get(function sc(response) {
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
                        SweetAlert.swal("Deleted!", "Your imaginary file has been deleted.", "success");
                    } else {
                        SweetAlert.swal("Cancelled", "Your imaginary file is safe :)", "error");
                    }
                });
        }
    }
})();