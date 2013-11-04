<?php

$registered;

include('header.php');
//include("./gamify/gamify.php");

//create class instance with database connection
//$g = new gamify("localhost", "root", "root", "gamify");

//if(isset($_POST['submit']) && ($_POST['submit']=='Regsiter')) {


//}

if(isset($_POST['submit'])=='Regsiter') {
	//hold errors
	$err = array();

	//make sure both passwords match from POST input 
	//(Doesn't currently work).

	if($_POST['username']=='') {
		$registered = false;
		$err[] = 'You must enter a username.';
	}

	if($_POST['password1']=='') {
	    $err[] = 'You must enter a password.';
	}

	if($_POST['password1'] != $_POST['password2']) {
	    $err[] = 'The Passwords do not match.';
	}

	//keep the username to less than '18' characters
	//(Doesn't currently work).
	if(strlen($username) > 12) {
	    $err[] = 'Your username is too long.';
	}
}


//if(!$err) {
	//retrieve our data from POST
	$username = $_POST['username'];
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];
	$email = $_POST['email'];

	$password = $password1;
	$password = mysql_real_escape_string(stripslashes(md5($_POST['password1'])));

	//sanitize username
	$username = mysql_real_escape_string($username);

	//output pdo errors
	$g->debug();

	//print_r($_POST);

	$register = $g->create_user($username, $password, $email);
	$registered = true;


?>
		<div id="main">
		<div class="display_error"><span><?php if(isset($err)) echo $err['0']; ?></span></div>
			<div id="content">
				<article id="post">

					<form name="register" action="" method="post">
						
						<h2>Registration Form</h2>
						<label for="username" class="form-label">Username: </label>
						<input type="text" name="username" class="form-field" maxlength="20" />
							
						<label for="password" class="form-label">Password: </label>
						<input type="password" class="form-field" name="password1" />
							
						<label for="confirmPassword" class="form-label">Confirm Password: </label>
						<input type="password" class="form-field" name="password2" />

						<label for="email" class="form-label">Email: </label>
						<input type="text" name="email" id="email" />
							
						<input type="submit" name="submit" value="Register" />
					</form>

					<?php
					//}
					

						//echo print_r($_GET);
						echo 'post - ' . print_r($_POST, true);
						//echo print_r($_SESSION);

						//echo $_GET['thanks'];


					if(empty($err) && ($_POST['submit']=='Register')) {
					
					//echo 'Thanks for regsitering';

					echo 'register - ' . print_r($register, true);
					//echo print_r($_POST);

					?>

					<h2> Thanks For Registering </h2>
					<p> Do some more HTML stuff here. </p>

					<?php } //endif ?>
				
				</article>
			</div>
		</div>

<?php include('footer.html'); ?>