(function() {
    'use strict';

    angular
        .module('admin')
        .factory('Place', Place);

    Place.$inject = ['$resource'];
    function Place($resource) {
        return $resource('/app_dev.php/api/secure/places/:slug', {slug: '@slug'}, {
            edit: {
                method: 'POST'
            },
            removeFile: {
                method: 'DELETE',
                url: '/app_dev.php/api/secure/places/:slug/files/:fileId',
                params: {
                    fileId: '@fileId'
                }
            }
        });
    }
})();