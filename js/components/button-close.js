jQuery(function () {
    $(document).on("click", global.e.raw.button_close, function () {
//        TODO Fix Hardcoded element
        global.f.hideElement(global.e.inactiveArea, $("#roommate"), "bottom");
        global.f.hideElement(global.e.inactiveArea, $("#roommates-new"), "bottom");
        global.f.hideElement(global.e.inactiveArea, $("#object-new"), "bottom");
        global.f.hideElement(global.e.inactiveArea, $(global.e.raw.ad_detail), "bottom");
        global.f.hideElement(global.e.inactiveArea, global.e.ad_new, "bottom");
        $(document).find(".info-line").hide();
        mapObject.getObjectsInBounds();
    });
});
