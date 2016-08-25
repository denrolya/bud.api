(function() {
    'use strict';

    angular
        .module('admin')
        .factory('PlaceService', PlaceService);

    PlaceService.$inject = [];
    function PlaceService() {
        var placeService = {
            formatPlaceToEdit: formatPlaceToEdit
        };

        return placeService;

        function formatPlaceToEdit(place) {
            return {
                place: {
                    name: place.name,
                    shortDescription: place.shortDescription,
                    rating: place.rating,
                    priceRange: place.priceRange,
                    category: place.category.id,
                    descriptionBlock1: place.descriptionBlock1,
                    descriptionBlock2: place.descriptionBlock2,
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