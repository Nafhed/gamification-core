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
					<?php $posts = $g->get_posts();

					echo print_r($posts, true);

						foreach($posts as $post) {
						//while($posts) {
							echo print_r($post, true);
							//var_dump($post);
							//echo "<h1><a href='post.php?id=".$post['post_id'].">Post Title: " . $post['post_title'] . '</a></h1>';
							echo "<h1><a href=".$post['post_slug'].">Post Title: " . $post['post_title'] . '</a></h1>';
							echo 'Post Content: ' . $post['post_content'];
							echo 'Post Date: ' . $post['post_date'];
						}

					?>
				</article>
			</div>
		</div>

<?php include('footer.html'); ?>
