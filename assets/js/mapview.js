var map, 
	geocoder,
	infowindow,
	service,
	eventListenerActive = false,
	inputtedMarker;

$( window ).load(function() {
	initialize().done(getMarkers);


	//MARKER LISTENER
	addMarkerListener = google.maps.event.addListener(map, 'click', function(event){
		moveMarker(event.latLng);
		var lat = inputtedMarker.getPosition().lat(),
			lng = inputtedMarker.getPosition().lng();


		geocoder.geocode({'latLng': new google.maps.LatLng(lat, lng)}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
			if (results[1])
				$("#address").val(results[0].formatted_address);
		  } else {
			displayNotifit( "Geocoder failed due to: " + status , true );
		  }
		});

		$("#lat").val(lat);
		$("#lng").val(lng);

		//reset values per click
		$("#formTitle").html("Add Marker");
		$("#rescue_units_id").val("");
		$("#name").val("");
		$("#address").val("");
		$("#email").val("");
		$("#type").val("hospital");
	

		google.maps.event.addListener(inputtedMarker, 'drag', function(event){
			
			$("#lat").val(inputtedMarker.getPosition().lat());
			$("#lng").val(inputtedMarker.getPosition().lng());

			//reset values per drag
			$("#formTitle").html("Add Marker");
			$("#rescue_units_id").val("");
			$("#name").val("");
			$("#address").val("");
			$("#email").val("");
			$("#type").val("hospital");
		});

		google.maps.event.addListener(inputtedMarker, 'dragend', function(event){
			var lat = inputtedMarker.getPosition().lat(),
				lng = inputtedMarker.getPosition().lng();

			geocoder.geocode({'latLng': new google.maps.LatLng(lat, lng)}, function(results, status) {
			  if (status == google.maps.GeocoderStatus.OK) {
				if (results[1])
					$("#address").val(results[0].formatted_address);
			  } else {
				displayNotifit( "Geocoder failed due to: " + status , true );
			  }
			});
		});
	});

	//DELETE MARKERS
	$("#map-canvas").on('click', 'a.delete', function(e){

		var marker_id 	= $(this).data("id"),
			marker_name	= $(this).data("name");

		if(confirm("Delete marker: "+marker_name.toUpperCase()+"?")) {
			$.post('deleteMarker', {marker_id: marker_id}, function(data) {
				displayNotifit( "Successfully deleted marker!" , false );
				markersArray[marker_id].setMap(null);
				markersArray[marker_id] = null;
			});
		}
	});

	//CONTACTS MARKERS
	$("#map-canvas").on('click', 'a.contacts', function(){
		var marker_id 	= $(this).data("id");

		//ADD CONTACT
		$("#addNewContact").unbind("click").on('click', function() {
			var add_contact_number 	= 	prompt("Input new contact number");
			if(add_contact_number) {
				$.post('addContact', {
					ru_id 			: 	marker_id,
					contact_number 	: 	add_contact_number
				}).done( function(data) {
					if(data.error == true) {
						var messages = "";
							messages += "<strong>Failed to add marker</strong><br>";
						$.each(data.messages, function(index, value) {
							messages += " - " + value[0] + "<br>";
						});

						displayNotifit( messages , true );
					} else {
						displayNotifit( "Successfully added new contact number!" , false );
						var insert_tr = '<tr data-error="false">'+
										'<td><p data-id="'+data.contact_id+'">'+data.contact_number+'</p></td>'+
										'<td class="pull-right">'+
											'<button type="button" class="btn btn-embossed btn-xs btn-info edit" data-id="'+data.contact_id+'" data-number="'+data.contact_number+'">Edit</button>'+
											'<button type="button" class="btn btn-embossed btn-xs btn-warning delete" data-id="'+data.contact_id+'">Delete</button>'+
										 '</td>'+
									'</tr>';


						var first_row = $("#contactsTable tr:first").data("error");
						if(first_row == true) {
							$("#contactsTable").html(insert_tr);
						} else {
							$("#contactsTable").append(insert_tr);
						}
					}
				});
			}

			add_contact_number = "";
		});

		$.get('getMarkerContactInfo/'+marker_id)
			.done( function(data) {
				if(data.length == 0)
					$("#contactsTable").html("<tr data-error='true'><td><p><strong>No contacts registered.</strong</p></td></tr>");
				else {
					var table = '';
					$.each(data, function (i, item) {
						table += '<tr data-error="false">'+
									'<td><p data-id="'+item.id+'">'+item.contact_number+'</p></td>'+
									'<td class="pull-right">'+
										'<button type="button" class="btn btn-embossed btn-xs btn-info edit" data-id='+item.id+' data-number='+item.contact_number+'>Edit</button>'+
										'<button type="button" class="btn btn-embossed btn-xs btn-warning delete" data-id='+item.id+'>Delete</button>'+
									 '</td>'+
								'</tr>';
					});
					$("#contactsTable").html(table);

					//EDIT CONTACT
					$("#modalBodyContact").unbind("click").on('click', 'button.edit', function(){
						var contact_id 		= $(this).data("id");
						var contact_number 	= $(this).data("number");

						var buttonSave = '<button type="button" class="btn btn-embossed btn-xs btn-success save" data-id='+contact_id+' data-number="'+contact_number+'">Save</button>';
						$(this).replaceWith(buttonSave);

						var p = $("#modalBodyContact").find("p[data-id='" + contact_id + "']");
						TBox(p);
					});


					//SAVE CONTACT
					$("#modalBodyContact").unbind("click").on('click', 'button.save', function(){
						var contact_id 		= $(this).data("id");
						var contact_number 	= $(this).data("number");

						
						var input = $("#modalBodyContact").find("input[data-id='" + contact_id + "']");
						contact_number = RBox(input, contact_number);

						var buttonEdit = '<button type="button" class="btn btn-embossed btn-xs btn-info edit" data-id='+contact_id+' data-number="'+contact_number+'">Edit</button>';
						$(this).replaceWith(buttonEdit);
					});


					//DELETE CONTACTS
					$("#modalBodyContact").unbind("click").on('click', 'button.delete', function(){
						var contact_id 	= $(this).data("id");
						var tr = $(this).parents('tr');

						if(confirm("Do you want to delete this contact?")) {
							$.post('deleteContact', {contact_id: contact_id}).done( function(data) {
								tr.remove();
								displayNotifit( "Successfully deleted contact!" , false );
							}).fail(function(){
								displayNotifit("Failed to connect to server. Please try again later.", true );
							});;
						}
					});
				}
			}).fail(function(){
				$("#modalBodyContact").html("Failed to connect to server. Please try again later.");
			});
	});


	//SUBMIT NEW MARKER/EDITTED MARKER
	$("#markerForm").submit(function(e) {
		e.preventDefault();
		var name 					= 	$("#name").val(),
			address 				= 	$("#address").val(),
			type 					= 	$("#type").val(),
			email 					= 	$("#email").val(),
			lat 					= 	$("#lat").val(),
			lng 					= 	$("#lng").val(),
			rescue_units_id 		= 	$("#rescue_units_id").val(),
			username 				=	$("#username").val(),
			password 				=	$("#password").val(),
			password_confirmation 	=	$("#password_confirmation").val();

		if(rescue_units_id) {
			//EDIT MARKER
			$.post('editMarker/'+rescue_units_id, {
				name: name, 
				address: address, 
				type: type, 
				email: email, 
				lat: lat, 
				lng: lng,
				username: username,
				password: password,
				password_confirmation: password_confirmation,
			}).done( function(data) {
					if(data.error == true) {
						var messages = "";
							messages += "<strong>Failed to save changes</strong><br>";
						$.each(data.messages, function(index, value) {
							messages += " - " + value[0] + "<br>";
						});

						displayNotifit( messages , true );
					} else {
						displayNotifit( "Successfully edited marker: <b>"+name+"</b>!" , false );
						infowindow.close();
						var marker = markersArray[rescue_units_id];
						marker.setOptions({
							title 	: 	name,
							map 	: 	map,
							icon 	: 	'assets/img/'+type+'.png'
						});
						
						google.maps.event.addListener(marker, 'click', (function(marker, rescue_units_id) {
							return function() {
								infowindow.setContent('<div class="noScrollInfoWindow">'+
															'<div><b>'+name+'</b></div>'+
															'<div>'+address+'</div>'+
															'<div>'+email+'</div>'+
															'<div>'+username+'</div>'+
															'<div class="infoButtons">'+
																'<a class="contacts btn btn-embossed btn-primary btn-xs" href="#" data-id="'+rescue_units_id+'" data-toggle="modal" data-target="#contactsMarkerModal">Contacts</a> '+         				
																'<a class="edit btn btn-embossed btn-info btn-xs" href="#markerArea" data-id="'+rescue_units_id+'">Edit</a> '+
																'<a class="delete btn btn-embossed btn-danger btn-xs" href="" data-id="'+rescue_units_id+'" data-name="'+name+'" data-toggle="modal" data-target="#deleteMarkerModal">Delete</a>'+
															'</div></div>');

								infowindow.open(map, marker);
							}
						})(marker, rescue_units_id));

						if(inputtedMarker) {
							inputtedMarker.setMap(null);
							inputtedMarker = null;
						}
						$("#name").val("");
						$("#address").val("");
						$("#type").val("hospital");
						$("#email").val("");
						$("#lat").val("");
						$("#lng").val("");
						$("#rescue_units_id").val("");
						$("#username").val("");
						$("#password").val("");
						$("#password_confirmation").val("");
					}
			}).fail(function(){
				displayNotifit("Failed to connect to server. Please try again later.", true );
			});
		} else {
			//ADD NEW MARKER
			$.post('saveMarker', {
				name: name, 
				address: address, 
				type: type, 
				email: email, 
				lat: lat, 
				lng: lng,
				status: 1,
				username: username,
				password: password,
				password_confirmation: password_confirmation
			}).done( function(data) {
					if(data.error == true) {
						var messages = "";
							messages += "<strong>Failed to add marker</strong><br>";
						$.each(data.messages, function(index, value) {
							messages += " - " + value[0] + "<br>";
						});

						displayNotifit( messages , true );
					} else {
						var rescue_units_id = data.id;

						displayNotifit( "Successfully added marker: <b>"+name+"</b>!" , false );
						infowindow.close();
						var marker = new google.maps.Marker({
							position 	: 		new google.maps.LatLng(lat,lng),
							title 		: 		name,
							map 		: 		map,
							icon 		: 		"assets/img/"+type+".png"
						});	
						
						markersArray[rescue_units_id] = marker;
						
						google.maps.event.addListener(marker, 'click', (function(marker, rescue_units_id) {
							return function() {
								infowindow.setContent('<div class="noScrollInfoWindow">'+
															'<div><b>'+name+'</b></div>'+
															'<div>'+address+'</div>'+
															'<div>'+email+'</div>'+
															'<div>'+username+'</div>'+
															'<div class="infoButtons">'+
																'<a class="contacts btn btn-embossed btn-primary btn-xs" href="#" data-id="'+rescue_units_id+'" data-toggle="modal" data-target="#contactsMarkerModal">Contacts</a> '+         				
																'<a class="edit btn btn-embossed btn-info btn-xs" href="#markerArea" data-id="'+rescue_units_id+'">Edit</a> '+
																'<a class="delete btn btn-embossed btn-danger btn-xs" href="" data-id="'+rescue_units_id+'" data-name="'+name+'" data-toggle="modal" data-target="#deleteMarkerModal">Delete</a>'+
															'</div></div>');

								infowindow.open(map, marker);
							}
						})(marker, rescue_units_id));

						if(inputtedMarker) {
							inputtedMarker.setMap(null);
							inputtedMarker = null;
						}
						$("#name").val("");
						$("#address").val("");
						$("#type").val("hospital");
						$("#email").val("");
						$("#lat").val("");
						$("#lng").val("");
						$("#rescue_units_id").val("");
						$("#username").val("");
						$("#password").val("");
						$("#password_confirmation").val("");
					}
			}).fail(function(){
				displayNotifit("Failed to connect to server. Please try again later.", true );
			});
		}
	});
});

