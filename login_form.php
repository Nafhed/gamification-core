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
	<div class="display_error"><span><?php if(isset($err)) echo $err['0']; ?></span></div>
	<form method="post" action="" id="user-login" name="userloginform">
	<h6> Member Login </h6>

		<fieldset>
			<label for="username" id="username"> Username: </label>
			<input type="text" name="username" class="username-field field" />
			<label for="password" id="password"> Password: </label>
			<input type="password" name="password" class="password-field field" />
			<input type="submit" name="submit" value="Login" />
			<div class="regsiter"> <a href="registration.php">Register</a> </div>
		</fieldset>
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
<section id="playerActive" class="player">
	<p> Welcome, Guest </p>
	<p> You are not logged in, please <span id="openLogin">login</span> </p>
</section>

<?php }//endif ?>

<?php

if(!empty($_SESSION['player_name'])) {
//logged in
	
?>

<section id="playerActive" class="player">
	<p> Welcome, <a href="profile.php?user=<?php echo $player_name; ?>"><?php echo $player_name; ?></a> </p>
	<a href="?op=logout">Log out</a>
</section>

<?php }//endif ?>