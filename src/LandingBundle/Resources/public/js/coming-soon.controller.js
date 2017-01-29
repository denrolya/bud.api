(function () {
    'use strict';

    angular
        .module('comingSoon', ['ngAnimate', 'ui.bootstrap'])
        .controller('ComingSoonController', ComingSoonController);

    ComingSoonController.$inject = ['$scope', '$http'];
    function ComingSoonController($scope, $http) {
        var vm = this;

        vm.email = '';

        vm.slides = [{
            img: 'bundles/landing/img/scr1.png',
            alt: "Screenshot made from application. Displays the view on 'Cafes' category"
        }, {
            img: 'bundles/landing/img/scr2.png',
            alt: ""
        }, {
            img: 'bundles/landing/img/scr3.png',
            alt: ""
        }, {
            img: 'bundles/landing/img/scr4.png',
            alt: ""
        }, {
            img: 'bundles/landing/img/scr5.png',
            alt: ""
        }];

        vm.submitEmail = function() {
            if ($scope.potentialclient.$valid) {
                $http.post(window.location.href, {
                    potentialclient: {email: vm.email}
                }).then(function sc(response) {
                    swal({
                        title: "Cool!",
                        html: "<p style=\"line-height: 2em;\">Thank you for leaving me your email, it is indeed valuable for us! You'll be notified on future updates, you will contribute alot by liking our facebook page with one simple click <iframe src=\"https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2Fgreenxapp&width=112&layout=button_count&action=like&show_faces=true&share=true&height=46&appId\" width=\"127\" height=\"20\" style=\"border:none;overflow:hidden\" scrolling=\"no\" frameborder=\"0\" allowTransparency=\"true\"></iframe></p>",
                        type: 'success',
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonColor: '#d9534f'
                    });

                    vm.email = '';
                }, function ec(err) {
                    swal({
                        title: "Oops...",
                        text: err.data.error,
                        type: 'error',
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonColor: '#d9534f'
                    });
                })
            } else {
                swal({
                    title: "Oops...",
                    text: "You've entered invalid email address!",
                    type: 'error',
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonColor: '#d9534f'
                });
            }
        }
    }
})();