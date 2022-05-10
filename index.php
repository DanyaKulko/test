<?php 
require 'db.php';
$title = 'Главная страница';
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'blocks/head.php'; ?>

</head>
<body>
<?php require 'blocks/header.php'; ?>

<br><br>
<h1>Вы на главной странице проекта</h1>
<br>

<?php if(isset($_SESSION['logged_user'])) { ?>
	<a href="account.php">Курсы</a>
<?php } else { ?>
	<a href="login.php">Войти</a>
<?php } ?>

<?php require 'blocks/footer.php'; ?>
</body>
</html>