function initialize() {
	geocoder = new google.maps.Geocoder();
	// var myStyles =[
	// 	{
	// 		featureType: "transit.station.bus",
	// 		elementType: "labels",
	// 		stylers: [
	// 			  { visibility: "off" }
	// 		]
	// 	}
	// ];

	var myStyles = [
		{
			stylers: [{"saturation":80},{"gamma":0.7}]
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
					infowindow.setContent('<div class="noScrollInfoWindow">'+
								'<div><b>'+data[key].name+'</b></div>'+
								'<div>'+data[key].address+'</div>'+
								'<div>'+data[key].email+'</div>'+
								'<div>Username: '+data[key].username+'</div>'+
								'<div class="infoButtons">'+
									'<a class="contacts btn btn-embossed btn-primary btn-xs" href="#" data-id="'+data[key].rescue_units_id+'" data-toggle="modal" data-target="#contactsMarkerModal">Contacts</a> '+         				
									'<a class="edit btn btn-embossed btn-info btn-xs" href="#markerArea" data-id="'+data[key].rescue_units_id+'">Edit</a> '+
									'<a class="delete btn btn-embossed btn-danger btn-xs" href="" data-id="'+data[key].rescue_units_id+'" data-name="'+data[key].name+'" data-toggle="modal" data-target="#deleteMarkerModal">Delete</a>'+
								'</div></div>');
					infowindow.open(map, marker);
				}
			})(marker, key));
		}
		//EDIT MARKERS
		$("#map-canvas").on('click', 'a.edit', function(event){

			var marker_id 	= $(this).data("id");
			
			$.get('editMarker/'+marker_id, function(data) {

				$("#rescue_units_id").val(marker_id);
				$("#formTitle").html("Edit Marker");
				$("#name").val(data.name);
				$("#address").val(data.address);
				$("#email").val(data.email);
				$("#lat").val(data.lat);
				$("#lng").val(data.lng);
				$("#type").val(data.rescue_type);
				$("#username").val(data.username);
				if(inputtedMarker) {
					inputtedMarker.setMap(null);
					inputtedMarker = "";
				}
			});
		});
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
		autohide: true
	});
}


