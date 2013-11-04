<?php

session_name('gamify_login');
	
session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks


session_start();


//echo print_r($_POST, true);




	if(isset($_GET['op']) && ($_GET['op'] == 'logout')) {
		$_SESSION = array();
		session_destroy();
		header('Location: index.php');
		exit;
	}

	include("./gamify/gamify.php");
	$g = new gamify("localhost", "root", "root", "gamify");

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
	
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<!--[if IE ]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->

	<?php 
			//get the filename and display as the page title.
			$url = $_SERVER['REQUEST_URI']; 
			$title = basename($url, ".php");

			$title = ucfirst($title);
	?>

	<title> The Core || <?php echo $title; ?></title>

	<meta name="title" content="Gamification ||  " <?php echo $title; ?> >

	<meta name="description" content="Gamification Website" />
	<!--Google will often use this as its description of your page/site. Make it good.-->

	<meta name="author" content="Nathan Brettell" />

	<meta name="viewport" content="width=device-width" />
	
	<link rel="shortcut icon" href="favion.png" />
	<link rel="apple-touch-icon" href="favicon.png" />

	<!-- stylesheets -->
	<link rel="stylesheet" href="css/reset.css" />
	<link rel="stylesheet" href="css/style.css" />
	
	<!-- This is an un-minified, complete version of Modernizr. 
		 Before you move to production, you should generate a custom build that only has the detects you need.
	<script src="/js/modernizr-2.6.2.dev.js"></script> -->

</head>
<body>

<div id="page-wrapper">
<header id="header" role="header">
<?php echo 'fuck sake ' . print_r($_SESSION, true); ?>
	<?php require_once('login_form.php'); ?>
<?php echo 'player ' . print_r($player, true); ?>

	<hgroup>
		<h1> <a href="index.php">Core</a> </h1>
		<h6> Work hard. Play hard. </h6>
	</hgroup>

	<?php include('navigation.html'); ?>

	
</header>