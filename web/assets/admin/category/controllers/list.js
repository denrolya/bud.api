(function () {
    'use strict';

    angular
        .module('admin')
        .controller('ListController', ListController);

    ListController.$inject = ['Category'];
    function ListController(Category) {
        var vm = this;

        vm.categories = [];

        vm.getCategories = getCategories;

        vm.getCategories();

        function getCategories() {
            Category.get(function sc(response) {
                vm.categories = response.categories;
            })
        }
    }
})();