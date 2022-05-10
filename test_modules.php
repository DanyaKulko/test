<?php 
require 'db.php';
require 'tests_array.php';


if(!isset($_SESSION['logged_user'])) 
	redirect('/');

$title = 'Модули теста';
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'blocks/head.php'; ?>
<style>

	.module_tests a {
		color: #0000CD;
		text-decoration: none;
	}

	.available {
		color: #00ed17;
	}

	.unavailable {
		color: #eb0000;
	}

</style>
</head>
<body>
<?php require 'blocks/header.php'; ?>





<?php 

$test_id = (int)$_GET['test_id'];

$available_tests = get_tests_by_id($con, $test_id);

// temporary solution, it will be remade

$numbers = array(
	1 => 'first',
	2 => 'second',
	3 => 'third',
	4 => 'fourth',
	5 => 'fifth',
	6 => 'sixth',
	7 => 'seventh',
	8 => 'eighth',
	9 => 'ninth',
	10 => 'tenth',
	11 => 'eleventh',
	12 => 'twelfth',
	13 => 'thirteenth',
	14 => 'fourteenth',
	15 => 'fifteenth',
	16 => 'sixteenth',
	17 => 'seventeenth',
	18 => 'eighteenth',
	19 => 'nineteenth',
	20 => 'twentieth',
	21 => 'twenty_first',
	22 => 'twenty_second',
	23 => 'twenty_third',
	24 => 'twenty_fourth',
	25 => 'twenty_fifth',
    26 => 'twenty_sixth',
    27 => 'twenty_seventh',
    28 => 'twenty_eighth',
    29 => 'twenty_ninth',
    30 => 'thirtyth',
    31 => 'thirty_first',
    32 => 'thirty_second',
    33 => 'thirty_third'
);


$user_id = $_SESSION['logged_user']['id'];

$access = array();

$q = mysqli_query($con, "SELECT * FROM test_passing WHERE user_id = $user_id AND test_id = $test_id AND done = 1");

if(mysqli_num_rows($q) !== 0) {

	while($ex = mysqli_fetch_assoc($q)) {
		$access[] = $ex;
	} 
	array_unique($access, SORT_REGULAR);

}


function get_prev_value($array, $module) {


	$position = array_search($module, array_keys($array));
	$ss = array_keys($array);

	if ($position !== false) 
	    array_splice($ss, $position);

	return end($ss);

}

function get_access($part, $module, $test): bool {

	global $access;
	global $available_tests;
	
	$user_ids = [ 24 ];
	$test_ids = [ 31, 16, 32 ];
	
	if(in_array($_SESSION['logged_user']['id'], $user_ids) && in_array($module, $test_ids)) {
	    return true;
	}
	
	$last_module = ($module > 1) ? $module - 1 : 0;


	if($module === 1 && $part === 0)
		return true;

	if($module === (int)array_key_first($available_tests) && $part === 0)
		return true;

	if(count($access) === 0)
		return false;



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

function get_access_text($part, $module, $test): string
{

	if(get_access($part, $module, $test))
		return 'доступен';
	else
		return 'недоступен';

}

function get_access_class($part, $module, $test): string
{

	if(get_access($part, $module, $test))
		return 'available';
	else
		return 'unavailable';

}

function get_access_link($part, $module, $test, $folder, $final = false): string
{

	global $access;
	global $numbers;

	$user_ids = [ 24 ];
	$test_ids = [ 31, 16 ];
	$part_folder = ($final) ? 'final' : $numbers[$part];

	if(in_array($_SESSION['logged_user']['id'], $user_ids) && in_array($module, $test_ids)) {
	    return "tests/" .  $folder . "/" . $numbers[$module] . "_module/" . $part_folder . "_part/index.php";;
	}
	
	
	if(!get_access($part - 1, $module, $test))
		return '#';


	for($i = 0; $i < count($access); $i++) {
		if($access[$i]['module'] == $module && $access[$i]['part'] == $part)
			return '#';
	}


	return "tests/" .  $folder . "/" . $numbers[$module] . "_module/" . $part_folder . "_part/index.php";

}


?>


<div class="title">Вам доступны: </div>

<div class="module_tests">

	<?php foreach($available_tests as $module => $array) { ?>

		<br><br>
		<h4><?= $array['name'] . "(".$module.")" ?></h4>
		<br>


		<?php for ($i = 0; $i < $array['tests']; $i++) {  ?>

		<a href="<?= get_access_link($i + 1, $module, $test_id, $array['folder']) ?>"><?= $module === 31 ? 'С  подсказками' : 'Билет ' . ($i+1) ?></a> - 
		<span class="<?= get_access_class($i, $module, $test_id) ?>"><?= get_access_text($i, $module, $test_id) ?></span> <br>

		<?php } ?>


		<a href="<?= get_access_link($i + 1, $module, $test_id, $array['folder'], true) ?>">
		    <?php 
		    if($module === 29) echo 'Начальный тест';
		    else if($module === 31) echo 'Без подсказок';
		    else echo 'Финальный тест';
		    ?>
		    </a> -
		<span class="<?= get_access_class($i, $module, $test_id) ?>"><?= get_access_text($i, $module, $test_id) ?></span><br>

	<?php } ?>

</div>


<br><br>
<?php require 'blocks/footer.php'; ?>
</body>
</html>