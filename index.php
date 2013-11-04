<?php
	
/*

	Author: Nathan Brettell
	Digital Media Project
	Gamification


*/

	include('header.php');
?>
		<div id="main">
			<div id="content">
				<article id="post"><?php //get post-id and attach to the id of the article element ?>

				<?php echo 'user session / player array ' . print_r($player, true); ?>

				<?php echo '<pre>'.print_r($g, true).'</pre>'; ?>
				<?php var_dump($g); ?>

				</article>
			</div>
		</div>

<?php include('footer.html'); ?>
