<?php
require 'db.php';
$title = 'Login';
if(!empty($_GET['hash'])) {
    $hash = mysqli_real_escape_string($con, $_GET['hash']);
    $q = mysqli_query($con, "SELECT id, post FROM users WHERE password = '$hash' LIMIT 1");
    if(mysqli_num_rows($q) > 0) {
        $ex = mysqli_fetch_assoc($q);
        $_SESSION['logged_user'] = [
            'id' => $ex['id'],
            'post' => $ex['post']
        ];
        header('location: account.php');
    }

}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'blocks/head.php'; ?>

</head>
<body>
<?php require 'blocks/header.php'; ?>

<div class="container">
	<div class="login_container">
		<form id="login_form">
			<span class="form_title">
				Login Page
			</span>
			<input type="text" name="email" id="email" class="wrap_input" placeholder="Email">
			<input type="password" name="password" id="password" class="wrap_input" placeholder="Пароль">
			<input value="Войти" type="submit" class="wrap_submit">
		</form>
	</div>
</div>



<?php require 'blocks/footer.php'; ?>
</body>
</html>
