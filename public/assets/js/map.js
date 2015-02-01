var map;
var infowindow;
var service;
var eventListenerActive = false;
var inputtedMarker;


$( window ).load(function() {
	initialize().done(getMarkers);

	//EDIT MARKERS
	$("#map-canvas").on('click', 'a.edit', function(){
		var marker_id 	= $(this).data("id");
		$.get('http://localhost:8000/editMarker/'+marker_id, function(data) {
			console.log(data);
		});
		// if (confirm('Are you sure you want to edit this marker?')) {
		//     $.post('http://localhost:8000/editMarker/', {marker_id: marker_id}, function(data) {
		// 		markersArray[marker_id].setMap(null);
		// 		markersArray[0] = null; 
	 //        });
		// } else {
		//     // Do nothing!
		// }
		
	});

	var contactNumberCounter = 1;
	$("#addMoreCN").on('click', function(e) {
		e.preventDefault();

		var newdiv = document.createElement('div');
		newdiv.innerHTML = "Contact Number " + (contactNumberCounter + 1) + " <br><input type='text' name='myContactNumber[]'>";
		document.getElementById('contactNumber').appendChild(newdiv);
		contactNumberCounter++;
	});
	

	//DELETE MARKERS
	$("#map-canvas").on('click', 'a.delete', function(){
		var marker_id 	= $(this).data("id");
		if (confirm('Are you sure you want to delete this marker?')) {
		    $.post('http://localhost:8000/deleteMarker', {marker_id: marker_id}, function(data) {
				markersArray[marker_id].setMap(null);
				markersArray[0] = null; 
	        });
		} else {
		    // Do nothing!
		}
		
	});
	
	
	
		addMarkerListener = google.maps.event.addListener(map, 'click', function(event){
			moveMarker(event.latLng);
			$("#lat").val(inputtedMarker.getPosition().lat());
			$("#lng").val(inputtedMarker.getPosition().lng());


			google.maps.event.addListener(inputtedMarker, 'drag', function(event){
				$("#lat").val(inputtedMarker.getPosition().lat());
				$("#lng").val(inputtedMarker.getPosition().lng());
			});
		});

		$("#addMarkerForm").submit(function(e) {
			e.preventDefault();
			// if($("#lat").val() != "" && $("#lng").val() != "") {
				// var name 				= 	$("#name").val(),
				// 	address 			= 	$("#address").val(),
				// 	type 				= 	$("#type").val(),
				// 	email 				= 	$("#email").val(),
				// 	lat 				= 	$("#lat").val(),
				// 	lng 				= 	$("#lng").val();

				var name 				= 	"",
					address 			= 	"",
					type 				= 	"",
					email 				= 	"",
					lat 				= 	"",
					lng 				= 	"";
				
				$.post('http://localhost:8000/saveMarker', {
						name: name, 
						address: address, 
						type: type, 
						email: email, 
						lat: lat, 
						lng: lng
					}).done( function(data) {
							console.log(data);
							if(data.error == true) {
								var messages = "<ul>";
								$.each(data.messages, function(index, value) {
								    messages += "<li>" + value[0] + "</li>";
								});

								messages += "</ul>";

								displayNotifit( messages , true );
							} else {
								displayNotifit( "Successfully added Marker!" , false );
							}

							// var marker = new google.maps.Marker({
							// 	position: new google.maps.LatLng(lat,lng),
							// 	title:name,
							// 	map:map,
							// 	icon: '{{ URL::to('/') }}/assets/img/'+type+'.png'
							// });	
							// markersArray[data] = marker;

							// google.maps.event.addListener(marker, 'click', (function(marker, data) {
				   //              return function() {
				   //                  infowindow.setContent('<a href="#" data-id="'+data+'">Delete</a>');
				   //                  infowindow.open(map, marker);
				   //              }
				   //          })(marker, data));
				            
							// $("#name").val("");
							// $("#address").val("");
							// $("#type").val("hospital");
							// $("#email").val("");
							// $("#lat").val("");
							// $("#lng").val("");
				            

							// inputtedMarker.setMap(null);
							// inputtedMarker = null;

							// alert(data);
							// console.log(data); 
		        	}).fail(function(){
		        		displayNotifit("Failed to connect to server. Please try again later.", true );
		        	});
			// }


			$("#name").val("");
			$("#address").val("");
			$("#type").val("hospital");
			$("#email").val("");
			$("#latLng").val("");
		});



	
});

function initialize() {
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
	xhr_get("/markers").done(function(data) {
		for (var key in data) {

			if(markersArray.hasOwnProperty(data[key])) {
				markersArray[data[key].rescue_units_id].setPosition(new google.maps.LatLng(data[key].lat,data[key].lng));
				markersArray[data[key].rescue_units_id].setTitle(data[key].name);
				markersArray[data[key].rescue_units_id].setIcon('assets/img/'+data[key].rescue_type+'.png');
			} else {
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng(data[key].lat,data[key].lng),
					title:data[key].name,
					map:map,
					icon: "assets/img/"+data[key].rescue_type+".png"
				});	
				markersArray[data[key].rescue_units_id] = marker;
			}


			google.maps.event.addListener(markersArray[data[key].rescue_units_id], 'click', (function(marker, key) {
                return function() {
                	var units = "";
                	for (var keyContact in data[key].username) {
                		units += ("<li data-id="+keyContact+">"+data[key].username[keyContact]+" ("+data[key].contact_number[keyContact]+")</li>");
                	}
                    infowindow.setContent('<div class="noScrollInfoWindow">'+
                    			'<div><b>'+data[key].name+'</b></div>'+
                    			'<div>'+data[key].address+'</div>'+
                    			'<div class="markerContactNumbers"><i>Contact Number</i><ul>'+units+'</ul></div>'+
                    			'<div class="infoButtons">'+
                    				'<a class="edit btn btn-embossed btn-info btn-xs" href="#" data-id="'+data[key].rescue_units_id+'">Edit</a> '+
                    				'<a class="delete btn btn-embossed btn-danger btn-xs" href="#" data-id="'+data[key].rescue_units_id+'">Delete</a>'+
                    			'</div></div>');
                    infowindow.open(map, marker);
                }
            })(marker, key));



		}
	});
}

function moveMarker( location ) {
	if ( inputtedMarker ) {
		inputtedMarker.setOptions({position: location, animation: google.maps.Animation.DROP});
	} else {
		inputtedMarker = new google.maps.Marker({
			position: location,
			map: map,
			draggable:true,
			animation: google.maps.Animation.DROP
		});
	}
}

function displayNotifit( msg , errorStatus ) {
	var bgcolor;
	if(errorStatus == true) { bgcolor = "#c0392b"; } else {	bgcolor = "#27ae60"; }

	notif({
		msg: msg,
		position: "right",
		bgcolor: bgcolor,
		multiline: true,
		autohide: false
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