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

					<?php 

					$g->debug();

					$cat_slug = $_GET['id'];

					$category = $g->get_category($cat_slug);

					echo "<h1> Category " . $category['category_name'] . " </h1>";

					//echo print_r($category, true);

					$cat_id = $category['category_id'];

					$catPosts = $g->category_posts($cat_id);

					//echo print_r($catPosts, true);

						foreach($catPosts as $catPost) {

							//$category = $g->post_category($post['post_id']);

							//echo 'category ' . print_r($category, true);
							$post_date = $post['post_date'];
							$postDate = date('l j F o', strtotime($post_date));

							
								//echo "<h1><a href='post.php?id=".$post['post_id'].">Post Title: " . $post['post_title'] . '</a></h1>';
								echo "<div id='post-entry-".$catPost['post_id']." class='post-entry'>";
									echo "<h1 class='post-title'><a href=".BASE_URL."post/".$catPost['post_slug'].">" . $catPost['post_title'] . "</a></h1>";

									//echo print_r($catPost, true);

									echo "<span class='post_date'>" . $postDate . "</span>";
									//echo "<div class='post_category'> Posted in <a href=post/" . $cat['category_slug'] . ">" . $cat['category_name'] . "</a></div>";
									echo "<p>" . $catPost['post_content'] . "</p>";
								echo "</div>";
							}

					?>
				</article>
			</div>
		</div>

<?php include('footer.html'); ?>