(function () {
    'use strict';

    angular
        .module('admin')
        .controller('DashboardController', DashboardController);

    DashboardController.$inject = [];
    function DashboardController() {
        var vm = this;

        vm.testData = "Hello from dashboard controller.";
    }
})();