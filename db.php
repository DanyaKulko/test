<?php 

$con = mysqli_connect('127.0.0.1', 'seamrmii_seamansbook', '@sb5801.', 'seamrmii_sea_test');
mysqli_query($con, "SET NAMES 'utf8'");

session_start();

function redirect($location = '/') {
	header('location: ' . $location);
	exit();	
}

function logged_in_check() {
	if(!isset($_SESSION['logged_user'])) 
		redirect('/');
}

?>