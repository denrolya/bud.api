(function () {
    'use strict';

    angular
        .module('admin')
        .controller('ListController', ListController);

    ListController.$inject = ['$state', 'Category', 'SweetAlert'];
    function ListController($state, Category, SweetAlert) {
        var vm = this;

        vm.categories = [];

        vm.getCategories = getCategories;
        vm.removeCategory = removeCategory;

        vm.getCategories();

        function getCategories() {
            Category.get(function sc(response) {
                if (response.categories.length === 0) {
                    $state.go('category.new');
                }
                vm.categories = response.categories;
            })
        }

        function removeCategory(category) {
            SweetAlert.swal({
                    title: "Are you sure?",
                    text: "Your will not be able to recover this shit!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel pls!",
                    closeOnConfirm: false,
                    closeOnCancel: false },
                function(isConfirm){
                    if (isConfirm) {
                        Category.delete({categorySlug: category.slug}, function(response) {
                            vm.categories.splice(vm.categories.indexOf(category), 1);
                            SweetAlert.swal("Deleted!", "Removed your fuckin category.", "success");
                        });
                    } else {
                        SweetAlert.swal("Cancelled", "Category is fuckin safe.", "error");
                    }
                });
        }
    }
})();