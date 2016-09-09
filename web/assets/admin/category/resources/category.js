(function() {
    'use strict';

    angular
        .module('admin')
        .factory('Category', Category);

    Category.$inject = ['$resource'];
    function Category($resource) {
        return $resource('/app_dev.php/api/secure/categories/:categorySlug', {categorySlug: '@categorySlug'}, {
            getPlaces: {
                url: '/app_dev.php/api/secure/categories/:categorySlug/places'
            },
            edit: {
                method: 'POST'
            },
            delete: {
                method: 'DELETE'
            },
            removeFile: {
                method: 'DELETE',
                url: '/app_dev.php/api/secure/categories/:slug/files/:fileId',
                params: {
                    fileId: '@fileId'
                }
            }
        });
    }
})();