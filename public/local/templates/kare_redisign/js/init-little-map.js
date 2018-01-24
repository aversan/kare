'use strict';

var map;
var mapStyles = [
	{
		"elementType": "geometry",
		"stylers": [
			{
				"color": "#f5f5f5"
			}
		]
	},
	{
		"elementType": "labels.icon",
		"stylers": [
			{
				"visibility": "off"
			}
		]
	},
	{
		"elementType": "labels.text.fill",
		"stylers": [
			{
				"color": "#616161"
			}
		]
	},
	{
		"elementType": "labels.text.stroke",
		"stylers": [
			{
				"color": "#f5f5f5"
			}
		]
	},
	{
		"featureType": "administrative.land_parcel",
		"elementType": "labels.text.fill",
		"stylers": [
			{
				"color": "#bdbdbd"
			}
		]
	},
	{
		"featureType": "poi",
		"elementType": "geometry",
		"stylers": [
			{
				"color": "#eeeeee"
			}
		]
	},
	{
		"featureType": "poi",
		"elementType": "labels.text.fill",
		"stylers": [
			{
				"color": "#757575"
			}
		]
	},
	{
		"featureType": "poi.park",
		"elementType": "geometry",
		"stylers": [
			{
				"color": "#e5e5e5"
			}
		]
	},
	{
		"featureType": "poi.park",
		"elementType": "labels.text.fill",
		"stylers": [
			{
				"color": "#9e9e9e"
			}
		]
	},
	{
		"featureType": "road",
		"elementType": "geometry",
		"stylers": [
			{
				"color": "#ffffff"
			}
		]
	},
	{
		"featureType": "road.arterial",
		"elementType": "labels.text.fill",
		"stylers": [
			{
				"color": "#757575"
			}
		]
	},
	{
		"featureType": "road.highway",
		"elementType": "geometry",
		"stylers": [
			{
				"color": "#dadada"
			}
		]
	},
	{
		"featureType": "road.highway",
		"elementType": "labels.text.fill",
		"stylers": [
			{
				"color": "#616161"
			}
		]
	},
	{
		"featureType": "road.local",
		"elementType": "labels.text.fill",
		"stylers": [
			{
				"color": "#9e9e9e"
			}
		]
	},
	{
		"featureType": "transit.line",
		"elementType": "geometry",
		"stylers": [
			{
				"color": "#e5e5e5"
			}
		]
	},
	{
		"featureType": "transit.station",
		"elementType": "geometry",
		"stylers": [
			{
				"color": "#eeeeee"
			}
		]
	},
	{
		"featureType": "water",
		"elementType": "geometry",
		"stylers": [
			{
				"color": "#c9c9c9"
			}
		]
	},
	{
		"featureType": "water",
		"elementType": "labels.text.fill",
		"stylers": [
			{
				"color": "#9e9e9e"
			}
		]
	}
];
var long = +detailCoordinates.COORDINATES.long;
var lat = +detailCoordinates.COORDINATES.lat;
var address = detailCoordinates.ADDRESS;
var showroomPreviewPicture = detailCoordinates.PIC_SRC;
var name = detailCoordinates.NAME;

var showrooms = [
	[name ,lat, long, 1],
];
var infoWindowContent = [
	['<article class="map-marker-info js-map-marker">' +
	'<div class="map-marker-info__left">' +
	'<img class="map-marker-info__icon" src="' + showroomPreviewPicture + '">' +
	'</div>' +
	'<div class="map-marker-info__right">' +
	'<p class="map-marker-info__address">' +
	'<span></span>' +
	'<span>' + address + '</span>' +
	'</p>' +
	'</div>' +
	'</article>']
];

function initLittleMap() {
	map = new google.maps.Map(document.getElementById('showroom-detail-map'), {
		center: {lat: lat, lng: long},
		zoom: 15,
		disableDefaultUI: true,
		zoomControl: false,
		zoomControlOptions: {
			position: google.maps.ControlPosition.LEFT_BOTTOM
		},
		stylers: [
			{
				lightness:0
			}
		]
	});
	map.setOptions({styles: mapStyles});

	map.data.setStyle(function(feature) {
		return {
			title: feature.getProperty('name'),
			optimized: false
		};
	});
	setMarkers();

	google.maps.event.addDomListener(window, "resize", function() {
		var center = map.getCenter();
		google.maps.event.trigger(map, "resize");
		map.setCenter(center);
	});
}
function setMarkers() {
	var image = {
		url: '/local/templates/kare_new/icons/map-marker.png',
		origin: new google.maps.Point(0, 0)
	};
	for (var i = 0; i < showrooms.length; i++) {
		var showroom = showrooms[i];
		var marker = new google.maps.Marker({
			position: {lat: showroom[1], lng: showroom[2]},
			map: map,
			icon: image,
			title: showroom[0],
			zIndex: showrooms[3]
		});
		var infoWindow = new google.maps.InfoWindow(), marker, i;
			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
						infoWindow.setContent(infoWindowContent[i][0]);
						infoWindow.open(map, marker);
						$('.js-map-marker').closest('div').css('overflow', '');
				}
		})
	(marker, i));
	}
}