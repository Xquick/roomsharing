jQuery(function () {
    $(document).on("click", ".ad-detail-contact", function (e) {
        var adId = $("#ad-detail").attr("data-ad-id");
        var $loader = $(document).find(".user-contact-card .loader");

        if (!$(document).find(global.e.raw.chat.appended).length) {
            $.ajax({
                url: "/ad_c/getOwner/" + adId,
                type: "POST",
                success: function (data) {
                    var userId = $(data).attr("data-userId");
                    var user_card = $(data);
//                    user_card.find("loader").show();
                    user_card.draggable();
                    $(".ad-detail-box").append(user_card);
                    appendConversation(userId);
                }
            });
        }
    });

    $(document).on("click", ".roommate-contact", function (e) {
        var $target = $(e.target);
        var userId = parseInt($target.closest(".roommate").attr("data-id"));

        if (!isNaN(userId)) {
            $.ajax({
                url: "/chat_c/getUserChat/" + userId,
                type: "POST",
                success: function (data) {
                    var userId = $(data).attr("data-userId");
                    var user_card = $(data);
//                    user_card.find("loader").show();
                    user_card.draggable();
                    $(document).find(".roommate-info").append(user_card);
                    appendConversation(userId);
                }
            });
        }
    });

    function appendConversation(userId) {
        $.ajax({
            url: '/chat_c/createConversation/' + userId,
            type: 'post',
            success: function (output) {
                var conversationId = parseInt(output);
                chat.append(conversationId);
            }
        });
    }
});