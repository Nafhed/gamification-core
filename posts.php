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
				
				<?php include('category_menu.php'); ?>

				<article id="post-directory">

				<div class="new_post"><a href="#" class="button"> + Create new post </a></div>
					<?php $posts = $g->get_posts();

					//echo print_r($posts, true);

						foreach($posts as $post) {
							//echo print_r($post, true);

							$g->debug();
							$category = $g->post_category($post['post_id']);

							//echo 'category ' . print_r($category, true);
							$post_date = $post['post_date'];
							$postDate = date('l j F o', strtotime($post_date));

							if($category) {
								foreach($category as $cat) {
								//echo 'category ' . print_r($cat, true);
							
								//echo "<h1><a href='post.php?id=".$post['post_id'].">Post Title: " . $post['post_title'] . '</a></h1>';
								echo "<div id='post-entry-".$post['post_id']."' class='post-entry'>";
									echo "<h1 class='post-title'><a href=post/".$post['post_slug'].">" . $post['post_title'] . "</a></h1>";
									echo "<span class='post_date'>" . $postDate . "</span>";
									echo "<div class='post_category'> Posted in <a href=posts/category/" . $cat['category_slug'] . ">" . $cat['category_name'] . "</a></div>";
									
									// limit to certain amount of words with elipsis (...)
									echo "<p>" . $post['post_content'] . "</p>";
								echo "</div>";
								}
							}

							else

							{

							//echo "<h1><a href='post.php?id=".$post['post_id'].">Post Title: " . $post['post_title'] . '</a></h1>';
							echo "<div id='post-entry-".$post['post_id']." class='post-entry'>";
								echo "<h1 class='post-title'><a href=post/".$post['post_slug'].">" . $post['post_title'] . "</a></h1>";
								echo "<span class='post_date'>" . $postDate . "</span>";
								echo "<div class='post_category'> No Category </div>";
								echo "<p>" . $post['post_content'] . "</p>";
							echo "</div>";
							}
							
						}

					?>
				</article>
			</div>
		</div>

<?php include('footer.html'); ?>
