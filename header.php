<?php

session_name('gamify_login');
	
session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks


session_start();


// Define base url
define('BASE_URL', '/DMP/Core/');



	if(isset($_GET['op']) && ($_GET['op'] == 'logout')) {
		$_SESSION = array();
		session_destroy();
		header('Location: /DMP/Core/index.php');
		exit;
	}

	//load gamify class library into application
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

	<meta name="title" content="Gamification || <?php echo $title; ?> ">

	<meta name="description" content="Gamification Website" />
	<!--Google will often use this as its description of your page/site. Make it good.-->

	<meta name="author" content="Nathan Brettell" />

	<meta name="viewport" content="width=device-width" />
	
	<link rel="shortcut icon" href="favion.png" />
	<link rel="apple-touch-icon" href="favicon.png" />

	<!-- stylesheets -->
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/reset.css" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css" />

	<!-- jQuery Load -->
	<script type='text/javascript' src='<?php echo BASE_URL; ?>js/jquery.min.js'></script>
	<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>
	
	<!-- JavaScript -->
	<script type='text/javascript' src='<?php echo BASE_URL; ?>js/script.js'></script>

	<!-- This is an un-minified, complete version of Modernizr. 
		 Before you move to production, you should generate a custom build that only has the detects you need.
	<script src="/js/modernizr-2.6.2.dev.js"></script> -->

</head>
<body>

<div id="page-wrapper">
<header id="header" role="header">
	<?php require_once('login_form.php'); ?>
	
	<hgroup>
		<h1> <a href="<?php echo BASE_URL; ?>">C <span id="logo"><img src="/DMP/Core/images/core-apple-icon.png" /></span> re</a> </h1>
		<h6> Cooking with a twist. Fun with food. </h6>
	</hgroup>

	<?php include('navigation.php'); ?>

	
</header>