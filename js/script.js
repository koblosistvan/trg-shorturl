$(document).ready(function() {
	$('td.edit-data').blur(function(){updateField(this);});

	$('i.url-go').css('cursor', 'pointer');
	$('i.url-go').on('click', function() {
		const url = window.location.href + $(this).closest('tr').find('[col="short_name"]').text();
		if (url) {
			window.open(url, '_blank');
		}
	});

	$('i.url-copy').css('cursor', 'pointer');
	$('i.url-copy').on('click', function() {
		const url = window.location.href + $(this).closest('tr').find('[col="short_name"]').text();
		if (url) {
			navigator.clipboard.writeText(url);
			msg('Vágólapra másolva: ' + url, 'success');
		} else {
			msg('A másolás nem sikerült.', 'danger');
		}
	});
});

function msg(msg, type) {
	$('#msg').removeClass('alert-danger alert-success');
	$('#msg').addClass('alert-' + type).html(msg);
	$('#msg').fadeIn();
	setTimeout(function() { $('#msg').fadeOut(); }, 2000);
}	

const DT = new Date();

function updateField(elem) {
	data = {};

	elem = elem.closest('tr');

	data['id'] = elem.getAttribute('data-row-id');

	data['name'] = elem.querySelector('[col="name"]').innerText
	data['short_name'] = elem.querySelector('[col="short_name"]').innerText
	data['url'] = elem.querySelector('[col="url"]').innerText
	data['valid_from'] = elem.querySelector('[col="valid_from"]').innerText
	data['valid_to'] = elem.querySelector('[col="valid_to"]').innerText

	$.ajax( {
		type: "POST",
		url: "api/update-field.php",
		cache: false,
		data: data,
		dataType: "json",
		success: function(response) {
			if (response.status) {
				msg(response.msg, 'success');
			} else {
				msg(response.msg, 'danger');
			}
		}
	} );

	/*

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
	
	*/
}

/* function datecmp(tol,ig) {
	t = (new Date(DT.toISOString().split('T')[0])).setHours(0); // alapvetoen 1AM-re allitja be

	tol = new Date(tol);
	ig = new Date(ig);

	if (tol <= t && t <= ig) {
		return 'aktiv';
	} else if (tol > t) {
		return 'jovobeli';
	} else {
		return 'lejart';
	}
}
 */

function validate_new() {
	error_ctr = 0
	document.querySelectorAll("#new-record-form input.form-text").forEach((elem, i) => {
		if (elem.value == "") {
			document.getElementById(elem.id + '-error').innerHTML = 'Érvénytelen';
			
			error_ctr += 1

		} else {
			document.getElementById(elem.id + '-error').innerHTML = "";
		}
	});

	if (error_ctr) {
		document.getElementById('submit-new').disabled = true
	} else {
		document.getElementById('submit-new').disabled = false
	}


}