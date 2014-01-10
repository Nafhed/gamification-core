<?php
/************************************************************* 
 * This script is developed by Arturs Sosins aka ar2rsawseen, http://webcodingeasy.com 
 * Feel free to distribute and modify code, but keep reference to its creator 
 * 
 * Gamify class allows to implement game logic into PHP aplications. 
 * It can create needed tables for storing information on most popular database platforms using PDO. 
 * It also can add users, define levels and achievements and generate user statistics and tops.
 * Then it is posible to bind class functions to user actions, to allow them gain experience and achievements.
 * 
 * For more information, examples and online documentation visit:  
 * http://webcodingeasy.com/PHP-classes/Implement-game-logic-to-your-web-application
**************************************************************/
include("header.php");
//$g = new gamify("localhost", "root", "root", "gamify");


?>
<script type='text/javascript' src='<?php echo BASE_URL; ?>js/leaderboard_update.js'></script>

<div id="loading"><img src="images/ajax-loading.gif" alt="ajax load animation" /></div>

<section id="leaderboard-update" class="leaderboard">
</section>

<?php


include('footer.html');
?>