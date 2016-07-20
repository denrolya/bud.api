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
            }
        ]);
})();