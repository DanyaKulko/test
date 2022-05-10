<?php
require '../db.php';

$title = 'Информация пользователя';

if (!isset($_SESSION['logged_user']) || $_SESSION['logged_user']['post'] !== 'teacher') {
    header('location: /');
    exit();
}
$id = $_SESSION['logged_user']['id'];
$user_id = (int)$_GET['id'];

$q = mysqli_query($con, "SELECT * FROM tests ORDER BY id DESC");

$tests_arr = [];

while($ex = mysqli_fetch_assoc($q)) {
    $tests_arr[] = [
            'id' => $ex['id'],
            'name' => $ex['name']
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require '../blocks/head.php'; ?>
    <style>
        table {
            padding: 10px;
        }

        table td {
            border: 1px solid #000;
            text-align: center;
            padding: 5px;
        }

        .form {
            margin: 20px 30px;
            display: inline-block;
            width: 300px;
            padding: 20px;
            border: 1px solid silver;
            border-radius: 5px;
        }

        .form input, .form select {
            width: 100%;
            padding-left: 6px;
            height: 30px;
            margin-top: 12px;
        }

        .form .title {
            font-weight: 600;
            font-size: 1.5em;
            margin-bottom: 10px;
            letter-spacing: .1px;
            text-align: center;
        }
    </style>
</head>
<body>
<?php require '../blocks/header.php'; ?>

<div><a href="/account.php">Account</a></div>


<input type="hidden" id="user_id" value="<?= $user_id ?>">
<div class="form">
    <div class="title">Пометить пройденным</div>
    <form id="give_access" method="post">
        <select id="user_test_1">
            <?php
            $q = mysqli_query($con, "SELECT * FROM tests as tests INNER JOIN (SELECT test_id, user_id FROM issued_tests) as issued_tests WHERE issued_tests.test_id = tests.id AND issued_tests.user_id = $user_id ORDER BY tests.id DESC");
            while ($ex = mysqli_fetch_assoc($q)) { ?>
                <option value="<?= $ex['id'] ?>"><?= $ex['name'] ?></option>
            <?php } ?>
        </select>
        <input type="text" id="module" placeholder="Модуль">
        <input type="text" id="part" placeholder="Часть">
        <input type="submit" value="Открыть доступ">
    </form>
</div>

<div class="form">
    <div class="title">Выдать тест</div>
    <form id="give_test" method="post">
        <select id="user_test_2">
            <?php
            for($i = 0; $i < count($tests_arr); $i++) { ?>
                <option value="<?= $tests_arr[$i]['id'] ?>"><?= $tests_arr[$i]['name'] ?></option>
            <?php } ?>
        </select>
        <input type="submit" value="Выдать">
    </form>
</div>

<div class="form">
    <div class="title">Удалить тест</div>
    <form id="delete_test" method="post">
        <select id="user_test_3">
            <?php
            for($i = 0; $i < count($tests_arr); $i++) { ?>
                <option value="<?= $tests_arr[$i]['id'] ?>"><?= $tests_arr[$i]['name'] ?></option>
            <?php } ?>
        </select>
        <input type="submit" value="Удалить">
    </form>
</div>


<div class="title">Доступные тесты пользователя:</div>

<div>
    <table>
        <tr>
            <td>id</td>
            <td>Название теста</td>
            <td>Выдан до</td>
        </tr>
        <?php
        $q = mysqli_query($con, "SELECT users.id, tests.name, tests.id, issued_tests.user_id, issued_tests.till_time FROM users, issued_tests, tests WHERE users.invited_by = $id AND issued_tests.user_id = $user_id AND issued_tests.user_id = users.id AND issued_tests.test_id = tests.id ORDER BY issued_tests.id DESC");

        while ($ex = mysqli_fetch_assoc($q)) { ?>

            <tr>
                <td><?= $ex['id'] ?></td>
                <td><?= $ex['name'] ?></td>
                <td><?= date('d.m.Y H:i', $ex['till_time']) ?></td>
            </tr>

        <?php } ?>
    </table>
</div>

<br><br>


<div class="title">Список прохождения тестов:</div>

<div>
    <table>
        <tr>
            <td>id</td>
            <td>Название теста</td>
            <td>Модуль</td>
            <td>Часть</td>
            <td>Балы</td>
            <td>Результат</td>
            <td>Дата</td>
            <td>Выдан админом</td>
        </tr>
        <?php
        $q = mysqli_query($con, "SELECT users.id, tests.name, tests.id, test_passing.id,test_passing.given_by_admin, test_passing.module, test_passing.part, test_passing.test_id, test_passing.time, test_passing.done, test_passing.user_id, test_passing.result FROM users, test_passing, tests WHERE users.invited_by = $id AND test_passing.test_id = tests.id AND test_passing.user_id = users.id AND users.id = $user_id ORDER BY test_passing.id DESC");

        while ($ex = mysqli_fetch_assoc($q)) { ?>

            <tr>
                <td><?= $ex['id'] ?></td>
                <td><?= $ex['name'] ?></td>
                <td><?= $ex['module'] ?></td>
                <td><?= $ex['part'] ?></td>
                <td><?= $ex['result'] ?></td>
                <td><?= ($ex['done'] == 1) ? 'Прошел' : 'Не прошел' ?></td>
                <td><?= date('d.m.Y H:i', $ex['time'] + 25200) ?></td>
                <td><?= ($ex['given_by_admin'] == 1) ? 'Дa' : 'Нет' ?></td>
            </tr>

        <?php } ?>
    </table>
</div>


<?php require '../blocks/footer.php'; ?>
</body>
</html>