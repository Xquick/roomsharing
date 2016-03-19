jQuery(function ($) {
    //USER
    //USER - MESSAGES
    var pathArray = window.location.pathname.split('/');
    var conversation = pathArray[3];
//    if (typeof conversation == 'undefined') {
    if (pathArray[2] == 'messages') {
        var lastConversation = getCookie('lastConversation');
        if (typeof lastConversation == 'undefined') {
            var firstConversation = $('.message-item-name').find('a').attr('href').split('/');
            firstConversation = firstConversation[firstConversation.length - 1];
            setCookie('lastConversation', firstConversation, 365);
            lastConversation = firstConversation;
        }
        showUserConversation('/site_c/getMessages/' + lastConversation);
        $('.message-item[data-id=' + lastConversation + ']').addClass('active');
//    }
    }
});

