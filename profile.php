<?php
	
	/*

	Author: Nathan Brettell
	Digital Media Project
	Gamification

	Player profile


	*/

	include('header.php');

	$player = $_GET['user'];

	# check to make sure user exists

	$player_stats = $g->get_user($player);
	//$player_stats = true;

	//echo print_r($g->get_errors(), true);

	$error = $g->get_errors();

	//echo '<pre>' . print_r($player_stats, true) . '</pre>'; 

?>

<script type='text/javascript' src='<?php echo BASE_URL; ?>js/leaderboard_update.js'></script>

		<div id="main">
			<div id="content">
				<article id="post">
				<?php echo $error['0']; ?>
				<?php if(count($error) < 1) { ?>
					<h2>Profile of <?php echo $player; ?></h2>
						<?php } else { ?>
					<h2>Profile doesn't exist</h2>
				<?php } ?>

				<div class="player_stats">
					<span> Experience points: <?php echo $player_stats['experience']; ?> </span>
					<span> Level <?php echo $player_stats['level']; ?> </span>
				</div>

				</article>
					<!--<h2 id="edit-user"> Bring some life to your profile </h2>
					<form action="" name="edituser" method="post" class="form-edit-user">

						<label for="fist-name" class="form-label">First Name: </label>
						<input type="text" name="firstname" id="firstname" />

	 					<label for="last-name" class="form-label">Last Name: </label>
						<input type="text" name="lastname" id="lastname" />

						<label for="dob" class="form-label">Date Of Birth: </label>
						<input type="text" name="dob" id="dob" />

						<label for="country" class="form-label">Country: </label>
						<input type="text" name="country" id="country" />

					</form>-->
					<section id="achievements">
					<?php 
						# get user achievements
						$achievements = $player_stats['achievements'];

						//echo print_r($achievements, true);

						foreach ($achievements as $achievement) {
							//echo print_r($achievement, true);
							# player earns achievement
							echo "<div id='achievement-section' class='achievement'>";
								echo "<h6> Achievements </h6>";
								echo "<img class='achiement-badge' src='" . BASE_URL . $achievement['badge_src'] . "' />";
								echo "<div class='achievement-title'>" . $achievement['achievement_name'] . "</div>";
								//echo "<span class='achievement-description'>" . $achievement['description'] . "</span>";
							echo "</div>";
						}


					?>
					</section>
					<div class="display_error"><span><?php echo $error['0']; ?></span></div>

					<div class="player-leaderboard">

						<div id="loading"><img src="../images/ajax-loading.gif" alt="ajax load animation" /></div>

						<section id="leaderboard-update" class="leaderboard">
						</section>

					</div>

			</div>
		</div>

<?php include('footer.html'); ?>
