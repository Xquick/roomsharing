function uploadImage(form, objectId) {
    var formData = new FormData(form[0]);
    var url = 'user_c/uploadImage/' + objectId;
//    console.log(url + '.......');
//    console.log(formData);

    $.ajax({
        url: url,
        type: 'POST',
        async: false,
        xhr: function () {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        success: function (output) {
//            window.location.href = 'objects';
        },
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
}
