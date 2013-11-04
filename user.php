<?php

if(empty($_SESSION['username'])) {
//not logged in
	
?>
<section id="playerActive" class="player">
	<p> Welcome, Guest </p>
	<p> You are not logged in, please <span id="openLogin">login</span> </p>
</section>

<?php }//endif ?>

<?php

if(!empty($_SESSION['username'])) {
//logged in
	
?>

<section id="playerActive" class="player">
	<p> Welcome, <a href="profile.php?user=<?php $username ?>"><?php echo $player_name; ?></a> </p>
	<a href="?op=logout">Log out</a>
</section>

<?php }//endif ?>