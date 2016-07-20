(function () {
    'use strict';

    angular
        .module('admin')
        .controller('DashboardController', DashboardController);

    DashboardController.$inject = ['Admin'];
    function DashboardController(Admin) {
        var vm = this;

        vm.categories = [];

        vm.getCategories = getCategories;

        vm.getCategories();

        function getCategories() {
            Admin.getCategories(function sc(response) {
                vm.categories = response.categories;
            });
        }
    }
})();