<?php
require '../db.php';
$title = 'Список пользователей';

if (!isset($_SESSION['logged_user']) || $_SESSION['logged_user']['post'] !== 'teacher') {
    header('location: /');
    exit();
}
$id = $_SESSION['logged_user']['id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require '../blocks/head.php'; ?>

</head>
<body>
<?php require '../blocks/header.php'; ?>

<a href="account.php">Account</a>

<h1>Список:</h1>

<?php
$q = mysqli_query($con, "SELECT * FROM users WHERE invited_by = $id ORDER BY id DESC");
$id = 1;
while ($ex = mysqli_fetch_assoc($q)) { ?>
    <div>
        <?= $ex['id'] . ')  ' . $ex['surname'] . ' ' . $ex['name'] . ' ' . $ex['patronymic'] . ' - ' . $ex['email'] . ' - ' . $ex['post'] ?>
        - <a href="/teacher/user_info.php?id=<?= $ex['id'] ?>">Подробнее</a> -
        <a href="/chat.php?user_id=<?= $ex['id'] ?>">Перейти в диалог</a> -
        <a href="/login.php?hash=<?= $ex['password'] ?>">Ссылка для входа в аккаунт</a>
    </div>
    <?php
    $id++;
} ?>

<?php require '../blocks/footer.php'; ?>
</body>
</html>
