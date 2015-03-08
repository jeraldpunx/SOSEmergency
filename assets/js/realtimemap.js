var INTERVAL = "5000";
var count = INTERVAL.substring(0, INTERVAL.length - 3);

var counter = setInterval(timer, 1000); //1000 will  run it every 1 second

function timer() {
	count = count - 1;
	if (count <= 0) {
		document.getElementById("timer").innerHTML = "Loading...";
		return;
	}
	document.getElementById("timer").innerHTML = "Updating in " + count;
}

var map, 
infowindow;
var report = {};	

$( window ).load(function() {
	initialize().done(getMarkers);

	function renderDirections(result, infoDetails, colorType) {
		var rendererOptions = {
			map: map,
			preserveViewport: true, 
			suppressMarkers: true,
			polylineOptions: {
				strokeColor: colorType
			}
		};
		var directionsRenderer = new google.maps.DirectionsRenderer(rendererOptions);
		directionsRenderer.setDirections(result);
		var iconUrl 	= 	"https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=|"+infoDetails.color_hex;
		var routeLegs 	= 	result.routes[0].legs[0];

		var marker = new google.maps.Marker({
			position: routeLegs.start_location,
			map: map,
			icon: {url: iconUrl},
		});

		var infowindow = new google.maps.InfoWindow({
			content: "<div>" +
			"<div><b>" + infoDetails.description + "</b></div>" +
			"<div><b><small>" + routeLegs.start_address + "</small></b></div>" +
			"<small>Date Reported: "+infoDetails.date_reported+"</small>" +
			"<div>" + infoDetails.pu_name + " <small>(" + infoDetails.gender + ") " + infoDetails.birth_date + "</small></div>" +
			"<div><small>" + infoDetails.pu_contact_number + " (" + infoDetails.pu_email + ")</small></div>" +
			"</div>"
		});
		google.maps.event.addListener(marker, 'click', function(){ //when the marker on map is clicked open info-window
			infowindow.open(map, marker);
		});
	}

	var directionsService = new google.maps.DirectionsService;
	function requestDirections(infoDetails, colorType) {
		directionsService.route({
			origin: new google.maps.LatLng(infoDetails.r_lat, infoDetails.r_lng),
			destination: new google.maps.LatLng(infoDetails.ru_lat, infoDetails.ru_lng),
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		}, function(result) {
			renderDirections(result, infoDetails, colorType);
		});
	}

	function getLiveReports(currentDateTime) {
		xhr_get("getlivereports/"+currentDateTime).done( function(infoDetails){
			for (var key in infoDetails) {
				if(report.hasOwnProperty(infoDetails[key].r_id)) {
					if(report[infoDetails[key].r_id] == 'ok' && infoDetails[key].date_responded != null) {
						report[infoDetails[key].r_id] = "responded";
						requestDirections(infoDetails[key], "#e74c3c");
						var reportText = infoDetails[key].date_responded + " : "+ infoDetails[key].ru_name.toUpperCase() + " responding " + infoDetails[key].pu_name.toUpperCase() + " request ("+ infoDetails[key].description.toUpperCase()+").";
						$("#logdata").append(reportText + "&#10;");
					}
				} else {
					requestDirections(infoDetails[key], "#3498db");
					var reportText = infoDetails[key].date_reported + " : " + infoDetails[key].pu_name.toUpperCase() +" reporting a "+ infoDetails[key].description.toUpperCase()+" to "+infoDetails[key].ru_name.toUpperCase()+".";
					$("#logdata").append(reportText+"&#10;");
					report[infoDetails[key].r_id] = 'ok';
				}
			}
			count = INTERVAL.substring(0, INTERVAL.length - 3);
		});
		window.setTimeout( function() { 
			getLiveReports(currentDateTime); 
		},INTERVAL);
	}

	xhr_get("getcurrenttime").done( function(date){
		var currentDateTime 	= 	date;
		getLiveReports(currentDateTime);
	});

	function getReportQueue() {
		xhr_get("reportqueue").done( function(data){
			console.log(data);
		});
		window.setTimeout( getReportQueue, 5000);
	}

	xhr_get("getcurrenttime").done( function(date){
		getReportQueue();
	});
});

function initialize() {
	geocoder = new google.maps.Geocoder();
	var myStyles =[
		{
			featureType: "transit.station.bus",
			elementType: "labels",
			stylers: [
				{ visibility: "off" }
			]
		}
	];

	var mapOptions = {
	center: new google.maps.LatLng(10.3156990, 123.8854370),
	zoom: 12,
	mapTypeControl: false,
	streetViewControl: false,
	styles: myStyles,
	panControl: true,
	panControlOptions: {
		position: google.maps.ControlPosition.TOP_RIGHT
	},
	zoomControl: true,
	zoomControlOptions: {
		style: google.maps.ZoomControlStyle.LARGE,
		position: google.maps.ControlPosition.TOP_LEFT
	}
	};
	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	infowindow = new google.maps.InfoWindow({
	maxWidth: 200
	});
	//Used to remember markers
	markersArray = {};

	//remove infoWindow when clicking map
	google.maps.event.addListener(map, 'click', function() {
		infowindow.close();
	});

	return $.Deferred().resolve();
}

function getMarkers() {
	xhr_get("markers").done(function(data) {
		for (var key in data) {
			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(data[key].lat,data[key].lng),
				title:data[key].name,
				map:map,
				icon: "assets/img/"+data[key].rescue_type+".png"
			});	
			markersArray[data[key].rescue_units_id] = marker;

			google.maps.event.addListener(markersArray[data[key].rescue_units_id], 'click', (function(marker, key) {
				return function() {
					infowindow.setContent('<div class="noScrollInfoWindow">'+
						'<div><b>'+data[key].name+'</b></div>'+
						'<div>'+data[key].address+'</div>'+
						'<div>'+data[key].email+'</div>'+
						'</div>');
					infowindow.open(map, marker);
				}
			})(marker, key));
		}
	});
}


function xhr_get(url) {
	return $.ajax({
		url: url,
		type: 'get',
		dataType: 'json',
	})
	.always(function() {
		// remove loading image maybe
	})
	.fail(function() {
		// handle request failures
	});
}