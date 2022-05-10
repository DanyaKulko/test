<?php 



function get_tests_by_id($con, $test_id) {

	$user_id = $_SESSION['logged_user']['id'];

	$q = mysqli_query($con, "SELECT id FROM tests WHERE id = $test_id ORDER BY id LIMIT 1");

	if(mysqli_num_rows($q) == 0)
		redirect('/');


	$q2 = mysqli_query($con, "SELECT * FROM given_modules WHERE user_id = $user_id AND test_id = $test_id ORDER BY id ASC");

	if(mysqli_num_rows($q2) > 0) {
		while($ex = mysqli_fetch_assoc($q2)) {
			$given_modules[] = $ex['test_module_number'];
		}
	} else {
		for ($i=0; $i < 50; $i++) 
			$given_modules[] = $i;
	}


	$q = mysqli_query($con, "SELECT tests.id, tests.folder, given_modules.test_id, given_modules.test_module_number, given_modules.user_id, tests_modules.test_id, tests_modules.name, tests_modules.tests_count, tests_modules.test_number FROM tests, tests_modules, given_modules WHERE tests.id = tests_modules.test_id AND tests.id =$test_id AND given_modules.test_id = tests.id AND given_modules.user_id = $user_id AND given_modules.test_id = tests.id AND given_modules.test_module_number = tests_modules.test_number ORDER BY given_modules.id ASC");
	

	while($ex = mysqli_fetch_assoc($q)) {
		if(in_array($ex['test_number'], $given_modules)) {
			$result[$ex['test_number']] = [
				'test_id' => $ex['id'],
				'name' => $ex['name'],
				'tests' => (int)$ex['tests_count'],
				'folder' => $ex['folder']
			];
		}
	}

	return $result;
}


?>