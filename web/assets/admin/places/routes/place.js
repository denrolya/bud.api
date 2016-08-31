(function() {
    'use strict';

    //Setting up routes
    angular
        .module('admin')
        .config(['$stateProvider',
            function($stateProvider) {
                $stateProvider
                    .state('place', {
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
                    .state('place.new', {
                        url: '/create',
                        templateUrl: '/assets/admin/places/views/create.html',
                        controller: 'CreatePlaceController',
                        controllerAs: 'vm',
                    })
                    .state('place.list', {
                        url: '/list',
                        templateUrl: '/assets/admin/places/views/list.html',
                        controller: 'ListPlaceController',
                        controllerAs: 'vm',
                    })
                    .state('place.edit', {
                        url: '/:placeSlug/edit',
                        templateUrl: '/assets/admin/places/views/edit.html',
                        controller: 'EditPlaceController',
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

