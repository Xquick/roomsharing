var layout;
jQuery(function () {
    layout = {
        resizeElements: function () {
            var windowHeight = $(window).height();
            var mapHeight = $(document).find('.right-menu-top').outerHeight();
            var textarea = $(document).find('.messages-conversation-new-message').outerHeight();
            var header = $(document).find('#header').outerHeight();
            var padding = 20;
            $(document).find('#menu').innerHeight(windowHeight);
            $(document).find('#menu-right').innerHeight(windowHeight);
            $(document).find('#wrap').innerHeight(windowHeight);
            $(document).find('.right-window').innerHeight(windowHeight);
            $(document).find('.left-window').innerHeight(windowHeight);
            $(document).find('#activity').height(windowHeight - mapHeight);
            $(document).find('#messages').height(windowHeight - header - padding);
            $(document).find('.messages-list').height(windowHeight - header - padding);
            $(document).find('.messages-conversation-wrapper').height(windowHeight - header - textarea - padding);
            $(document).find('.messages-conversation-new-message').css("top",windowHeight - header - padding);
            //Map needs to be resized after changing its container size
//            google.maps.event.trigger(map, "resize");
        }
    };
    layout.resizeElements();

});