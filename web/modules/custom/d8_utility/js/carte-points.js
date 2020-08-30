(function ($, Drupal, domready, drupalSettings) {
    'use strict';
    Drupal.behaviors.cartepoints = {
        attach: function (context, settings) {
            var $mapid = $(context).find('#mapid');
            if ($mapid.length) {
                this.initialize(settings);
            }
        },

        initialize: function initialize(settings) {

            var locations = settings.carte.points;
            var mapOptions = {
                zoom: 2,
                maxZoom: 15,
                center: new google.maps.LatLng(48.8534, 2.3488),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                styles: [
                            {
                            "featureType": "landscape",
                            "elementType": "geometry",
                            "stylers": [{
                                "visibility": "off"
                                }]
                            },
                            {
                                "featureType": "landscape",
                                "elementType": "labels",
                                "stylers": [{
                                "visibility": "off"
                                }]
                            },
                            {
                                "featureType": "poi",
                                "elementType": "geometry",
                                "stylers": [{
                                "visibility": "off"
                                }]
                            },
                            {
                                "featureType": "poi",
                                "elementType": "labels",
                                "stylers": [{
                                "visibility": "off"
                                }]
                            },
                            {
                                "featureType": "all",
                                "elementType": "labels.text",
                                "stylers": [{
                                    "visibility": "off"
                                }]
                            },
                            {
                                "featureType": "all",
                                "elementType": "labels.icon",
                                "stylers": [{
                                    "visibility": "off"
                                }]
                            },
                            {
                                "featureType": "administrative.country",
                                "stylers": [{
                                    "visibility": "off"
                                }]
                            },
                            {
                                "featureType": "administrative.country",
                                "elementType": "geometry.stroke",
                                "stylers": [{
                                    "visibility": "off"
                                }]
                            }
                            ,
                            {
                                "featureType": "administrative.province",
                                "elementType": "geometry.stroke",
                                "stylers": [{
                                    "visibility": "off"
                                }]
                            }
                            ,
                            {
                                "featureType": "administrative.locality",
                                "elementType": "geometry.stroke",
                                "stylers": [{
                                    "visibility": "off"
                                }]
                            }
                            ,
                            {
                                "featureType": "administrative.neighborhood",
                                "elementType": "geometry.stroke",
                                "stylers": [{
                                    "visibility": "off"
                                }]
                            }
                        ]
            };
            //map.setMapTypeId(google.maps.MapTypeId['TERRAIN']);
            var map = new google.maps.Map(document.getElementById("mapid"),  mapOptions);
            this.setMarkers(map, locations);
            

        },

        setMarkers: function setMarkers(map,locations){
            var markers = [];
            for (var i = 0; i < locations.length; i++) {

                var location = locations[i];
                var infoWindow = new google.maps.InfoWindow();

                var latLng = new google.maps.LatLng(location.latitude, location.longitude);
                var marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    title: location.nom,
                    icon : {
                        size : new google.maps.Size(52, 52),
                        url : location.picto
                    }
                });
                markers.push(marker);
                //Attach click event to the marker.
                (function (marker, location) {
                    google.maps.event.addListener(marker, "click", function (e) {
                        //Wrap the content inside an HTML DIV in order to set height and width of InfoWindow.
                        map.setCenter(marker.getPosition());
                        //location.popincolor
                        infoWindow.setContent("<div class='barCarte' style='background-color:#" + location.popincolor + "'></div><div class='info_location'>" + location.description + "</div>");
                        infoWindow.open(map, marker);
                    });
                })(marker, location);

            }
            var markerCluster = new MarkerClusterer(map, markers, {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
        }
    };

})(jQuery, Drupal, domready, drupalSettings);