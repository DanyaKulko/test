<header>
	<div class="column">

		<div class="logo_container">
			<a href="/">
				<img src="/images/logo.png" alt="logo">
			</a>
		</div>
<?php 
	$id = (isset($_SESSION['logged_user']['id'])) ? $_SESSION['logged_user']['id'] : 0;
	$q = mysqli_query($con, "SELECT invited_by FROM users WHERE id = $id ORDER BY id LIMIT 1");

	if(mysqli_num_rows($q) == 0)
		$invited_id = 0;
	else {
		$invited_id = mysqli_fetch_assoc($q);
		$invited_id = $invited_id['invited_by'];
	}
?>
		<div class="nav_bar">
			<ul>
				<li><a href="/account.php">Курсы</a></li>
				<li><a href="/chat.php?user_id=<?= $invited_id ?>">Поддержка</a></li>
			</ul>
		</div>

		<div class="header_user_info">
			<div class="logout">
				<a href="/logout.php">Выйти</a>
			</div>
		</div>

	</div>
</header>

<div class="wrapper column">
