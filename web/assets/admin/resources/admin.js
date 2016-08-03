(function() {
    'use strict';

    angular
        .module('admin')
        .factory('Admin', Admin);

    Admin.$inject = ['$resource'];
    function Admin($resource) {
        return $resource('/app_dev.php/api/secure', {}, {});
    }
})();