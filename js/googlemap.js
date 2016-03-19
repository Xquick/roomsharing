/**
 * Created by Adam on 11.4.14.
 */
var lat = 0;
var lng = 0;
var map;
var latLng;
var geocoder;
var markersArr = new Array();
var addressInput;
var bounds;
var mapObject;
var address;
var autocomplete;

jQuery(function () {
    var lngLatArr = new Array();
    var addressFormated = new Array();
    var infobox;

    mapObject = {

        appendGeocomplete: function () {
            addressInput = $(document).find('.address-input');
            address = addressInput.val();
            addressInput.geocomplete().bind("geocode:result", function (event, result) {
//                console.log(result);
//                alert();
                global.filter.f.submit();
            });

        },
        changeCenter: function (lat, lng) {
            var latLng = new google.maps.LatLng(lat, lng);
            map.setCenter(latLng);
        },
        deleteMarkers: function () {
            this.setAllMap(null);
        },
        fitbounds: function () {
            var bounds = new google.maps.LatLngBounds();
            map.setOptions({maxZoom: 17});
            for (var i = 0; i < markersArr.length; i++) {
                bounds.extend(markersArr[i].getPosition());
            }

            map.fitBounds(bounds);
            map.setOptions({maxZoom: 50});
        },
        getObjectsInBounds: function () {
//        map.setZoom(13);
//            var bounds = map.getBounds();

            var postArr = new Array();
            $(document).find(".marker").each(function () {
                var $post = $(this).closest(".post");
                var adId = parseInt($(this).closest(".post").attr("data-id"));
                var location = {
                    lat: $(this).attr("data-lat"),
                    lng: $(this).attr("data-lng"),
                    adId: adId,
                    post: $post
                };
                postArr.push(location);
            });
            this.deleteMarkers();
            markersArr = [];
            for (var i = 0; i < postArr.length; i++) {
                latLng = new google.maps.LatLng(postArr[i].lat, postArr[i].lng);
                var $content = postArr[i].post.html();
                var marker = new google.maps.Marker({
                    position: latLng,
                    adId: postArr[i].adId,
                    icon: "/images/icons/markerlight.png",
                    content: $content
                });

                google.maps.event.addListener(marker, 'mouseover', function () {
                    $(document).find(".post[data-id=" + this.adId + "]").addClass("selected");
                    this.setIcon = "/images/icons/markerlight.png";
                });

                google.maps.event.addListener(marker, 'mouseout', function () {
                    $(document).find(".post").each(function () {
                        $(this).removeClass("selected");
                        this.setIcon = "/images/icons/marker.png";
                    });
                });

                google.maps.event.addListener(marker, 'click', function () {
                    this.setIcon = "/images/icons/markerlight.png";
//                    global.f.getContent("campaign/" + this.adId, global.e.raw.ad_detail, global.e.wrap, global.e.raw.ad_detail, "bottom");
                    global.f.initPrettyPhoto();
                    infobox.setContent(this.content);
                    infobox.open(map, this);
                    $(document).on("click", ".icon-next", function (e) {
                        global.f.listPhoto(e, "next");
                    });
//                    mapObject.objectOnMap(objectId);
                });

                markersArr.push(marker);
            }
            this.setAllMap(map);
        },
        gotoPlace: function (response, rawAddressFormated) {
            for (var i = 0; i < rawAddressFormated.length; i++) {
                addressFormated[rawAddressFormated[i].types[0]] = rawAddressFormated[i].long_name;
            }
            lat = response.results[0].geometry.location.lat;
            lng = response.results[0].geometry.location.lng;
            var bounds = response.results[0].geometry.viewport;
            var northeastLat = bounds.northeast.lat;
            var northeastLng = bounds.northeast.lng;
            var southwestLat = bounds.southwest.lat;
            var southwestLng = bounds.southwest.lng;
            this.changeCenter(lat, lng);
            this.getObjectsInBounds();
        },
        init: function () {
            latLng = new google.maps.LatLng(lat, lng);
            var mapOptions = {
                center: latLng,
                zoom: 13,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map"), mapOptions);
            geocoder = new google.maps.Geocoder();
            infobox = new InfoBox({
                enableEventPropagation: true,
                pixelOffset: new google.maps.Size(-90, -250),
                disableAutoPan: false
            });
            this.initListeners();
            this.appendGeocomplete();
            this.fitbounds();


            $.ajax({
                url: 'map_c/getLatLng',
                type: "GET",
                data: {address: encodeURI(address)},
                success: function (output) {
                    var output = $.parseJSON(output);
                    console.log(output);
                    if (output.status != "ZERO_RESULTS") {
                        var rawAddressFormated = output.results[0].address_components;
                        mapObject.gotoPlace(output, rawAddressFormated);
                    }
                    mapObject.fitbounds();
                }
            });
        },
        initListeners: function () {

            //TOTO Both following events to one - code clean
            google.maps.event.addListener(map, 'dragend', function () {
                mapObject.getObjectsInBounds();
                if ($("input[name=map-filter]").is(":checked")) {
                    global.filter.f.submit();
                }
            });

            google.maps.event.addListener(map, 'zoom_changed', function () {
                mapObject.getObjectsInBounds();
                if ($("input[name=map-filter]").is(":checked")) {
                    global.filter.f.submit();
                }
            });

            google.maps.event.addListener(map, 'click', function () {
                infobox.close();
            });

            infobox.addListener("domready", function () {
                $(document).on("click", ".infoBox .icon-next", function (e) {
                    global.f.listPhoto(e, "prev");
                });
                $(document).on("click", ".infoBox .icon-next", function (e) {
                    global.f.listPhoto(e, "next");
                });
            });
        },
        objectOnMap: function (objectId) {
            $.get('map_c/getObjectLocation', {object_id: objectId}, function (output) {
                output = JSON.parse(output);
                var lat = output[0]['lat'];
                var lng = output[0]['lng'];
//                mapObject.changeCenter(lat, lng);
                mapObject.showSingleMarker(lat, lng);
            });
        },
        setAllMap: function (map) {
//            console.log(markersArr);
            for (var i = 0; i < markersArr.length; i++) {
//                console.log(markersArr[i]);
                markersArr[i].setMap(map);
            }
        },

        showSingleMarker: function (lat, lng) {
            var latLng = new google.maps.LatLng(lat, lng);
//            this.setAllMap(null);
//            markersArr = [];
            markersArr.push(new google.maps.Marker({
                position: latLng,
                icon: "/images/icons/markerselected.png"
            }));
            this.setAllMap(map);
        }
    };


//sets map on users first offer
    if ($('.user-object').length != 0) {
        $('.user-object:first-child').ready(function () {
            var lat = $('.user-object:first-child').attr('data-lat');
            var lng = $('.user-object:first-child').attr('data-lng');
            mapObject.changeCenter(lat, lng);
            mapObject.showSingleMarker(lat, lng);
        });
    }
})
;
