jQuery(function () {

    //Global filter functions
    global.filter.f = {
        removeTag: function (type) {
            $(".tag-base .tag[data-tag=" + type + "]").remove();
            switch (type) {
                case global.filter.type.price:
                    this.resetPrice();
                    break;
                case global.filter.type.area:
                    $(document).find("input.address-input").text("");
                    break;
                case global.filter.type.objectType:
                    this.resetObjectType();
                    break;
                case global.filter.type.adType:
                    this.resetAdType();
                    break;
                case global.filter.type.sex:
                    this.resetSex();
                    break;
                case global.filter.type.time:
                    $(document).find("input[name=filter_available_from]").val("");
                    $(document).find("input[name=filter_available_to]").val("");
                    break;
                case global.filter.type.equipment:
                    this.resetEquipment();
                    break;
                case global.filter.type.squareArea:
                    $(document).find("input[name=filter_square_area]").val("");
                    break;
                case global.filter.type.bailBoolean:
                    $(document).find("input[name=filter_bail_boolean]").prop("checked", false);
                    break;
            }
        },

        addTag: function (type, text) {
            $(document).find(global.e.raw.filter.tag + "[data-tag=" + type + "]").each(function () {
                $(this).remove();
            });
            var $newTag = $('<div class="tag" data-tag="' + type + '">' + text + '<div class="tag-close">x</div></div>');
            $(document).find(global.e.raw.filter.tag).append($newTag);

            return $newTag;
        },
        getBaseTop: function () {
            if ($(document).find('.tag-base').length)
                global.filter.v.tagBaseTop = $(document).find('.tag-base').position().top;
        },
        resetPrice: function () {
            $(document).find(".price-slider").slider("setValue", [global.filter.settings.min, global.filter.settings.max]);
            var value = $(document).find(".price-slider").slider('getValue');

            $(document).find(".price-slider-from").text(value[0]);
            $(document).find(".price-slider-to").text(value[1]);
        },
        submit: function (offset) {
            var useMap = false;
            var data = {
                filter_area: $(document).find("#filter").find('input[name=filter_area]').val(),
                filter_price_from: parseInt($(".price-slider-from").text()),
                filter_price_to: parseInt($(".price-slider-to").text()),
                filter_object_type: this.getObjectType(),
                filter_ad_type: this.getAdType(),
                filter_sex: this.getSex(),
                filter_time_from: $(document).find("input[name=filter_available_from]").val(),
                filter_time_to: $(document).find("input[name=filter_available_to]").val(),
                filter_equipment: this.getEquipment(),
                filter_square_area: $(document).find("input[name=filter_square_area]").val(),
                filter_bail_boolean: $(document).find("input[name=filter_bail_boolean]").is(":checked") ? 0 : 1
            };
            //if we want load more data
            if (offset) {
                data.offset = offset;
            }

            //if filtering by map movement is allowed, pass geo lovation borders of the map
            if ($(document).find("input[name=map-filter]").is(":checked")) {
                useMap = true;
                var bounds = map.getBounds();
                var NE = bounds.getNorthEast();
                var SW = bounds.getSouthWest();
                data.northeastLat = NE.k;
                data.northeastLng = NE.D;
                data.southwestLat = SW.k;
                data.southwestLng = SW.D;
            }
            $.ajax({
                url: global.api.filter,
                type: "GET",
                data: data,
                beforeSend: function () {
                    $("#discover>.loader").show();
                },
                success: function (output) {
                    $(document).find(".more-results").remove();
                    var noresults = $($.parseHTML(output)).find(".no-results").length ? true : false;
                    console.log($($.parseHTML(output)).find(".no-results").length);
                    var result = $($.parseHTML(output)).find("#discover-objects");
                    if (offset) {
                        if (!noresults) {
                            $(document).find("#discover-objects").append($(result.html()));
                        }
                    } else {
                        $(document).find("#discover-objects").html($(result.html()));
                    }
                    //if filtering by map is OFF, then center map so it shows all results
                    mapObject.getObjectsInBounds();
                    if (!useMap)
                        mapObject.fitbounds();
                    $("#discover>.loader").hide();
                }
            });
        },
        getObjectType: function () {
            var objectTypeArr = new Array();
            $(document).find(".filter-more input[name=filter_object_type]:checked").each(function () {
                objectTypeArr.push($(this).val());
            });
            return objectTypeArr;
        },
        resetObjectType: function () {
            $(document).find(".filter-more input[name=filter_object_type]").each(function () {
                $(this).prop("checked", false);
            });
        },
        getAdType: function () {
            var adTypeArr = new Array();
            $(document).find(".filter-more input[name=filter_ad_type]:checked").each(function () {
                adTypeArr.push($(this).val());
            });
            return adTypeArr;
        },
        resetAdType: function () {
            $(document).find(".filter-more input[name=filter_ad_type]").each(function () {
                $(this).prop("checked", false);
            });
        },
        getSex: function () {
            var sexArr = new Array();
            $(document).find(".filter-more input[name=filter_sex]:checked").each(function () {
                sexArr.push($(this).val());
            });
            return sexArr;
        },
        resetSex: function () {
            $(document).find(".filter-more input[name=filter_sex]").each(function () {
                $(this).prop("checked", false);
            });
        },
        getEquipment: function () {
            var equipmentArr = new Array();
            $(document).find(".filter-more input[name=filter_equipment]:checked").each(function () {
                equipmentArr.push($(this).val());
            });
            return equipmentArr;
        },
        resetEquipment: function () {
            $(document).find(".filter-more input[name=filter_equipment]").each(function (e) {
                $(this).prop("checked", false);
            });
        }
    };

    $(document).on("click", ".filter-more li", function () {
        var source = parseInt($(this).attr('data-filter-source'));

        $(document).find(".filter-more-content-item").each(function () {
            var target = parseInt($(this).attr('data-filter-target'));
            $(this).removeClass("active");
            if (target == source) {
                $(this).addClass("active");
            }
        });

        $(document).find(".filter-more li").each(function () {
            $(this).removeClass("active");
        });
        $(this).addClass("active");
    });

//zobrazení filtru - vyjetí okna
    $(document).on("click", "button[name=filter-more]", function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        //slideToggle the filter extra
        $(document).find('.filter-more').slideToggle(global.filter.settings.openSpeed);


        if ($(document).find('.filter-quick').hasClass("active")) {
            setTimeout(function () {
                $(document).find('.filter-quick').toggleClass("active");
            }, global.filter.settings.openSpeed);
        } else {
            $(document).find('.filter-quick').toggleClass("active");
        }

        setTimeout(function () {
            global.filter.f.getBaseTop();
        }, global.filter.settings.openSpeed + 50);
    });

// AJAX FILTER FORM SUBMIT
    $(document).on("submit", "#filter", function (e) {
        e.preventDefault();
        global.filter.f.submit();
    });

    $(document).on("click", ".tag-close", function (e) {
        var type = $(e.target).closest(global.e.raw.filter.tag).attr("data-tag");
        $(document).find(".price-slider").slider({
            value: [global.filter.settings.min, global.filter.settings.max]
        });
        global.filter.f.removeTag(type);
        $(document).find("#filter").submit();
    });

////////////////// Calling FILTER SUBMIT on multiple events////////////////////////////////////////////////////////////////
    $(document).on("slideStop", ".price-slider", function () {
        var price_from = parseInt($(document).find(".price-slider-from").text());
        var price_to = parseInt($(document).find(".price-slider-to").text());

        $(document).find("#filter").submit();
        global.filter.f.addTag(global.filter.type.price, price_from + " Kč - " + price_to + " Kč");
    });

    $(document).on("change", ".address-input", function () {
        var filter_area = $(document).find("#filter").find('input[name=filter_area]').val();

        $(document).find("#filter").submit();
        global.filter.f.addTag(global.filter.type.area, filter_area);
    });
    $(document).on("change", "input[name=map-filter]", function () {
        var offset = $(document).find(".post").length;
        console.log(offset);
        global.filter.f.submit();
    });
    $(document).on("change", ".filter-more input[type=checkbox], .filter-quick input[type=checkbox]", function (e) {
        $(document).find("#filter").submit();
        var name = $(e.target).attr("name");
        console.log(name);
        switch (name) {
            case "filter_object_type":
                global.filter.f.addTag(global.filter.type.objectType, "Typ pokoje");
                break;
            case "filter_ad_type":
                global.filter.f.addTag(global.filter.type.adType, "Typ nemovitosti");
                break;
            case "filter_sex":
                global.filter.f.addTag(global.filter.type.sex, "Pohlaví");
                break;
            case "filter_time_from":
                global.filter.f.addTag(global.filter.type.time, "Od");
                break;
            case "filter_time_to":
                global.filter.f.addTag(global.filter.type.time, "Do");
                break;
            case "filter_equipment":
                global.filter.f.addTag(global.filter.type.equipment, "Vybavení");
                break;
            case "filter_square_area":
                global.filter.f.addTag(global.filter.type.squareArea, "Rozloha");
                break;
            case "filter_bail_boolean":
                global.filter.f.addTag(global.filter.type.bailBoolean, "Kauce");
                break;
        }
    });

    $(document).on("blur", ".filter-more-content-item input[type=text]", function () {
        global.filter.f.submit();
    });
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    global.filter.f.getBaseTop();

    var scrollTop;
    $(document).find("#wrap").scroll(function () {
        scrollTop = $(document).find("#wrap").scrollTop();
        console.log(scrollTop);
        console.log(global.filter.v.tagBaseTop);
        if (scrollTop >= global.filter.v.tagBaseTop) {
            $(document).find('.tag-base').css("position", "fixed").css("top", 0);
        } else {
            $(document).find('.tag-base').removeAttr("style");
        }
    });
})
;