(function () {
    'use strict';

    angular
        .module('admin')
        .controller('EditCategoryController', EditCategoryController);

    EditCategoryController.$inject = ['$state', '$stateParams', 'CategoryFormFields', 'category', 'Category', 'SweetAlert'];
    function EditCategoryController($state, $stateParams, CategoryFormFields, category, Category, SweetAlert) {
        var vm = this;

        category.$promise.then(function(r) {
            var mockFile = {
                id: r.coverImage.id,
                name: r.coverImage.name,
                size: r.coverImage.size,
                path: r.coverImage.uri,
                accepted: true
            };

            vm.dropzone.emit("addedfile", mockFile);
            vm.dropzone.createThumbnailFromUrl(mockFile, mockFile.path);
            vm.dropzone.files.push(mockFile);
            vm.dropzone.emit("complete", mockFile);
        });

        vm.category = category;
        vm.categoryFormFields = CategoryFormFields;

        vm.editCategory = editCategory;

        vm.dropzoneConfig = {
            'options': {
                url: "/app_dev.php/api/secure/categories/" + $stateParams.slug + "/files",
                maxFilesize: 100,
                paramName: "uploadfile",
                addRemoveLinks: false,
                maxThumbnailFilesize: 5,
                autoProcessQueue: true,
                maxFiles: 1,
                parallelUploads: 1,
                init: function () {
                    vm.dropzone = this;
                }
            },
            'eventHandlers': {
                'sending': function (file, xhr, formData) {
                    formData.append("_token", angular.element.find('meta[name="csrf-token"]')[0].content);
                },
                'success': function (file, response) {
                    vm.category.coverImage = response;
                },
                'maxfilesexceeded': function (file) {
                    this.removeAllFiles();
                    this.addFile(file);
                },
                'removedfile': function (file) {
                }
            }
        }

        function editCategory(goToListing) {
            var category = {
                category: {
                    name: vm.category.name
                }
            };

            Category.edit({categorySlug: vm.category.slug}, category, function sc(response) {
                if (goToListing) {
                    $state.go('category.list')
                } else {
                    SweetAlert.swal({
                        title: "Success!",
                        text: "Category was edited successfully.",
                        timer: 1500,
                        showConfirmButton: true
                    })
                }
            });
        }
    }
})();