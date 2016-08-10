(function () {
    'use strict';

    angular
        .module('admin')
        .controller('AppController', AppController);

    AppController.$inject = ['$http'];
    function AppController($http) {
        var vm = this;

        vm.searchQuery = '';

        vm.search = search;

        function search() {
            if (vm.searchQuery.length > 0) {
                $http.get('/app_dev.php/api/secure/search/' + vm.searchQuery).then(function sc(response) {
                    console.log(response);
                });
            }
        }
    }
})();