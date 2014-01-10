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


							# post experience from invdividual post
							$post_exp = $_POST['experience'];

							//echo $playerDetails['userID'];

							//add current experience to post experience
							$exp_points = $playerDetails['experience'] + $post_exp;
		

							//if post has experience
							//earn experience points
							if(!empty($player_name)) {

								if(isset($_POST['reward'])) {

									# check user achievements
									$check_achievement = $g->check_achievement($playerDetails['userID']);

									# complete this recipe
									$finish = $g->complete_recipe($playerDetails['userID'], $post['post_id']);
									$error = $g->get_errors();


									//print_r($error);
									
									if(!$error['0']) {
										# earn experience points
										$experience = $g->add_experience($player_name, $exp_points);

										$achievement = $g->action($playerDetails['username'], $check_achievement['ID'], 1);

										//echo 'player earn' . print_r($achievement, true);
										# player earns achievement
										echo "<section id'player-rewards'>";
										echo "<h6> Congratulations, You have earned </h6>";
											# check what user is earning
											if(count($achievement) > 1) {

												echo "<div id='player-achievement' class='achievement'>";
													echo "<span class='achievement-title'>" . $achievement['achievement_name'] . "</span>";
													echo "<img class='achiement-badge' src='" . BASE_URL . $achievement['badge_src'] . "' />";
													echo "<span class='achievement-description'>" . $achievement['description'] . "</span>";
												echo "</div>";
											}

												echo "<div class='exp-notification'> You have receieved " . $post_exp . " experience points </div>";
										echo "</section>";

										//echo 'achievement check' . print_r($check_achievement, true);

										# earn user achievement
										//$earn = $g->action($playerDetails['player_name'], );

									} else {
										echo $error['0'];
									}

									

									# after adding to user posts table check which posts completed call function to earn acheivement
									//print_r($finish);
								

								//echo 'Errors ' . print_r($error, true);

								//if post has acheievement

								//check user post achievements
								
								//earn achievements
								//$achievment = $g->action();
								//if(!$error['0']) {

										
									//}
								}
							}
							else if(isset($_POST['reward'])) {
								echo '<div class="display_error"><span>You need to be logged in to earn rewards.<span></div>';
							}
							
							//you have already completed this post.
						?>
					</div>

					<section id="comments">

					<h3> Leave some feedback? </h3>

					<form action="post_comment.php" method="post" id="form-comment">

							<label for="comment_post"> Comment </label>
							<textarea name="comment" id="comment" rows="6" tabindex="4"></textarea>

							<input name="comment_submit" type="submit" value="Post Comment" />
					</form>

					</section>
				</article>
			</div>
		</div>

<?php include('footer.html'); ?>
