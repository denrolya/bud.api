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
                    })
                    .state('event.edit', {
                        url: '/:slug/edit',
                        templateUrl: '/assets/admin/event/views/edit.html',
                        controller: 'EditController',
                        controllerAs: 'vm',
                        resolve: {
                            event: function($stateParams, Event) {
                                return Event.get({slug: $stateParams.slug}, function sc(response) {
                                    response.dateFrom = moment(response.dateFrom);
                                    response.dateTo = (response.dateTo) ? moment(response.dateTo) : null;

                                    return response;
                                });
                            },
                            loadPlugin: function($ocLazyLoad) {
                                return $ocLazyLoad.load([{
                                    serie: true,
                                    files: [
                                        '/assets/admin/forms/event.js',
                                        '/assets/admin/event/controllers/edit.js'
                                    ]
                                }])
                            }
                        }
                    });
            }]);
})();

