(function() {
    'use strict';

    angular
        .module('admin')
        .factory('Event', Event);

    Event.$inject = ['$resource'];
    function Event($resource) {
        return $resource('/app_dev.php/api/secure/events/:categorySlug', {eventSlug: '@eventSlug'}, {});
    }
})();