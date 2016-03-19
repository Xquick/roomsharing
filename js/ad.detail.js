$(document).ready(function () {

    getDetail = function (adId) {
        var adDetail;
        $.post('site_c/getAdDetail', {adId: adId}, function (output) {
//        console.log(output);
            adDetail = JSON.parse(output);
            console.log(adDetail);
        });
    }
});
var getDetail;
function imageTemplate(imageId) {
    return '<img src="../galleries/' + imageId + '/1.jpg"/>';
}
function locationTemplate(text) {
    return '<>';
}
function descriptionTemplate(text) {

}

