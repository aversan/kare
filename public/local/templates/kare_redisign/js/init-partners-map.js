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
		"featureType": "administrative.locality",
		"stylers": [
			{
				"visibility": "on"
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

function initPartnersMap() {
	map = new google.maps.Map(document.getElementById('partners-map'), {
		center: {lat:55, lng: 65},
		zoom: 4,
		disableDefaultUI: true,
		scrollwheel: false,
		zoomControl: true,
		zoomControlOptions: {
			position: google.maps.ControlPosition.RIGHT_CENTER
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
var markers = {};

function setMarkers() {
	var image = {
		url: '/local/templates/kare_new/icons/map-marker-gray.png',
		origin: new google.maps.Point(0, 0)
	};
	var imageActive = {
		url: '/local/templates/kare_new/icons/map-marker.png',
		origin: new google.maps.Point(0, 0)
	};
	for (var i = 0; i < showrooms.length; i++) {
		var showroom = showrooms[i];
		var long = +showroom.COORDINATES.long;
		var lat = +showroom.COORDINATES.lat;

		var marker = new google.maps.Marker({
			position: {lat: lat, lng: long},
			map: map,
			icon: image,
			title: showroom.NAME,
			id: showroom.ID,
			region: showroom.REGION
		});

		markers[showroom.ID] = marker;

		$('.js-partner[data-id='+showroom.ID+']').on('click', function(){
			for (var key in markers) {
				markers[key].setIcon(image);
			}
			var currentID = $(this).attr('data-id');
			markers[currentID].setIcon(imageActive);
			map.setZoom(15);
			map.setCenter(markers[currentID].position);

		});
	}

	$('.js-choose-region').on('click', function(){
		var region = $(this).attr('data-region');
		$('.js-partner').hide();
		$('.js-partner[data-region='+region+']').show();
		$('.js-hidden-part').attr('style', '');
		$('.js-caret').removeClass('active');
		var k;
		for (var key in markers) {
			markers[key].setVisible(false);

			if (markers[key].region == region) {
				markers[key].setVisible(true);
				if(k!==1) {
					map.setZoom(10);
					map.setCenter(markers[key].position);
					k = 1;
				}
			}
		}
	});
}

$('.js-partner').on('click', function(){
	if(!$(this).hasClass('active')) {
		$('.js-partner').removeClass('active');
		$(this).addClass('active');
	}
});
$('.js-caret').on('click', function(){
	$(this).siblings('.js-hidden-part').slideToggle();
	$(this).toggleClass('active');
});


