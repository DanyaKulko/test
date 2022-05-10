function ajax_send(url, data, callback) {
	$.ajax({
	    url: url,
	    data: data,
	    type: 'POST',
	    success: function(data) {
	        callback(jQuery.parseJSON(data));
	    }
	});
}

$('#login_form').on('submit', function(e) {
	e.preventDefault();

	var data = {
		'email': $('#email').val(),
		'password': $('#password').val()
	}

	ajax_send('../ajax_handlers/login.php', data, function(result) {
		console.log(result);
		if(result.success) {
			window.location.href = '/account.php';
		} else {
			alert('Error: ' + result.error);
		}
	});
})


$('#create_account').on('submit', function(e) { 
	e.preventDefault();

	var data = {
		'email': $('#email').val(),
		'name':  $('#name').val(),
		'surname': $('#surname').val(),
		'patronymic': $('#patronymic').val(),
		'password': $('#password').val()
	}

	ajax_send('../ajax_handlers/create_user.php', data, function(result) {
		console.log(result);
		if(result.success) {
			alert('Successfully created!');
			window.location.href = '/account.php';
		} else {
			alert('Error: ' + result.error);
		}
	});
})


$('#give_access').on('submit', function(e) {
	e.preventDefault();

	var data = {
		'test_id': $('#user_test_1').val(),
		'module': $('#module').val(),
		'part': $('#part').val(),
		'user_id': $('#user_id').val(),
		'type': 'give'
	}

	ajax_send('../ajax_handlers/access_controler.php', data, function(result) {
		console.log(result);
		if(result.success) {
			alert('Successfully added!');
			location.reload();
		} else {
			alert('Error: ' + result.error);
		}
	});
})


$('#give_test').on('submit', function(e) {
	e.preventDefault();

	var data = {
		'test_id': $('#user_test_2').val(),
		'user_id': $('#user_id').val(),
		'type': 'give_test'
	}

	ajax_send('../ajax_handlers/access_controler.php', data, function(result) {
		console.log(result);
		if(result.success) {
			alert('Successfully added!');
			location.reload();
		} else {
			alert('Error: ' + result.error);
		}
	});
})


$('#delete_test').on('submit', function(e) {
	e.preventDefault();

	var data = {
		'test_id': $('#user_test_3').val(),
		'user_id': $('#user_id').val(),
		'type': 'delete_test'
	}

	ajax_send('../ajax_handlers/access_controler.php', data, function(result) {
		console.log(result);
		if(result.success) {
			alert('Successfully added!');
			location.reload();
		} else {
			alert('Error: ' + result.error);
		}
	});
})

// $('#send_message').on('click', function() {

// 	var data = {
// 		'message': $('#message_input').val(),
// 		'type': 'send'
// 	}

// 	ajax_send('../ajax_handlers/message_controller.php', data, function(result) {

// 		if(result.success) {
// 			console.log('Sent');
// 		} else {
// 			alert('Error: ' + result.error);
// 		}

// 	})
// })