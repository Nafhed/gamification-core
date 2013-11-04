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
					<?php echo '<h2> Profile of ' . $player . '</h2'; ?>
					<?php //$g->get_user($_SESSION['player_name']); ?>

					<?php $player_stats = $g->get_user($player);
					//$player_stats = true;

					echo print_r($player_stats, true); ?>

				</article>
			</div>
		</div>

<?php include('footer.html'); ?>
