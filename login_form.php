<?php

//echo print_r($_SESSION, true);

//if(isset($_POST['submit']) && ($_POST['submit']=='Login')) {
//if(isset($_POST['submit'])=='Login') {
if(isset($_POST['submit']) && ($_POST['submit']=='Login')) {
	//hold errors
	$err = array();

	if($_POST['username']=='')
		$err[] = 'All the fields must be filled in.';

	if($_POST['password']=='')
		$err[] = 'You must enter a password.';

//}


		$_POST['username'] = mysql_real_escape_string($_POST['username']);
		$_POST['password'] = mysql_real_escape_string(md5($_POST['password']));

		$username = $_POST['username'];
		$password = $_POST['password'];

		 //$row = mysql_fetch_assoc(mysql_query("SELECT userID, username FROM gamify_users WHERE username='{$_POST['username']}' AND password='".md5($_POST['password'])."'"));
		 $player = $g->user_login($username, $password);

		 # get potential errors
		 $error = $g->get_errors();

		//echo 'player login ' . print_r($player, true);

		if(isset($player['username'])) {
			$_SESSION['player_id'] = $player['userID'];
			$_SESSION['player_name'] = $player['username'];
			$_SESSION['player_email'] = $player['email'];
		}
	}
	
//define user variables
//refine into a $user array with user information
$player_name = $_SESSION['player_name'];

if(empty($_SESSION['player_name'])) {
//the user is not logged in.

?>

<section id="userForm" class="user-login">
	<div class="display_error"><span><?php echo $error['0']; if(isset($err)) echo $err['0']; ?></span></div>
	<form method="post" action="" id="user-login" class="user-form" name="userloginform">
		<fieldset>
		<legend> Member Login </legend>
			<label for="username" id="username"> Username: </label>
			<input type="text" name="username" class="username-field field" />
			<label for="password" id="password"> Password: </label>
			<input type="password" name="password" class="password-field field" />
		</fieldset>
		<input type="submit" name="submit" value="Login" />
		<a class="register" href="<?php echo BASE_URL; ?>registration">Register</a>
	</form>
	<div class="user_status"></div>
</section>

<?php
	
}//end if

?>

<?php

if(empty($_SESSION['player_name'])) {
//not logged in
	
?>
<section id="playerNotActive" class="player">
	<a href="#" id="openLogin">Login</a>
	<a href="<?php echo BASE_URL; ?>registration" class="register">Register</a>
</section>

<?php }//endif ?>

<?php

if(!empty($_SESSION['player_name'])) {
//logged in

//get user level
$playerDetails = $g->get_user($player_name);

$next_level = $g->get_next_level($playerDetails['level']);

$level_percentage = (($playerDetails['experience'] / $next_level['experience_needed']) * 100);

?>

<section id="playerActive" class="player">
	<!--<div class="user_experience"> Experience <span><?php echo $playerDetails['experience']; ?></span> </div>-->
	<div class="user_level"> <span>Level</span> <?php echo $playerDetails['level']; ?> <?php echo $playerDetails['level_name']; ?> </div>
	<div class="level_bar" style="width: 220px; height: 20px;"> <div class="level-bar-fill" style=" width: <?php echo $level_percentage; ?>%;"><span class="percentage"><?php echo round($level_percentage); ?>%</span></div> </div>
	<span> <a id="player" href="#"><?php echo $player_name; ?></a> 
		<ul id="user-links" style="display: none;">
			<li><a href="/DMP/Core/profile/<?php echo $player_name; ?>"> Profile </a></li>
			<li><a href="?op=logout">Log out</a></li>
		</ul>
	</span>
</section>

<?php }//endif ?>