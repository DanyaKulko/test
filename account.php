<?php 

require 'db.php';

$title = 'User profile';

if(!isset($_SESSION['logged_user'])) 
	redirect('/');

$page = ($_SESSION['logged_user']['post'] == 'teacher') ? 'teacher/teacher_account.php' : 'user_account.php';
$id = $_SESSION['logged_user']['id'];

$q = mysqli_query($con, "SELECT invited_by FROM users WHERE id = $id ORDER BY id LIMIT 1");
$invited_id = mysqli_fetch_assoc($q);
$invited_id = $invited_id['invited_by'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'blocks/head.php'; ?>

</head>
<body>
<?php require 'blocks/header.php'; ?>

<?php require $page; ?>

<?php require 'blocks/footer.php'; ?>

</body>
</html>