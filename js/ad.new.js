$(document).ready(function () {

    $('button[name=ad-new-general]').click(function () {
//        $.post('user_c/saveTmpRooms', {tmpRooms: JSON.stringify(roomsArr)}, function (output) {
//            console.log(output);
//        });
        nextFormPart('.new-step', 'button[name=ad-new-general]', 1);
    });

    $('button[name=ad-new-timePeople]').click(function () {
//        $.post('user_c/saveTmpRooms', {tmpRooms: JSON.stringify(roomsArr)}, function (output) {
//            console.log(output);
//        });
        nextFormPart('.new-step', 'button[name=ad-new-timePeople]', 1);
    });

    $(document).on('click', 'button[name=ad-new-select]', function () {
        nextFormPart('.new-step', 'button[name=ad-new-select]', 1);
    });

    $(document).on('click', 'button[name=ad-new-rooms]', function () {
        nextFormPart('.new-step', 'button[name=ad-new-rooms]', 1);
    });

    $(document).on('click', 'button[name=ad-new-general]', function () {
        nextFormPart('.new-step', 'button[name=ad-new-general]', 1);
    });

    $(document).on('click', 'button[name=ad-new-submit]', function () {
        var objectId = $('#object-ad-new').attr('data-id');
        var roomsArr = new Array();
        var separate = $('input[name=ad-new-select-type]:checked').val();
        console.log(separate);
        if (separate == 1) {
            $('body').find('#ad-new-room .ad-new-room').each(function () {
                if ($(this).css('display') == 'block') {
                    var room = new Object();
                    var object_room_id = parseInt($(this).attr('data-object_room_id'));
                    room.objectId = parseInt($(this).attr('data-id'));
                    room.price = $(this).find('input[name=ad-roomPrice]').val();
                    room.availableFrom = $(this).find('input[name=ad-availableFrom]').val();
                    room.availableTo = $(this).find('input[name=ad-availableTo]').val();
                    room.title = $('#ad-new-room-description .ad-new-room-' + object_room_id).find('input[name=ad-title]').val();
                    room.body = $('#ad-new-room-description .ad-new-room-' + object_room_id).find('textarea[name=ad-body]').val();
                    roomsArr.push(room);
                }
            });
            for (var i = 0; i < roomsArr.length; i++) {
                console.log(i);
                var data = {
                    ad_objectId: objectId,
                    ad_availableFrom: roomsArr[i].availableFrom,
                    ad_availableTo: roomsArr[i].availableTo,
                    ad_price: roomsArr[i].price,
                    ad_title: roomsArr[i].title,
                    ad_body: roomsArr[i].body
                };
                $.ajax({
                    url: 'ad_c/saveAd',
                    type: 'GET',
                    data: data,
                    async: false,
                    success: function (output) {
                        console.log(output);
                    }
                });
            }
        } else {
            var price = $('body').find('input[name=ad-priceAll]').val();
            var availableFrom = $('body').find('input[name=ad-availableAllFrom]').val();
            var availableTo = $('body').find('input[name=ad-availableAllTo]').val();
            var title = $('body').find('input[name=ad-title]').val();
            var body = $('body').find('textarea[name=ad-body]').val();
            $.get('ad_c/saveAd', {
                ad_objectId: objectId,
                ad_availableFrom: availableFrom,
                ad_availableTo: availableTo,
                ad_price: price,
                ad_title: title,
                ad_body: body
            }, function (output) {
                console.log(output);
            });
        }

//        console.log(price);
//        console.log(availableFrom);
//        console.log(title);
//        console.log(body);
    });

    $(document).on('click', '.ad-new-select-room', function () {
        var roomId = $(this).closest('.ad-new-room').attr('data-object_room_id');
        var room = $('body').find('.ad-new-room-' + roomId);

        if ($(this).is(':checked')) {
            room.show();
        } else {
            room.hide();
        }
    });


    $(document).on('click', 'input[name=ad-new-select-type]', function () {
        var type = parseInt($(this).val());
        var aroomsAll = $('.ad-new-roomsAll');

        var rooms = $('#ad-new-select-rooms');
        if (type == 1) {
            rooms.show();
            aroomsAll.hide();

        } else {
            $('#ad-new-select-rooms input:checkbox').each(function () {
                $(this).attr('checked', false);
                var roomId = $(this).closest('.ad-new-room').attr('data-object_room_id');
                var room = $('body').find('.ad-new-room-' + roomId);
                room.hide();
            });
            aroomsAll.show();
            rooms.hide();
        }

    });
});