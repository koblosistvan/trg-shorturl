$(document).ready(function() {
	$('td.edit-data').blur(function(){updateField(this);});

	$('tr.edit-field').each(function() {

		res = datecmp($(this).find('.tol').text(), $(this).find('.ig').text());
		
		col = $(this).find('[col="status"]');
		col.addClass('stat-'+res);
		if (res == 'aktiv') {
			col.text('Aktív');
		} else if (res == 'jovobeli') {
			col.text('Jövőbeli');
		} else {
			col.text('Lejárt');
		}
		
	});
});

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
				$('#msg').removeClass('alertDanger');
				$('#msg').addClass('alertSuccess').html(response.msg);
			} else {
				$('#msg').removeClass('alertSuccess');
				$('#msg').addClass('alertDanger').html(response.msg);
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

function datecmp(tol,ig) {
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