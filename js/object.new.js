var lat;
var lng;
var objects;
var roomsArr;
var equipment;
var equipmentArr;
var object;
jQuery(function () {
    object = {
        settings: {
            minAge: 18,
            maxAge: 99
        },
        input: {
//section1
            location: null,
            realityType: null,
            adType: null,
            squareArea: null,
            walkthrough: null,
            creatorType: null,
//setion 2
            peopleCount: null,
            roomCount: null,
            sex: null,
            ageFrom: null,
            ageTo: null,
//section 3
            price: null,
            bailBoolean: null,
            bail: null,
//section 4
            available_from: null,
            available_to: null,
//section 5
            equipment: null,
//section 6
            photo: null
        },
        getRooms: function () {
            var arr = new Array();
            var i = 0;
            $('body').find('.object-new-rooms-item').each(function () {
                var checked = $(this).find('.myCheckbox').find('input').is(':checked');
                if (checked) {
                    arr[i] = new object();
                    arr[i].room_id = parseInt($(this).attr('data-id'));
                    arr[i].room_name = $(this).find('.room-item-name').text();
                    arr[i].room_count = $(this).find('.object-new-rooms-count').find('input').val();
                    i++;
                }
            });
            return arr;
        },
        getEquipment: function () {
            var arr = new Array();
            var i = 0;
            $('body').find('.object-new-equipment-item').each(function () {
                var checked = $(this).find('.myCheckbox').find('input').is(':checked');
                if (checked) {
                    arr[i] = parseInt($(this).attr('data-id'));
                    i++;
                }
            });
            return arr;
        }, getValues: function () {
            //Create object
            object.input.location = $(document).find(".address-input").val();
            object.input.realityType = $(document).find("input[name=object_type]:checked").val();
            object.input.adType = $(document).find("input[name=ad_type]:checked").val();
            object.input.squareArea = $(document).find("input[name=object_area]").val();
            object.input.walkthrough = $(document).find("input[name=object_walkthrough]").is(":checked") ? 1 : 0;
            object.input.creatorType = $(document).find("input[name=object_rel]:checked").val();

            //Part after creation of OBJECT
            object.input.peopleCount = $(document).find("select[name=object_people_count]").val();
            object.input.roomCount = $(document).find("select[name=object_room_count]").val();
            object.input.sex = $(document).find("input[name=object_sex]:checked").val();
//            object.input.ageFrom = $(document).find("input[name=object_age]").slider('getValue')[0];
//            object.input.ageTo = $(document).find("input[name=object_age]").slider('getValue')[1];
            object.input.price = $(document).find("input[name=object_price]").val();
            object.input.bailBoolean = $(document).find("input[name=object_bail_bool]").is(":checked") ? 1 : 0;
            object.input.bail = $(document).find("input[name=object_bail]").val();
            object.input.available_from = $(document).find("input[name=object_available_from]").val();
            object.input.available_to = $(document).find("input[name=object_available_to]").val();
            object.input.equipment = JSON.stringify(object.getEquipment());
            object.input.photo = $(document).find("").val();
            return object.input;
        },
        create: function () {
            var data = object.getValues();
            var indicator = $(".object-new-menu ul li[data-id=" + 1 + "]");
            if (object.validate(1, true)) {
                $.ajax({
                    url: global.api.saveObject,
                    type: "GET",
                    data: data,
                    success: function (output) {
                        var idArr = JSON.parse(output);
                        console.log(idArr);
                        var $sectionCreate = $(document).find(".object-section[data-id=" + 1 + "]");
                        $(document).find("#form_object_new");

                        $(document).find("#object-new").attr("data-objectId", idArr.objectId);
                        $(document).find("#object-new").attr("data-adId", idArr.adId);
                        indicator.addClass("success");
                        $(document).find("#form_object_new").hide();
                        $(".object-message-success").show();
                    }
                });
            } else {
                indicator.addClass("fail");
            }
        },
        update: function (adId, sectionId) {
            var data = object.getValues();
            if (object.validate(sectionId, true)) {
                $.ajax({
                    url: global.api.updateAd + adId,
                    type: "POST",
                    data: data,
                    success: function (output) {
                        console.log(output);
                    }
                });
            }
        },
        getSelectedObject: function () {
            var selectedId = $(document).find('#object-showcase').find('.selected').attr('data-id');
            return selectedId;
        },

        getPhotoRooms: function () {
            var photoRooms = new Array();
            $('body').find('.upload-image-preview-item').each(function () {
                photoRooms.push($(this).find('select').val());
            });
            return photoRooms;
        },

        getFrontPhotoId: function () {
            var tmpCounter = 1;
            var frontPhoto = 1;
            $('body').find('.upload-image-preview-item').each(function () {
                var isSelected = $(this).hasClass('selected');
                if (isSelected) {
                    frontPhoto = tmpCounter;
                }
                tmpCounter++;
            });
            return frontPhoto;
        },
        setFrontPhoto: function () {
            $.get('user_api/setFrontPhoto', {gallery_id: galleryId, photo_id: object.getFrontPhotoId()});
        },
        submit: function (output) {
            var returnArr = JSON.parse(output);
            var objectId = returnArr[returnArr.length - 1];
            var photoRooms = new Array();

            $.get('user_api/saveGallery', {object_id: objectId}, function (output) {
                var galleryId = parseInt(output);
                console.log('galleryId: ' + galleryId);
                for (var i = 0; i < photoRooms.length - 1; i++) {
                    $.get('user_api/saveRoomPhoto', {
                        photo_number: i + 1,
                        object_room_id: photoRooms[i],
                        gallery_id: galleryId
                    }, function () {

                    });
                }
            });
            //upload obrázku
            roomsArr = object.getRooms();
            console.log(uploadImage($('body').find('#fileupload'), objectId));
        },
        validateAll: function () {
            var returnVal = 1;
            for (var i = 1; i < 7; i++) {
                returnVal *= this.validate(i, false) ? 1 : 0;
            }
            return returnVal == 1;
        },
        validate: function (sectionId, changeIndicators) {
            var changeIndicators = changeIndicators == "undefined" ? true : changeIndicators;
            var returnVal = false;
            var indicator = $(".object-new-menu ul li[data-id=" + sectionId + "]");

            switch (sectionId) {
                case 1:
                    if (object.input.location
                        &&
                        object.input.realityType &&
                        object.input.adType &&
                        object.input.squareArea &&
                        object.input.creatorType
                        ) {
                        returnVal = true;
                    }
                    break;
                case 2:
                    returnVal = object.input.sex ? true : false;
                    break;
                case 3:
                    returnVal = object.input.price && object.input.price > 0 ? true : false;
                    break;
                case 4:
                    var from = new Date($(document).find("input[name=object_available_from]").val());
                    var to = new Date($(document).find("input[name=object_available_to]").val());

                    if (from.getTime() && to.getTime()) {
                        console.log(from.getTime());
                        console.log(to.getTime());
                        console.log(Number(from.getTime()) < Number(to.getTime()));
                        if (Number(from.getTime()) < Number(to.getTime())) {
                            returnVal = true;
                        } else {
                            returnVal = false;
                        }
                    } else {
                        if (from.getTime()) {
                            returnVal = true;
                        }
                    }

                    if (!from.getTime() && !to.getTime())
                        returnVal = true;
                    break;
                case 5:
                    returnVal = true;
                    break;
                case 6:
                    returnVal = $(document).find(".upload-image-preview .no-results").length ? false : true;
                    break;
            }
            if (changeIndicators) {
                if (!returnVal) {
                    if (sectionId != 1)
                        indicator.removeClass("success");
                    indicator.addClass("fail");
                } else {
                    indicator.removeClass("fail");
                    indicator.addClass("success");
                }
            }
            return returnVal;
        },
        validateField: function ($field) {
            var passed = 0;
            switch ($field.attr("name")) {
                case "object_location" :
                    if ($field.val().length > 5) {
                        passed = 1;
                    } else {
                        passed = -1;
                    }
                    break;
                case "object_area" :
                    if ($.isNumeric($field.val())) {
                        passed = 1;
                    } else {
                        passed = -1;
                    }
                    break;
                case "object_type" :
                    passed = 1;
                    break;
                case "ad_type" :
                    passed = 1;
                    break;
                case "object_rel" :
                    passed = 1;
                    break;
                default:
                    passed = 0;
                    return;
            }
            if (passed == 1) {
                object.showSuccess($field.closest(".object-subsection"));
            }
            if (passed == -1) {
                object.showError($field.closest(".object-subsection"));
            }
        },
        showError: function ($field) {
            $field.removeClass("success");
            $field.addClass("error");
        },
        showSuccess: function ($field) {
            $field.removeClass("error");
            $field.addClass("success");
        },
        hideError: function () {
            $field.removeClass("error");
        },
        hideSuccess: function () {
            $field.removeClass("success");
        }
    };

    //SUBMIT MAIN PART ACTION
    $(document).on("click", "button[name=object_new_submit]", function (e) {
        e.preventDefault();
        object.create();
    });

    //MENU ITEMS SELECTION
    $(document).on("click", ".object-new-menu li", function (e) {
        var currSectionId = parseInt($(".object-new-menu li.active").attr("data-id"));
        var nexSectionId = parseInt($(e.target).attr("data-id"));
        var adId = $(document).find("#object-new").attr("data-adId");
        if (currSectionId != 1 || object.validate(1, true)) {
            $(".object-section").hide();
            $(".object-section[data-id=" + nexSectionId + "]").show();
            $(document).find(".object-new-menu ul li").removeClass("active");
            $(e.target).addClass("active");

            console.log(object.input);

            object.update(adId, currSectionId);
        }
        //if everzthing is validated OK
        if (object.validateAll()) {
            console.log("everything");
        }
    });

    //TOGGLE BAIL
    $(document).on("click", "input[name=object_bail_bool]", function (e) {
        var $bailSubsection = $(document).find(".object-subsection[data-subsection=bail]");
        if ($(e.target).is(":checked")) {
            $bailSubsection.addClass("show");
        } else {
            $bailSubsection.removeClass("show");
            $bailSubsection.find("input").val("");
        }
    });

    //SELECT PHOTO
    $(document).on('click', '.upload-image-preview-item:not(select)', function (e) {
        var target = $(e.target);
        console.log(target);
        if (!target.is('select')) {
            $('body').find('.upload-image-preview-item').removeClass('selected');
            $(this).closest('.upload-image-preview-item').addClass('selected');
        }
    });

    $(document).on("blur", ".form-item > input", function (e) {
        var $input = $(e.target);
        object.validateField($input);
    });


    $(document).on("click", ".object-subsection input[type=radio]", function (e) {
        var $input = $(e.target);
        object.validateField($input);
    });
});
