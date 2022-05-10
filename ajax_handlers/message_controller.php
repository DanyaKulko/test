<?php 

require '../db.php';
require 'functions.php'; 

logged_in_check();

$id = $_SESSION['logged_user']['id'];
$user_id = (int)$_POST['user_id'];

$type = $_POST['type'];
$aviable_types = array('get', 'send');

if (!in_array($type, $aviable_types)) 
	return_answer(array('success' => false, 'error' => 'Ахтунг!'));


if ($type == 'get') {

	$last_id = (int)$_POST['last_id'];

	$q = mysqli_query($con, "SELECT name, surname FROM users WHERE id = $user_id ORDER BY id LIMIT 1");
	$name = mysqli_fetch_assoc($q);


	$q = mysqli_query($con, "SELECT * FROM messages WHERE ((user_to = $id AND user_from = $user_id) OR (user_to = $user_id AND user_from = $id)) AND id > $last_id ORDER BY id ASC LIMIT 50");

	if(mysqli_num_rows($q) != 0) {

		$output = array();

		while($ex = mysqli_fetch_assoc($q)) {

			if($ex['user_to'] == $id)
				$ex['user_info'] = $name['surname'] . ' ' . $name['name'];
			else 
				$ex['user_info'] = 'Вы';

			$ex['time'] = date("d.m.Y H:i", $ex['time']);

			array_push($output, $ex);
		}

		$last_id = end($output);
		$last_id = $last_id['id'];

		return_answer(array('success' => true, 'messages' => $output, 'last_id' => $last_id));
	} else 
		return_answer(array('success' => false, 'last_id' => $last_id));		
	



} elseif ($type == 'send') {

	$message = mysqli_real_escape_string($con, $_POST['message']);
	$time = time();

	$q = mysqli_query($con, "INSERT INTO messages(id, user_from, user_to, message, viewed, time) VALUES(NULL, $id, $user_id, '$message', 0, $time)");


	return_answer(array('success' => true, 'last_id' => mysqli_insert_id($con)));

}


?>