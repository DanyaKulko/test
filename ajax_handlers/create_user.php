<?php 

require '../db.php';
require 'functions.php';

teacher_checking();

$email   = mysqli_real_escape_string($con, $_POST['email']);
$name    = mysqli_real_escape_string($con, $_POST['name']);
$surname = mysqli_real_escape_string($con, $_POST['surname']);
$patronymic   = mysqli_real_escape_string($con, $_POST['patronymic']);
$password = mysqli_real_escape_string($con, $_POST['password']);

$invited_by = $_SESSION['logged_user']['id'];
$time = time();


$email_validate    = email_validate($email);
$password_validate = password_validate($password);


if($email_validate['success'] && $password_validate['success']) {

	$password = password_hash($password, PASSWORD_DEFAULT);
	$q = mysqli_query($con, "INSERT INTO users (id, email, password, name, surname, patronymic, post, invited_by, time) VALUES(NULL, '$email', '$password', '$name', '$surname', '$patronymic', 'pupil', $invited_by, $time)");

	if($q) {
		return_answer(array('success' => true));
	} else 
		return_answer(array('success' => false, 'error' => 'Ошибка добавления!'));


} else {
	$error = (!$email_validate['success']) ? $email_validate['error'] : $password_validate['error'];
	return_answer(array('success' => false, 'error' => $error));
}

 ?>