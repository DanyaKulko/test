<?php 

require '../db.php';
require 'functions.php';

$email = mysqli_real_escape_string($con, $_POST['email']);
$password = mysqli_real_escape_string($con, $_POST['password']);

$password_validate = password_validate($password);
$email_validate    = email_validate($email);


if($email_validate['success'] && $password_validate['success']) {

	$q = mysqli_query($con, "SELECT * FROM users WHERE email = '$email' ORDER BY id LIMIT 1");

	if(mysqli_num_rows($q) != 0) {

		$ex = mysqli_fetch_assoc($q);

		if(password_verify($password, $ex['password'])) {
			$_SESSION['logged_user'] = [
				'id' => $ex['id'],
				'post' => $ex['post']
			];
			return_answer(array('success' => true));
		}
		else
			return_answer(array('success' => false, 'error' => 'Неверный пароль'));

	} else 
		return_answer(array('success' => false, 'error' => 'Такой пользователеь не найден!'));


} else {
	$error = (!$email_validate['success']) ? $email_validate['error'] : $password_validate['error'];
	return_answer(array('success' => false, 'error' => $error));
}

 ?>