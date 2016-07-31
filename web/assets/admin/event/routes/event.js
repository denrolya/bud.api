(function() {
    'use strict';

    //Setting up routes
    angular
        .module('admin')
        .config(['$stateProvider',
            function($stateProvider) {
                $stateProvider
                    .state('event', {
                        abstract: true,
                        url: '/events',
                        template: '<ui-view/>'
                    })
                    .state('event.new', {
                        url: '/new',
                        templateUrl: '/assets/admin/event/views/create.html',
                        controller: 'CreateController',
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
                    })
                    .state('event.list', {
                        url: '/list',
                        templateUrl: '/assets/admin/event/views/list.html',
                        controller: 'ListController',
                        controllerAs: 'vm',
                        resolve: {
                            loadPlugin: function($ocLazyLoad) {
                                return $ocLazyLoad.load([{
                                    serie:true,
                                    files: [
                                        '/assets/admin/event/controllers/list.js'
                                    ]
                                }])
                            }
                        }
                    });
            }]);
})();

