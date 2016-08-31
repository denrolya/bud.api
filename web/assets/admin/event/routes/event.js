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
                        template: '<ui-view/>',
                        resolve: {
                            loadPlugin: function ($ocLazyLoad) {
                                return $ocLazyLoad.load([{
                                    serie: true,
                                    files: [
                                        '/assets/admin/forms/event.js',
                                        '/assets/admin/event/controllers/create.js',
                                        '/assets/admin/event/controllers/list.js',
                                        '/assets/admin/event/controllers/edit.js',
                                        '/assets/admin/event/services/event.js'
                                    ]
                                }]);
                            }
                        }
                    })
                    .state('event.new', {
                        url: '/create',
                        templateUrl: '/assets/admin/event/views/create.html',
                        controller: 'CreateEventController',
                        controllerAs: 'vm',
                    })
                    .state('event.list', {
                        url: '/list',
                        templateUrl: '/assets/admin/event/views/list.html',
                        controller: 'ListEventController',
                        controllerAs: 'vm',
                    })
                    .state('event.edit', {
                        url: '/:eventSlug/edit',
                        templateUrl: '/assets/admin/event/views/edit.html',
                        controller: 'EditEventController',
                        controllerAs: 'vm',
                        resolve: {
                            event: function($stateParams, Event) {
                                return Event.get({eventSlug: $stateParams.eventSlug}, function sc(response) {
                                    response.dateFrom = moment(response.dateFrom);
                                    response.dateTo = (response.dateTo) ? moment(response.dateTo) : null;

                                    return response;
                                });
                            }
                        }
                    });
            }]);
})();