// P TO TEXTBOX
function TBox(obj) {
		var id 						= $(obj).attr("data-id");
		var input 					= $('<input />', { 'type': 'text', 'class': 'text_box', 'value': $(obj).html(), 'data-id': id });
		$(obj).replaceWith(input);
		input.focus();
}

//TEXTBOX TO P
function RBox(obj, oldNumber) {
	var id 				= $(obj).attr("data-id");
	var contact_number 	= $(obj).val();


	$.post('editContact/'+id, {
				contact_number: contact_number
			}).done( function(data) {
					if(data.error == true) {
						var messages = "";
							messages += "<strong>Failed to save changes</strong><br>";
						$.each(data.messages, function(index, value) {
							messages += " - " + value[0] + "<br>";
						});
						contact_number 	= oldNumber;
						displayNotifit( messages , true );
					} else {
						contact_number = $(obj).val();
						displayNotifit( "Successfully edited <b>contact number</b>!" , false );
					}
					var input = $('<p />', { 'data-id': id, 'html': contact_number });
					$(obj).replaceWith(input);
					return contact_number;
			}).fail(function(){
				contact_number 	= oldNumber;
				displayNotifit("Failed to connect to server. Please try again later.", true );
				var input = $('<p />', { 'data-id': id, 'html': contact_number });
				$(obj).replaceWith(input);
				return contact_number;
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