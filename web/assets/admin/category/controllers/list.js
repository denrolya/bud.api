(function () {
    'use strict';

    angular
        .module('admin')
        .controller('ListController', ListController);

    ListController.$inject = ['Admin'];
    function ListController(Admin) {
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