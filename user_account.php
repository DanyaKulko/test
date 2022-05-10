<div class="title">Вам доступны: </div>



<?php 
$q = mysqli_query($con, "SELECT tests.*, issued_tests.user_id, issued_tests.test_id, issued_tests.till_time FROM tests, issued_tests WHERE issued_tests.user_id = $id AND issued_tests.test_id = tests.id ORDER BY issued_tests.id DESC");

if(mysqli_num_rows($q) == 0) {
	echo "К сожалению, здесь пока пусто...";
} else {

	while($ex = mysqli_fetch_assoc($q)) { ?>




<div class="aviable_tests">
	<div class="left_part">
		<img src="images/<?= $ex['image'] ?>" alt="">
		<a href="test_modules.php?test_id=<?= $ex['id'] ?>">К обучению</a>
	</div>

	<div class="right_part">
		<div class="test_title"><?= $ex['name'] ?></div>

		<div class="test_sub_info">
			<div>
				Модулей: <?= $ex['modules'] ?>
			</div>
			<div>
				Билетов: <?= $ex['tickets'] ?>
			</div>
			<div>
				Экзаменов: <?= $ex['exams'] ?>
			</div>
			<br>
			<div>
				Доступно до: <?= date("d.m.Y", $ex['till_time'] + 25200) ?>
			</div>
		</div>
	</div>
</div>

	<?php } 
} ?>
