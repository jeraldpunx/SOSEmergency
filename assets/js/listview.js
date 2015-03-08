
$( window ).load(function() {
//CONTACTS MARKERS
	$("#main").on('click', 'a.contacts', function() {
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
});

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
