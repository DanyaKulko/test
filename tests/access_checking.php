<?php 

require '../../../../db.php';
require '../../../../tests_array.php';

logged_in_check();


$module = (empty($module)) ? 1 : $module;
$part   = (empty($part))   ? 1 : $part;
$test   = (empty($test))   ? 1 : $test;

$_SESSION['logged_user']['test'] = $test;
$_SESSION['logged_user']['part'] = $part;
$_SESSION['logged_user']['module'] = $module;

$id = $_SESSION['logged_user']['id'];

$access = array();
$q = mysqli_query($con, "SELECT * FROM test_passing WHERE user_id = $id AND test_id = $test AND done = 1");


if(mysqli_num_rows($q) != 0) {

	while($ex = mysqli_fetch_assoc($q)) {
		array_push($access, $ex);
	} 
	array_unique($access);

}

function get_prev_value($array, $module) {

	$position = array_search($module, array_keys($array));
	$ss = array_keys($array);

	if ($position !== false) 
	    array_splice($ss, $position);
	

	return end($ss);

}


$available_tests = get_tests_by_id($con, $test);

function get_access($part, $module, $test) {


	global $access;
	global $available_tests;
	
	$user_ids = [ 24 ];
	$test_ids = [ 31, 16, 32 ];
	
	if(in_array($_SESSION['logged_user']['id'], $user_ids) && in_array($module, $test_ids)) {
	    return true;
	}
	
	$last_module = ($module > 1) ? $module - 1 : 0;


	if($module == 1 && $part == 0)
		return true;

	if($module == array_key_first($available_tests) && $part == 0)
		return true;

	if(count($access) == 0)
		return false;

    if($module === 28)
        return true;



	for ($i = 0; $i < count($access); $i++) {

		if($module > 1 && $part == 0) {

			if($last_module == $access[$i]['module'] && $access[$i]['part'] == $available_tests[$module]['tests'] + 1)
				return true;

			if(get_prev_value($available_tests, $module) == $access[$i]['module'] && $access[$i]['part'] == $available_tests[get_prev_value($available_tests, $module)]['tests'])
				return true;

			if ($last_module == $access[$i]['module'] && $access[$i]['part'] == 4) 
				return true;

		}

		if($access[$i]['part'] == $part && $access[$i]['module'] == $module)
			return true;
	}

	return false;
}

$part = ($part > 0) ? --$part : $part; 

if(!get_access($part, $module, $test))
	redirect('/');


 ?>


 <style>
 .text_bckg {
 	position: absolute;
 	font-size: 1em;
 	transform: 45deg;
 	color: #e6e6e6;
  	color: #eaeaea;
 	z-index: 999;
 }

 .quiz-slide-visualizer__content {
 	height: auto!important;
 }
 </style>

 <?php 
 for($i = 0; $i < rand(4, 7); $i++) { ?>

 	<div class="text_bckg" style="top: <?= rand(30, 70) ?>%; left: <?= rand(20, 80) ?>%">
 		<?= $_SESSION['logged_user']['id'] ?>
 	</div>

<?php } ?>