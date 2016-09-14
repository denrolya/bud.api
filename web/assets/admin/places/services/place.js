(function() {
    'use strict';

    angular
        .module('admin')
        .factory('PlaceService', PlaceService);

    PlaceService.$inject = [];
    function PlaceService() {
        var placeService = {
            formatPlaceToSubmit: formatPlaceToSubmit
        };

        return placeService;

        function formatPlaceToSubmit(place) {
            return {
                place: {
                    name: place.name,
                    shortDescription: place.shortDescription,
                    rating: place.rating,
                    priceRange: place.priceRange,
                    categories: place.categories.map(function(v) {
                        return v.id
                    }),
                    fullDescription: place.fullDescription,
                    address: place.address,
                    latitude: place.latitude,
                    longitude: place.longitude,
                    website: place.website,
                    phonenumber: place.phonenumber,
                    images: place.images,
                    opened: place.opened,
                    googleID: place.googleID
                }
            }
        }
    }
})();