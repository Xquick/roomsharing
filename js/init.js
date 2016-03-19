var global;

jQuery(function ($) {
    //literal containing globally used stuff
    global = {
        api: {
            saveObject: "/ad_c/saveObject/",
            filter: "/site_c/filter/",
            updateAd: "/ad_c/updateAd/",
            follow: "/user_c/follow/",
            unfollow: "/user_c/unfollow/",
            makeRoommate: "/user_api/likeRoommate",
            dennyRoommate: "/user_api/dislikeRoommate",
            acceptRoommate: "/user_api/acceptRoommate",
            rejectRoommate: "/user_api/dennyRoommate",
            removeRoommate: "/user_api/removeRoommate"
        },
        f: {
            /*
             * Switches pictures in posts
             * @param {Event} e
             * @param {String} direction - "prev" || "next"
             * */
            listPhoto: function (e, direction) {
                if (direction != "prev" && direction != "next")
                    return false;

                var $post = $(e.target).closest(".post");

                var $tmpAppend = $post.find(".post-photo-inner .more-images");
                var $image = $post.find(".post-photo-inner>img");
                if (!$tmpAppend.find("img").length) {
                    var photo = $post.find(".post-photo-inner>img").attr("src");
                    var adId = parseInt($post.attr("data-id"));
                    var objectId = parseInt($post.attr("data-object-id"));
                    if (adId) {
                        $.ajax({
                            url: "/ad_c/getGallery/" + adId,
                            beforeSend: function () {
                                $(e.target).closest(".post").find(".loader").show();
                            },
                            success: function (output) {
                                var gallery = JSON.parse(output);
                                var gallerySize = Object.keys(gallery).length;

                                //if there is more then 1 image in gallery, continue
                                if (gallerySize > 1) {
                                    var loaded = 0;
                                    var count = 0;
                                    for (var key in gallery) {
                                        count++;
                                        $(e.target).closest();
                                        $tmpAppend.append('<img data-id="' + count + '" src="/galleries/' + objectId + '/_thumbs/' + gallery[key] + '"/>');
                                    }
                                    //count loaded images and hide AJAX LOADR after they are all loaded
                                    $post.find(".more-images img").load(function () {
                                        loaded++;
                                        if (loaded == gallerySize) {
                                            $post.find(".loader").hide();
                                            if (direction == "prev") {
                                                var $nextImage = $post.find(".more-images img[data-id=2]");
                                            }
                                            if (direction == "next") {
                                                var $nextImage = $post.find(".more-images img[data-id=" + count + "]");
                                            }
                                            $nextImage.addClass("flag");
                                            $image.attr("src", $nextImage.attr("src"));
                                        }
                                    });
                                }
                            }
                        });
                    }
                    //if there are already images loaded, there is no need to load them again
                } else {
                    var nextImageId = parseInt($post.find(".flag").attr("data-id"));
                    var maxId = $post.find(".more-images img").length;
                    direction == "prev" ? nextImageId++ : nextImageId--;

                    nextImageId > maxId ? nextImageId = 1 : nextImageId;
                    nextImageId <= 0 ? nextImageId = maxId : nextImageId;

                    var $nextImage = $post.find(".more-images img[data-id=" + nextImageId + "]");
                    console.log(nextImageId);
                    $post.find(".more-images img").each(function () {
                        $(this).removeClass("flag");
                    });
                    $nextImage.addClass("flag");
                    $image.attr("src", $nextImage.attr("src"));
                }
            }
        },
        e: {
            wrap: $("#wrap"),
            chat: {
                appended: $(document).find('.current-chat-content')
            },
            filter: {
                filter: $(document).find('#user-filter'),
                filter_form: $(document).find("#filter"),
                filter_more_section_item: $(document).find(".filter-more li"),
                filter_more_content_item: $(document).find(".filter-more-content-item"),
                slider: "",
                slider_price: $(document).find(".price-slider"),
                slider_price_from: $(document).find(".price-slider-from"),
                slider_price_to: $(document).find(".price-slider-to"),
                filter_quick: $(document).find('.filter-quick'),
                filter_more: $(document).find('.filter-more'),
                tag_base: $(document).find('.tag-base'),
                tag: $(document).find('.tag'),
                tag_close: $(document).find('.tag-close')
            },
            post: $(document).find('.post'),
            user_followed: $(document).find('.user-followed'),
            post_new_icon: $('.object-submenu-newAd'),
            button_close: $(document).find('.button-close'),
            object_new: $("#object-new"),
            ad_new: $(document).find('#object-ad-new'),
            ad_detail: $(document).find('#ad-detail'),
            address_input: $(document).find('.address-input'),
            objects_results: $(document).find("#discover-objects"),
            inactiveArea: $(document).find('#inactive-area'),
            raw: {
                chat: {
                    appended: '.current-chat-content'
                },
                filter: {
                    tag: '.tag',
                    tag_close: '.tag-close'
                },
                button_close: '.button-close',
                wrap: "#wrap",
                post: '.post',
                ad_detail: '#ad-detail'
            }
        },
        filter: {
            settings: {
                min: 0,
                max: 25000,
                step: 200,
                openSpeed: 300
            },
            type: {
                price: "price",
                objectType: "object_type",
                adType: "ad_type",
                sex: "sex",
                time: "time",
                equipment: "equipment",
                squareArea: "square_area",
                bailBoolean: "bail_boolean"
            },
            v: {

            },
            f: {

            }
        }
    };

    global.f.initPrettyPhoto = function () {
        setTimeout(function () {
            $(document).find("a[rel^='prettyPhoto']").prettyPhoto();
        }, 400);
    };


    global.f.initPrettyPhoto();

    $(document).on('append', '#user-filter', function () {
        $(document).find('#filter-available-from').datepicker({minDate: 0});
        $(document).find('#filter-available-to').datepicker({minDate: 0});
    });

    global.f.initPriceSlider = function () {
        if ($(document).find(".price-slider").length) {

            var value = {
                from: parseInt($(document).find(".price-slider").attr("data-price-from")) != 0 ? $(document).find(".price-slider").attr("data-price-from") : global.filter.settings.min,
                to: parseInt($(document).find(".price-slider").attr("data-price-to")) != 0 ? $(document).find(".price-slider").attr("data-price-to") : global.filter.settings.max
            };
            global.e.filter.slider = $(document).find(".price-slider").slider({
                tooltip: 'hide',
                range: true,
                value: [value.from, value.to],
                min: global.filter.settings.min,
                max: global.filter.settings.max,
                step: global.filter.settings.step
            });
            var value = $(document).find(".price-slider").slider('getValue');

            $(document).find(".price-slider-from").text(value[0]);
            $(document).find(".price-slider-to").text(value[1]);
            $(document).find(".price-slider").on("slide", function () {

                value = $(document).find(".price-slider").slider('getValue');

                $(document).find(".price-slider-from").text(value[0]);
                $(document).find(".price-slider-to").text(value[1]);
            });
        }
    };

    global.f.initPriceSlider();

    global.f.pleaseLogin = function () {
        $(".please-login").show();
    };

    global.f.getContent = function (url, appendWhat, appendTo, element, direction, callback, loader) {
        direction = typeof direction !== 'undefined' ? direction : 'right';
        $.ajax({
            url: url,
            beforeSend: function () {
                if (loader)
                    loader.show();
            },
            success: function (output) {
                if ($(output).find("#login").length) {
                    global.f.pleaseLogin();
                } else {

                    var content = $(output).find(appendWhat)[0];
                    if (typeof content == 'undefined') {
                        content = $(output);
                    } else {
                        content = content.outerHTML;
                    }
                    $(appendWhat).remove();
                    if ($(appendWhat).length == 0) {
                        appendTo.append(content);
                        layout.resizeElements();
                        mapObject.appendGeocomplete();
                        switch (direction) {
                            case 'right':
                                $('body').find(element).animate({right: 300});
                                break;
                            case 'left':
                                $('body').find(element).animate({left: 85});
                                break;
                            case 'bottom':
                                $('body').find(element).css('top', (parseInt($(window).innerHeight())));
                                $('body').find(element).animate({top: 0});
                                break;
                            case 'top':
                                $('body').find(element).css('top', 0);
                                $('body').find(element).animate({top: element.outerHeight});
                                break;
                        }
                    }
                    if (callback) {
                        callback();
                    }
                }
                if (loader)
                    loader.hide();
            }
        });
    };

    global.f.hideElement = function (inactiveArea, element, direction) {
        direction = typeof direction !== 'undefined' ? direction : 'right';
        var zindex;
        if (inactiveArea != null) {
            zindex = parseInt(inactiveArea.css('z-index'));
        } else {
            zindex = 0;
        }
        switch (direction) {
            case 'right':
                element.animate({right: parseInt(element.css('right'), 0) - element.outerWidth() - 5}, {
                    complete: function () {
                        element.remove();
                    }
                });
                break;
            case 'left':
                element.animate({left: parseInt(element.css('left'), 0) - element.outerWidth() - 5}, {
                    complete: function () {
                        element.remove();
                    }
                });
                break;
            case 'bottom':
                console.log(element);

                element.animate({top: parseInt(window.innerHeight)}, {
                    complete: function () {
                        element.remove();
                    }
                });
                break;
            case 'top':
                element.animate({top: -parseInt(element.outerHeight)}, {
                    complete: function () {
                        element.remove();
                    }
                });
                break;
        }
        if (inactiveArea != null) {
            zindex -= 2;
            inactiveArea.css('z-index', zindex);
            if (zindex < 10) {
                inactiveArea.hide();
                inactiveArea.css('z-index', 9);
            }
        }
    };

    global.f.addFavorite = function ($post) {
        var adId = parseInt($post.attr("data-id"));
        if (!isNaN(adId)) {
            $post.find(".post-icon-right").toggleClass("icon-follow icon-unfollow");
            console.log($post);
            $post.find(".post-icon-right>a").attr("href", global.api.unfollow + "/" + adId);
            var $postClone = $post.clone();
            $postClone.width($post.width()).height($post.height()).css("position", "fixed").css("top", $post.position().top).css("left", $post.position().left).css("z-index", "999999");
            $("body").append($postClone);
            $postClone.addClass("scaleDown", 10);
        }
    };

    global.f.removeFavorite = function ($post) {
        var adId = parseInt($post.attr("data-id"));
        if (!isNaN(adId)) {
            $post.find(".post-icon-right").toggleClass("icon-follow icon-unfollow");
            console.log($post);
            $post.find(".post-icon-right>a").attr("href", global.api.follow + "/" + adId);
            $post.addClass("discart", 10);
        }
    };

    global.f.initPriceSlider();


    //init google map
    if (typeof(google) != 'undefined') {
        mapObject.init();
    }

    if (typeof (address) == 'undefined' || address.length == 0) {
        address = 'praha';
    }

});

(function ($) {
    var origAppend = $.fn.append;

    $.fn.append = function () {
        return origAppend.apply(this, arguments).trigger("append");
    };
})(jQuery);
