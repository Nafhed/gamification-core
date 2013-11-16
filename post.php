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
				<article id="post">

					<?php
					//var_dump($_SERVER);
					//echo print_r($_GET, true);

					echo $g->debug();

					$post = $g->view_post($_GET['id']); 

					//echo 'array - ' . print_r($post, true);

					echo '<h1>' . $post['post_title'] . '</h1>';
					echo '<span>Posted on: ' . $post['post_date'] . '</span>';
					echo '<p>' . $post['post_content'] . '</p>';
					?>
				</article>
			</div>
		</div>

<?php include('footer.html'); ?>
