<?php 
require 'db.php';

logged_in_check();

if (empty($_GET['user_id'])) 
	redirect('/');

$user_id = (int)$_GET['user_id'];
$id = $_SESSION['logged_user']['id'];

$q = mysqli_query($con, "SELECT id, name, surname FROM users WHERE id = $user_id ORDER BY id LIMIT 1");
if(mysqli_num_rows($q) == 0)
	redirect('/');

$user_info = mysqli_fetch_assoc($q);



$title = "Чат с преподавателем";
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'blocks/head.php'; ?>
<style>
	.chat_outer {
		border: 1px solid #e7e8ec;
		border-radius: 4px;
		overflow: hidden;
		max-width: 450px;
		width: 450px;
		margin: 100px auto;
		height: 600px;
		padding: 5px;
	}
	.chat_area {
		height: calc(527px - 1.5em - 27px);
		overflow-y: auto;
	}
	.chat_input {
		background-color: #fafbfc;
		border-top: 1px solid #e7e8ec;
		padding: 15px 10px;
		margin-left: -5px;
		margin-bottom: -5px;
		margin-right: -5px;
	}
	.chat_input input {
		height: 35px;
		padding: 2px 6px;
		border: 1.5px solid silver;
		border-radius: 4px;
	}
	#message_input {
		width: 75%;
	}
	#send_message {
		cursor: pointer;
		margin-left: 2%;
		width: 21%;
	}
	.chat_title {
		margin-left: -5px;
		margin-right: -5px;
		border-bottom: 1px solid #e7e8ec;
		font-size: 1.5em;
		font-weight: 600;
		letter-spacing: .1px;
		padding: 10px 0;
		text-align: center;
	}
	.chat_attach_icon {
		display: inline-block;
		color: #000;
		vertical-align: middle;
		cursor: pointer;
		font-size: 1.5em;
		margin: auto 10px auto 5px;
	}
	.chat_inputs {
		width: calc(100% - 45px);
		display: inline-block;
		vertical-align: middle;
	}
	.message_inner {
		padding: 2px 5px 5px 7px;
	}
	.user_info {
		color: blue;
		padding-left: 4px;
		display: inline-block;
		margin-top: 8px;
	}
	.message_time {
		display: inline-block;
		font-size: 0.8em;
		margin-left: 3px;
		color: grey;
	}

.chat_attachments_popup {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	z-index: 99;
	bottom: 0;
	display: none;
    text-align: center;
    white-space: nowrap;
    background-color: rgba(0, 0, 0, .5);
}

.chat_attachments_popup::after {
    display: inline-block;
    vertical-align: middle;
    width: 0;
    height: 100%;
    content: '';
}

.modal {
    display: inline-block;
    vertical-align: middle;
}

.modal_container {
	position: relative;
    margin: 50px;
    border-radius: 4px;
    min-width: 200px;
    text-align: left;
    white-space: normal;
    background-color: #fff;    
    color: #000;
}
.modal_title {
	background-color: #e3e3e3;
	border-bottom: 1px solid #e7e8ec;
	font-size: 1.3em;
	font-weight: 600;
	padding: 13px;
}
.modal_content {
    padding: 20px 25px;
}
.modal_close_ico {
	position: absolute;
	right: 14px;
	top: 10px;
	font-size: 1.6em;
	cursor: pointer;
}
</style>
</head>
<body>
<?php require 'blocks/header.php'; ?>


<div class="chat_outer">
	<div class="chat_title">
		<span>
			<?= $title ?>
		</span>
	</div>
	<div class="chat_area">
		<div class="message0" style="display: none"></div>
	</div>
	<div class="chat_input">
		<input type="hidden" id="user_id" value="<?= $user_id ?>">
		<div class="chat_attach_icon">
			<i class="far fa-paperclip"></i>
		</div>
		<div class="chat_inputs">
			<input type="text" id="message_input" placeholder="Введите ваше сообщение">
			<input type="submit" id="send_message">
		</div>
	</div>
</div>
<div class="chat_attachments_popup">
    <div class="modal">
        <div class="modal_container">
        	<div class="modal_close_ico">
        		<i class="fal fa-times"></i>
        	</div>
        	<div class="modal_title">
        		Загрузить файлы:
        	</div>
    		<div class="modal_content">
    			Для загрузки файлов нажмите кнопку "Загрузить": <br><br>
    			<input type="file">
    		</div>
        </div>
    </div>
</div>

<?php require 'blocks/footer.php'; ?>

<script>
$('.chat_attach_icon').on('click', function() {
	$('.chat_attachments_popup').fadeIn(350);
})

$('.modal_close_ico').on('click', function() {
	$('.chat_attachments_popup').fadeOut(350);
})

$('#send_message').on('click', function(e) {
	e.preventDefault();

	var data = {
		'user_id': $('#user_id').val(),
		'type': 'send',
		'message': $('#message_input').val()
	}

    $.ajax({
        type: "POST",
        url: "../ajax_handlers/message_controller.php",
        data: data,
        cache: false,
        success: function(data) {

        	data = jQuery.parseJSON(data);

        	if(data.success) {
	            $('#message_input').val('');
	            waitForMessage(data.last_id, true);
        	}
	        else 
	        	alert('Ошибка при отправке сообщения');

		}
    })

})

function addMessage(data) {
	var chat_with = $('#user_id').val();

	for (let i = 0; i < data.length; i++) {
		console.log(data.length);

			
		$('.chat_area').append('<div class="message' + data[i]['id'] + '" id="message"><div class="user_info">' + data[i]['user_info'] + '</div><span class="message_time"> - ' + data[i]['time'] + '</span><div class="message_inner">' + data[i]['message'] + '</div></div>');
	}
	console.log(data);
}

function waitForMessage(last_id, update_only = false) {

	var data = {
		'user_id': $('#user_id').val(),
		'last_id': last_id,
		'type': 'get'
	}

    $.ajax({
        type: "POST",
        url: "../ajax_handlers/message_controller.php",
        data: data,
        cache: false,
        success: function(data) {
			console.log( data);

        	data = jQuery.parseJSON(data);
        	if(data.success)
	            addMessage(data.messages);

	        if(!update_only)
				setTimeout(function(){waitForMessage(data.last_id)}, 5000);
		}
    })
};

$(function() {
	// var last_id = $('div#message').last(),
	// 	last_id = last_id[0].classList,
	// 	last_id = last_id[0].replace('message', '');

	// console.log(last_id);
    waitForMessage(0);
});
</script>

</body>
</html>