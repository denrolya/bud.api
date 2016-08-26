(function() {
    'use strict';

    //Setting up routes
    angular
        .module('admin')
        .config(['$stateProvider',
            function($stateProvider) {
                $stateProvider
                    .state('places', {
                        abstract: true,
                        url: '/places',
                        template: '<ui-view/>',
                        resolve: {
                            loadPlugin: function ($ocLazyLoad) {
                                return $ocLazyLoad.load([{
                                    serie: true,
                                    files: [
                                        '/assets/admin/forms/place.js',
                                        '/assets/admin/places/controllers/create.js',
                                        '/assets/admin/places/controllers/list.js',
                                        '/assets/admin/places/controllers/edit.js',
                                        '/assets/admin/places/services/place.js'
                                    ]
                                }]);
                            }
                        }
                    })
                    .state('places.new', {
                        url: '/create',
                        templateUrl: '/assets/admin/places/views/create.html',
                        controller: 'CreateController',
                        controllerAs: 'vm',
                    })
                    .state('places.list', {
                        url: '/list',
                        templateUrl: '/assets/admin/places/views/list.html',
                        controller: 'ListController',
                        controllerAs: 'vm',
                    })
                    .state('places.edit', {
                        url: '/:slug/edit',
                        templateUrl: '/assets/admin/places/views/edit.html',
                        controller: 'EditController',
                        controllerAs: 'vm',
                        resolve: {
                            place: function($stateParams, Place) {
                                return Place.get({placeSlug: $stateParams.placeSlug}, function sc(response) {
                                    return response;
                                });
                            }
                        }
                    });
            }]);
})();

