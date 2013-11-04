<?php

//if(isset($_POST['submit']) && ($_POST['submit']=='Login')) {
if(isset($_POST['submit'])=='Login') {

	//hold errors
	$err = array();

	if($_POST['username']=='')
		$err[] = '<div class="display_error"><span>All the fields must be filled in.<span></div>';

	if($_POST['password']=='')
		$err[] = '<div class="display_error"><span>You must enter a password.</span></div>';

}


		$username = mysql_real_escape_string($_POST['username']);
		$password = mysql_real_escape_string(stripslashes(md5($_POST['password'])));

		//$username = $_POST['username'];
		//$password = $_POST['password'];

		 //$row = mysql_fetch_assoc(mysql_query("SELECT userID, username FROM gamify_users WHERE username='{$_POST['username']}' AND password='".md5($_POST['password'])."'"));
		 $player = $g->user_login($username, $password);

		//echo 'player login ' . print_r($player, true);

		if(isset($player['username'])) {
			$_SESSION['player_id'] = $player['userID'];
			$_SESSION['player_name'] = $player['username'];
			$_SESSION['player_email'] = $player['email'];
		}
	
//define user variables
//refine into a $user array with user information
$player_name = $_SESSION['player_name'];

if(empty($_SESSION['username'])) {
//the user is not logged in.

?>
<section id="userForm" class="user-login">
	<?php if(isset($err))
		echo $err['0']; ?>
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


if(!empty($_SESSION['username'])) {
//logged in
	
?>

<section id="playerActive" class="player">
	<p> Welcome, <a href="profile.php"><?php echo $player_name; ?></a> </p>
	<a href="?op=logout">Log out</a>
</section>

<?php }//endif ?>
