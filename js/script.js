$(document).ready(function() {
	console.log('init');
	$('td.edit-data').focus(function(){console.log("beleklixelt")});

	$('td.edit-data').blur(function(){console.log("kiklixelt")});
});

function updateEvent(eventElem) {
	data = {};
	eventRow = eventElem.closest('tr');
	eventId = eventRow.getAttribute('data-row-id');
	data['id'] = eventId;
	data['date'] = document.getElementById('date-' + eventId).innerText;
	data['description'] = document.getElementById('description-' + eventId).innerText;
	
	inputs = eventRow.getElementsByTagName("input");
	views = [];
	for (i = 0; i < inputs.length; i++) {
        if(inputs[i].checked) {
			views.push(inputs[i].getAttribute('col'));
        }
	}
	data['views'] = views.join(',');
	$.ajax( {   
		type: "POST",  
		url: "api/update-event.php",  
		cache: false,       
		data: data,
		dataType: "json",
		success: function(response) {   
			if(response.status) {
				$("#msg").removeClass('alert-danger');
				$("#msg").addClass('alert-success').html(response.msg);
			} else {
				$("#msg").removeClass('alert-success');
				$("#msg").addClass('alert-danger').html(response.msg);
			}
		}   
	} );
}

function updateField(dataElem) {
	data = {};
	data['event'] = dataElem.getAttribute('event');
	data['field'] = dataElem.getAttribute('field');
	data['value'] = dataElem.innerHTML;
	
	$.ajax( {   
		type: "POST",  
		url: "api/update-field.php",  
		cache: false,       
		data: data,
		dataType: "json",
		success: function(response) {   
			if(response.status) {
				$("#msg").removeClass('alert-danger');
				$("#msg").addClass('alert-success').html(response.msg);
			} else {
				$("#msg").removeClass('alert-success');
				$("#msg").addClass('alert-danger').html(response.msg);
			}
		}   
	} );
}

function removeEvent(eventElem) {
	if (window.confirm("Biztosan törölni szeretnéd?")) {
		data = {};
		data['id'] = $(eventElem).attr('data-row-id');
		$.ajax( {   
			type: "POST",
			url: "api/remove-event.php",
			cache: false,
			data: data,
			dataType: "json",
			success: function(response) {
				if(response.status) {
					$("#msg").removeClass('alert-danger');
					$("#msg").addClass('alert-success').html(response.msg);
					$("#data-row-" + data['id']).remove();
				} else {
					$("#msg").removeClass('alert-success');
					$("#msg").addClass('alert-danger').html(response.msg);
				}
			}
		});
	}
}