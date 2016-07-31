(function() {
    'use strict';

    angular
        .module('admin')
        .factory('Admin', Admin);

    Admin.$inject = ['$resource'];
    function Admin($resource) {
        return $resource('/app_dev.php/api/secure', {}, {
            createEvent: {
                method: 'POST',
                url: '/app_dev.php/api/secure/event',
                isArray: false
            }
        });
    }
})();