<?php 

require '../db.php';

$percent = (int)$_POST['percent'];
$test_name = mysqli_real_escape_string($con, $_POST['name']);
$id = (isset($_SESSION['logged_user'])) ? $_SESSION['logged_user']['id'] : 0;
$time = time();

$test_id = $_SESSION['logged_user']['test'];
$part   = $_SESSION['logged_user']['part'];
$module = $_SESSION['logged_user']['module'];

$done = ($percent >= 95) ? 1 : 0;

$q = mysqli_query($con, "INSERT INTO test_passing VALUES (NULL, $id, '$test_name', $test_id, $module, $part, $percent, $done, $time, 0)");
if(!$q) echo mysqli_error($con);
?>