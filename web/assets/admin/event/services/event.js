(function() {
    'use strict';

    angular
        .module('admin')
        .factory('EventService', EventService);

    EventService.$inject = [];
    function EventService() {
        var eventService = {
            formatEventToEdit: formatEventToEdit
        };

        return eventService;

        function formatEventToEdit(event) {
            event.dateFrom = moment(event.dateFrom);
            event.dateTo = event.dateTo ? moment(event.dateTo) : null;

            return {
                event: {
                    title: event.title,
                    descriptionBlock1: event.descriptionBlock1,
                    descriptionBlock2: event.descriptionBlock2,
                    dateFrom: {
                        date: event.dateFrom.format('YYYY-MM-DD'),
                        time: {
                            hour: event.dateFrom.get('hour'),
                            minute: event.dateFrom.get('minute')
                        }
                    },
                    dateTo: event.dateTo ? {
                        date: event.dateTo.format('YYYY-MM-DD'),
                        time: {
                            hour: event.dateTo.get('hour'),
                            minute: event.dateTo.get('minute')
                        }
                    } : null,
                    location: event.location,
                }
            }
        }
    }
})();