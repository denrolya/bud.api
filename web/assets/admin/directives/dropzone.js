(function() {
    'use strict';

    angular
        .module('dropzone', [])
        .directive('dropzone', function () {
            return {
                restrict: 'A',
                scope: {
                    config: '=config'
                },
                link: function (scope, element, attrs) {
                    Dropzone.autoDiscover = false;
                    var dropzone = new Dropzone(element[0], scope.config.options);

                    // bind the given event handlers
                    angular.forEach(scope.config.eventHandlers, function (handler, event) {
                        dropzone.on(event, handler);
                    });

                    scope.processDropzone = function() {
                        dropzone.processQueue();
                    };

                    scope.resetDropzone = function() {
                        dropzone.removeAllFiles();
                    }
                }
            }
        });
})();