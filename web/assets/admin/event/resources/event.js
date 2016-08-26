(function() {
    'use strict';

    angular
        .module('admin')
        .factory('Event', Event);

    Event.$inject = ['$resource'];
    function Event($resource) {
        return $resource('/app_dev.php/api/secure/events/:slug', {slug: '@slug'}, {
            edit: {
                method: 'POST'
            },
            delete: {
                method: 'DELETE'
            },
            removeFile: {
                method: 'DELETE',
                url: '/app_dev.php/api/secure/events/:slug/files/:fileId',
                params: {
                    fileId: '@fileId'
                }
            }
        });
    }
})();