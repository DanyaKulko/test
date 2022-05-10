<?php 

require '../db.php';
require 'functions.php';

teacher_checking();


$test_id = (int)$_POST['test_id'];
$user_id = (int)$_POST['user_id'];
$type = $_POST['type'];
$time = time();


if($type == 'give') {

	$part = (int)$_POST['part'];
	$module = (int)$_POST['module'];

	$q = mysqli_query($con, "INSERT INTO test_passing VALUES(NULL, $user_id, 'Given by admin', $test_id, $module, $part, 100, 1, $time, 1)");

	if($q) 
		return_answer(array('success' => true));
	else 
		return_answer(array('success' => false, 'error' => 'Ошибка'));


} elseif($type == 'give_test') {

	$till_time = $time + 31536000;

	$q = mysqli_query($con, "INSERT INTO issued_tests VALUES(NULL, $test_id, $user_id, $time, $till_time)");

	if($q) 
		return_answer(array('success' => true));
	else 
		return_answer(array('success' => false, 'error' => 'Ошибка'));


} elseif($type == 'delete_test') {

	$q = mysqli_query($con, "DELETE FROM issued_tests WHERE test_id = $test_id AND user_id = $user_id");

	if($q) 
		return_answer(array('success' => true));
	else 
		return_answer(array('success' => false, 'error' => 'Ошибка'));


}


 ?>