(function() {
    'use strict';

    angular
        .module('admin')
        .factory('Admin', Admin);

    Admin.$inject = ['$resource'];
    function Admin($resource) {
        return $resource('/app_dev.php/api/secure', {}, {
            getCategories: {
                method: 'GET',
                url: '/app_dev.php/api/secure/categories',
                isArray: false
            },
            createCategory: {
                method: 'POST',
                url: '/app_dev.php/api/secure/category',
                isArray: true
            },
            createEvent: {
                method: 'POST',
                url: '/app_dev.php/api/secure/event',
                isArray: false
            }
        });
    }
})();