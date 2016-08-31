(function() {
    'use strict';

    angular
        .module('admin')
        .factory('Place', Place);

    Place.$inject = ['$resource'];
    function Place($resource) {
        return $resource('/app_dev.php/api/secure/places/:placeSlug', { placeSlug : '@placeSlug' }, {
            edit: {
                method: 'POST'
            },
            delete: {
                method: 'DELETE',
            },
            removeFile: {
                method: 'DELETE',
                url: '/app_dev.php/api/secure/places/:placeSlug/files/:fileId',
                params: {
                    fileId: '@fileId'
                }
            }
        });
    }
})();