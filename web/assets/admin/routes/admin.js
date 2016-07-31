(function (){
    'use strict';

    //Setting up routes
    angular
        .module('admin')
        .config(['$stateProvider', '$urlRouterProvider',
            function($stateProvider, $urlRouterProvider) {

                $urlRouterProvider.otherwise('/');

                $stateProvider
                    .state('dashboard', {
                        url: '/',
                        templateUrl: '/assets/admin/views/dashboard.html',
                        controller: 'DashboardController',
                        controllerAs: 'vm',
                    })
                    .state('categories', {
                        url: '/categoires',
                        templateUrl: '/assets/admin/views/categories.html',
                        controller: 'CategoryListController',
                        controllerAs: 'vm'
                    })
                    .state('events', {
                        url: '/events/new',
                        templateUrl: '/assets/admin/views/event.create.html',
                        controller: 'EventCreateController',
                        controllerAs: 'vm'
                    })
            }
        ]);
})();