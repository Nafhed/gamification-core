<?php
	
	/*

	Author: Nathan Brettell
	Digital Media Project
	Gamification

	Player profile


	*/

	include('header.php');

	$player = $_GET['user'];

?>
		<div id="main">
			<div id="content">
				<article id="post">
					<?php echo '<h2> Profile of ' . $player . '</h2>'; ?>
					<?php //$g->get_user($_SESSION['player_name']); ?>

					<?php $player_stats = $g->get_user($player);
					//$player_stats = true;

					echo print_r($player_stats, true); ?>


					<h2 id="edit-user"> Bring some life to your profile </h2>
					<form action="" name="edituser" method="post" class="form-edit-user">

						<label for="fist-name" class="form-label">First Name: </label>
						<input type="text" name="firstname" id="firstname" />

	 					<label for="last-name" class="form-label">Last Name: </label>
						<input type="text" name="lastname" id="lastname" />

						<label for="dob" class="form-label">Date Of Birth: </label>
						<input type="text" name="dob" id="dob" />

						<label for="country" class="form-label">Country: </label>
						<input type="text" name="country" id="country" />

					</form>

				</article>
			</div>
		</div>

<?php include('footer.html'); ?>
