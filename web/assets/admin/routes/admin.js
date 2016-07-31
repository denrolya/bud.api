(function (){
    'use strict';

    angular
        .module('admin')
        .config(['$urlRouterProvider',
            function($urlRouterProvider) {
                $urlRouterProvider.otherwise('/categories/list');
            }
        ]);
})();