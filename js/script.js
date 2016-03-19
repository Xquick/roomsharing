var resizeElements;
$(document).ready(function () {
    // facebookový způsob posouvání hranice mezi mapou a aktivitami uživatelů vpravo
    $('.resizableHeight').resizable({
        handles: 's'
    });

    //zamezení přesměrování odkazů, které fungují AJAXově
    $(document).on("click", "a.ajax", function (e) {
        e.preventDefault();
    });

    $(document).on("click", "#menu li", function (e) {
        e.preventDefault();
        var link = $(e.target).closest("li").find("a").attr("href");

        $.ajax({
            url: link,
            beforeSend: function () {
                $("#wrap>.loader").show();
                $(document).find("#inactive-area").show();
            },
            success: function (output) {
                var data = $($(output).find("#wrap").html());
                $(document).find("#wrap").html(data);
                $("#wrap>.loader").hide();
                $(document).find("#inactive-area").hide();
                layout.resizeElements();
                mapObject.appendGeocomplete();
                global.f.initPriceSlider();
                mapObject.getObjectsInBounds();
            }
        });
    });

    $(document).on('load', '.messages-conversation', function () {
        $('body').find('.messages-conversation').scrollTop(100000000);
    });

//------DISPLAY SLIDING WINDOWS------//////////////////////////////////////
    function initObject() {
        $(document).find('#object-available-from').datepicker({minDate: 0});
        $(document).find('#object-available-to').datepicker({minDate: 0});

        var ageFrom = $(document).find("input[name=object_age]").attr("data-ageFrom");
        var ageTo = $(document).find("input[name=object_age]").attr("data-ageTo");
        $(document).find('.object-age-slider').slider({
            range: true,
            value: [
                ageFrom == "undefined" || ageFrom == "NaN" ? object.settings.minAge : ageFrom,
                ageTo == "undefined" || ageTo == "NaN" ? object.settings.maxAge : ageTo
            ],
            min: object.settings.minAge,
            max: object.settings.maxAge,
            step: 1
        });
    }

    function initObjectEdit() {
        initObject();
        $(document).find(".object-section[data-id=" + 2 + "]").show();
        for (var i = 2; i <= 6; i++) {
            object.getValues();
            object.validate(i, true);
        }
    }

    //spolubydlící okno
    $(document).on("click", ".card-new", function (e) {
        var $loader = $(e.target).closest(".post").find(".loader");
        global.f.getContent('/site_c/adRoommates/', '#roommates-new', $('#wrap'), '#roommates-new', 'bottom', "", $loader);
    });

    //detail konkretniho spolubnydliciho
    $(document).on("click", "#roommates>.post:not(.card-new)", function (e) {
        var roommateId = parseInt($(e.target).closest(".post").attr("data-id"));
        var $loader = $(e.target).closest(".post").find(".loader");
        global.f.getContent('/site_c/roommateProfile/' + roommateId, '#roommate', $('#wrap'), '#roommate', 'bottom', "", $loader);
    });

    //přidání nového objektu - vyjetí okna
    $(document).on("click", 'button[name=object-new]', function () {
        global.f.getContent('/objects/new', '#object-new', $('#wrap'), '#object-new', 'bottom', initObject);
    });

    $(document).on("click", "#objects .icon-edit", function (e) {
        var adId = $(e.target).closest(".post").attr('data-adid');
        var $loader = $(e.target).closest(".post").find(".loader");
        global.f.getContent('/objects/edit/' + adId, '#object-new', $('#wrap'), '#object-new', 'bottom', initObjectEdit, $loader);

    });

    //detail kampaně - vyjetí okna
    $(document).on("click", ".post", function (e) {
        e.preventDefault();
        var target = e.target;
        var post = $(target).closest(".post");
        var $loader = $(e.target).closest(".post").find(".loader");

        var objectId = parseInt(post.attr('data-object-id'));
        if ($(target).hasClass('grayout') || $(target).hasClass('object-submenu-detail')) {
            var url = $(target).closest('.post').find('.grayout').closest('a').attr('href');
            global.f.getContent(url, global.e.raw.ad_detail, global.e.wrap, global.e.raw.ad_detail, "bottom", "", $loader);

            global.f.initPrettyPhoto();

            mapObject.objectOnMap(objectId);
//            $('#inactive-area').show();
        }
    });

    //aktivovat nebo deaktivovat kampaň          toggle
    $(document).on('click', '.item-active', function (e) {
        e.preventDefault();
        var $target = $(e.target);
        var link = $(this).find('a').attr('href');
        $.ajax({
            url: link,
            beforeSend: function () {
                $target.closest(".post").find(".loader").show();
            },
            success: function (output) {
                if (output != 1) {
                    $target.closest(".post").find(".card-error").fadeIn();
                } else {
                    $(e.target).toggleClass("icon-active icon-inactive");
                }
                $target.closest(".post").find(".loader").hide();
            }
        });
    });

//přepínání mezi uživateli a objekty v discover
    $(document).on('click', '.header-menu-item', function () {
        var showObjects = $(this).hasClass('header-menu-objects');
        var showUsers = $(this).hasClass('header-menu-users');
        if (showUsers) {
            $('body').find('#discover-similar-users').show();
            $('body').find('#discover-objects').hide();
        }
        if (showObjects) {
            $('body').find('#discover-objects').show();
            $('body').find('#discover-similar-users').hide();
        }
    });

//začít nebo přestat sledovat kampaň
    $(document).on('click', '.icon-follow, .icon-unfollow', function (e) {
        var link = $(this).find('a').attr('href');
        $.ajax({
            url: link,
            context: e.target,
            success: function (output) {
                console.log(output);
                if (output == 1) {
                    if ($(this).parent().hasClass("icon-follow")) {
                        global.f.addFavorite($(this).closest(global.e.raw.post));
                    }
                    else {
                        global.f.removeFavorite($(this).closest(global.e.raw.post));
                    }
                } else {
                    alert("nelze");
                }
            }
        });
    });

//klik na link AJAX
    $(document).on('click', '.ajax', function () {
        var url = $(this).attr('href');
        var type = $(this).attr('data-type');
        switch (type) {
            case 'last_messages':
                global.f.getContent(url, '#last-messages', $('#wrap'), '#last-messages', 'left');
                break;
        }
    });

//potvrzení spolubydlícího
    $(document).on('click', '.roommate .icon-like,.roommate .icon-dislike', function (e) {
        var $target = $(e.target);
        var url = $target.closest("a").attr("href");
        if (url) {
            var nextRoommateNumber = parseInt($target.closest(".roommate").attr('data-count')) + 1;
            var roommateId = parseInt($target.closest(".roommate").attr('data-id'));
            //zakomentováno protože spolubydlici nejsou ukladani pro konkrétní nabídku, ale pro vśechny najednou
//            var adId = $(document).find("#roommates").attr('data-adId');
            var $nextRoommate = $(document).find(".roommate[data-count=" + nextRoommateNumber + "]");

            $.ajax({
                url: url,
                type: "post",
                data: {
                    roommate: roommateId
                },
                beforeSend: function () {
                    $("#roommates>.loader").show();
                },
                success: function () {
                    $(document).find(".roommate").removeClass("visible");
                    $nextRoommate.addClass("visible");
                    console.log($nextRoommate);
                    $("#roommates>.loader").hide();
                }
            });
        }
    });

//klik na uživatelovu konverzaci
    $(document).on('click', '.message-item', function () {
        $('.message-item').removeClass('active');
        $(this).addClass('active');
        var link = $(this).find('a').attr('href');
        var lastConversation = link.split('/');
        lastConversation = lastConversation[lastConversation.length - 1];
        showUserConversation(link);
        $('body').find('.messages-conversation').scrollTop(100000000);
        setCookie('lastConversation', lastConversation, 365);
    });

    showUserConversation = function (link) {
        $.ajax({
            url: link,
            success: function (output) {
                var conversationId = parseInt($(output).filter(".messages-conversation-header").attr("data-conversation-id"));
                var userId = parseInt($(output).filter(".messages-conversation-header").attr("data-user-id"));
                $(".messages-conversation-new-message form").attr("data-id", conversationId).attr("data-user-id", userId);
                $(document).find('.messages-conversation-inner').html(output);
                $(document).find('.messages-conversation').scrollTop(100000000);
                checkOnlineUsers();
            }
        });
    };

//odlinkování objektu uživatele
    $(document).on('click', '.object-submenu-deleteObject', function () {
        var link = $(this).find('a').attr('href');
        $.ajax({
            url: link,
            success: function () {
                location.reload();
            }
        });
    });

//zamezení skrolování nad neaktivní zónou když je vyjeté nějaké okno
    $('.scrollable').bind('mousewheel DOMMouseScroll', function (e) {
        if ($('#inactive-area').css('display') != 'none') {
            var e0 = e.originalEvent,
                delta = e0.wheelDelta || -e0.detail;
            this.scrollTop += ( delta < 0 ? 1 : -1 ) * 30;
            e.preventDefault();
        }
    });

    $(document).on("click", ".icon-next", function (e) {
        global.f.listPhoto(e, "next");
    });
    $(document).on("click", ".icon-prev", function (e) {
        global.f.listPhoto(e, "prev");
    });

    $(window).on('resize', function () {
        layout.resizeElements();
    });

    $(document).on('click', '.more-results', function () {
        var offset = $(document).find(".post").length;
        global.filter.f.submit(offset);
    });

    $(document).on('click', '.post', function (e) {
        var postId = $(e.target).closest(".post").attr("data-id");
        for (var i = 0; i < markersArr.length; i++) {
            if (markersArr[i].adId == postId) {
//                markersArr[i].c
            }
        }
    });

    $(document).on("click", '#roommates .icon-remove', function (e) {
        e.stopPropagation();
        $(e.target).closest(".post").find(".really").fadeIn();
    });

    $(document).on("click", ".really .icon-like", function (e) {
        e.stopPropagation();
        e.preventDefault();
        var roommateId = $(e.target).closest(".post").attr("data-id");
        var $loader = $(e.target).closest(".post .loader").show();
        var $really = $(e.target).closest(".really");
        var $post = $(e.target).closest(".post");
        if (roommateId) {
            $.ajax({
                url: global.api.removeRoommate,
                type: "POST",
                beforeSend: function () {
                    $loader.show();
                },
                data: {
                    roommate: roommateId
                },
                success: function () {
                    $loader.hide();
                    $really.fadeOut();
                    $post.css("opacity", 0);
                    setTimeout(function () {
                        $post.remove();
                    }, 500);
                }
            });
        }
    });
    $(document).on("click", ".card-info .icon-dislike, .card-error .icon-dislike", function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $info = $(e.target).closest(".card-info");
        $info.fadeOut();
        var $error = $(e.target).closest(".card-error");
        $error.fadeOut();
    });

    $.extend($.expr[":"], {
        "containsNC": function (elem, i, match, array) {
            return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
    });

    var showUserConversation;
})
;
