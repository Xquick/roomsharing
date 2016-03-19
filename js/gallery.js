jQuery(function () {
    previewImages(this.files);

    $(document).on("change", "#fileupload-input", function (e) {
        e.preventDefault();
        var input = $(document).find('#fileupload-input');
        var data = new FormData();
        var $progressBar = $(document).find('.progress-bar').clone();
        var files = input.prop("files");
        var objectId = parseInt($(document).find("#object-new").attr("data-objectid"));
        for (var i = 0; i < files.length; i++) {
            data.append("files[]", files[i]);
        }
        var request = new XMLHttpRequest();

        request.upload.addEventListener("loadstart", function () {
            console.log("loadstart");
            removeProgressbars();
            $(document).find(".progress-container").append($progressBar);
        });

        request.upload.addEventListener("progress", function (e) {
            var percent = Math.round((e.loaded / e.total) * 100);
            // Increase the progress bar length.
            if (percent <= 100) {
                $progressBar.css("width", percent + '%');
                $progressBar.text(percent + '%');
            }
        });

        request.upload.addEventListener('load', function (e) {
//            removeProgressbars();
        });

        request.upload.addEventListener('error', function () {
            console.log("error");
        });

        request.addEventListener("readystatechange", function () {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    console.log(this.response);

                    var response = JSON.parse(this.response);
                    console.log(response);

                    $(".upload-image-preview .no-results").remove();
                    for (var i = 0; i < response.length; i++) {
                        console.log(response[i]);
                        $('body').find('.upload-image-preview').append('<div class="upload-image-preview-item">' +
                            '<img src="' + response[i] + '">' +
                            '<div class="object-showcase-item-selection"></div>' +
                            '</div>');
                    }
                }
            }
        });

        request.open("post", "/user_c/upload/" + objectId);
        request.setRequestHeader("Cache-Control", "no-cache");
        request.send(data);
    });

    function removeProgressbars() {
        $(document).find('.progress-bar').each(function () {
            $(this).remove();
        });
    }
});


function previewImages(files) {

//    for (var i = 0; i < files.length; i++) { //for multiple files
//        (function (file) {
//            var name = file.name;
//            var reader = new FileReader();
//            var $progressBar = $(document).find('.progress-bar:first-child').clone();
//
//            reader.onload = function (e) {
//                $('body').find('.upload-image-preview').append('<div class="upload-image-preview-item">' +
//                    '<img src="' + e.target.result + '">' +
//                    '<div class="object-showcase-item-selection"></div>' +
//                    '</div>');
//            };
//
//            reader.onloadstart = function (e) {
//                $(document).find(".progress-container").append($progressBar);
//            };
//
//            reader.onprogress = function (e) {
//                console.log(Math.round((e.loaded / e.total) * 100));
//                var percentLoaded = Math.round((e.loaded / e.total) * 100);
//
////                // Increase the progress bar length.
////                if (percentLoaded < 100) {
////                    $progressBar.css("width", percentLoaded + '%');
////                    $progressBar.text(percentLoaded + '%');
////                }
//            };
//            reader.onloadend = function (e) {
//                $progressBar.css("width", "100%");
//                $progressBar.text('100%');
//            };
//            reader.readAsDataURL(file);
//        })(files[i]);
//    }
//
//    $('body').find('.upload-image-preview-item').each(function () {
//        console.log($(this).attr('class'));
//        $(this).append(s);
//    });

}
