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

					// retrieve individual post
					$post = $g->view_post($_GET['id']); 

					$post_date = $post['post_date'];
					$postDate = date('l j F o', strtotime($post_date));
							
					//echo "<h1><a href='post.php?id=".$post['post_id'].">Post Title: " . $post['post_title'] . '</a></h1>';
					echo "<div id='post-entry-".$post['post_id']." class='post-entry'>";
						echo "<h1 class='post-title'>" . $post['post_title'] . "</h1>";
						echo "<span class='post_date'>" . $postDate . "</span>";
						echo "<p>" . $post['post_content'] . "</p>";
					echo "</div>";
					?>

					<?php $experience = $post['post_experience']; ?>
					<div id="user_reward">
						<form method="post" class="reward">
							<input type="hidden" name="experience" value=<?php echo $experience; ?>>
							<input type="submit" name="reward" value="Finished!">
						</form>
						<?php 

							echo $player_name;
							echo print_r($_POST, true);
							$post_exp = $_POST['experience'];

							//add current experience to post experience
							$exp_points = $playerDetails['experience'] + $post_exp;
							
							//if post has experience
							//earn experience points
							
							if(isset($exp_points)) {
								$experience = $g->add_experience($player_name, $exp_points);
							}

							//if post has acheievement
							//earn achievements
							//$achievment = $g->action();
							if(!empty($experience)) {
								echo "<div class='notification'> You have receieved " . $exp_points . " experience </div>";
							}

							echo $exp_points;
						?>
					</div>
				</article>
			</div>
		</div>

<?php include('footer.html'); ?>
