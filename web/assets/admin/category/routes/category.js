(function() {
    'use strict';

    //Setting up routes
    angular
        .module('admin')
        .config(['$stateProvider',
            function($stateProvider) {
                $stateProvider
                    .state('category', {
                        abstract: true,
                        url: '/categories',
                        template: '<ui-view/>'
                    })
                    .state('category.list', {
                        url: '/list',
                        templateUrl: '/assets/admin/category/views/list.html',
                        controller: 'ListCategoryController',
                        controllerAs: 'vm',
                        resolve: {
                            loadPlugin: function ($ocLazyLoad) {
                                return $ocLazyLoad.load([{
                                    serie: true,
                                    files: [
                                        '/assets/admin/category/controllers/list.js'
                                    ]
                                }]);
                            }
                        },
                    })
                    .state('category.new', {
                        url: '/create',
                        templateUrl: '/assets/admin/category/views/create.html',
                        controller: 'CreateCategoryController',
                        controllerAs: 'vm',
                        resolve: {
                            loadPlugin: function ($ocLazyLoad) {
                                return $ocLazyLoad.load([{
                                    serie: true,
                                    files: [
                                        '/assets/admin/forms/category.js',
                                        '/assets/admin/category/controllers/create.js'
                                    ]
                                }]);
                            }
                        }
                    })
                    .state('category.edit', {
                        url: '/:categorySlug/edit',
                        templateUrl: '/assets/admin/category/views/edit.html',
                        controller: 'EditCategoryController',
                        controllerAs: 'vm',
                        resolve: {
                            category: function($stateParams, Category) {
                                return Category.get({categorySlug: $stateParams.categorySlug}, function sc(response) {
                                    return response;
                                });
                            },
                            loadPlugin: function($ocLazyLoad) {
                                return $ocLazyLoad.load([{
                                    serie: true,
                                    files: [
                                        '/assets/admin/forms/category.js',
                                        '/assets/admin/category/controllers/edit.js'
                                    ]
                                }])
                            }
                        }
                    });
            }]);
})();