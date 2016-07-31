(function (){
    'use strict';

    //Setting up routes
    angular
        .module('admin')
        .config(['$stateProvider', '$urlRouterProvider',
            function($stateProvider, $urlRouterProvider) {
                $stateProvider
                    .state('admin', {
                        abstract: true
                    })
                    .state('category.list', {
                        url: '/categories',
                        templateUrl: '/assets/admin/category/views/list.html',
                        controller: 'ListController',
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
                        parent: 'admin'
                    })
                    .state('category.new', {
                        url: '/categories/new',
                        templateUrl: '/assets/admin/category/views/create.html',
                        controller: 'CreateController',
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
                    .state('event.create', {
                        url: '/events/new',
                        templateUrl: '/assets/admin/views/event.create.html',
                        controller: 'EventCreateController',
                        controllerAs: 'vm',
                        resolve: {
                            loadPlugin: function ($ocLazyLoad) {
                                return $ocLazyLoad.load([{
                                    serie: true,
                                    files: [
                                        '/assets/admin/forms/event.js',
                                        '/assets/admin/event/controllers/create.js'
                                    ]
                                }]);
                            }
                        }
                    });

                $urlRouterProvider.otherwise('/categories');
            }
        ]);
})();