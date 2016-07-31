(function() {
    'use strict';

    angular
        .module('admin')
        .factory('Category', Category);

    Category.$inject = ['$resource'];
    function Category($resource) {
        return $resource('/app_dev.php/api/secure/categories/:categorySlug', {categorySlug: '@categorySlug'}, {});
    }
})();