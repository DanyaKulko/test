<?php 

function teacher_checking() {
	global $_SESSION;
	if(!isset($_SESSION['logged_user']) || $_SESSION['logged_user']['post'] != 'teacher') 
		return_answer(array('success' => false, 'Пожалуйста, перезайдите в аккаунт преподователя.'));
}

function return_answer($array) {
	echo json_encode($array);
	exit();
}

function login_validate($login) {
	$loginLen = strlen($login);

	if($loginLen < 4 || $loginLen > 20)
		return array('success' => false, 'error' => 'Длина логина не меньше 4 символов и не больше 20');

	return array('success' => true);
}

function password_validate($password) {
	$passwordLen = strlen($password);

	if ($passwordLen < 6) 
		return array('success' => false, 'error' => strlen($password));

	return array('success' => true);
}

function email_validate($email) {

	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		return array('success' => false, 'error' => 'Неверный формат почты');

	return array('success' => true);
}

 ?>