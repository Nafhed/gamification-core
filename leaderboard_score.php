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
//include("header.php");
include("./gamify/gamify.php");
$g = new gamify("localhost", "root", "root", "gamify");


$g->debug();
if(isset($_GET["sort"]))
{
	if(isset($_GET["desc"]))
	{
		$users = $g->get_users($_GET["sort"], true);
	}
	else
	{
		$users = $g->get_users($_GET["sort"]);
	}
}
else
{
	$users = $g->get_users();
}

//echo print_r($users, true);
echo "<table id='leaderboard' border='0' cellpadding='5' cellspacing='5'>";
echo "<tr><th>";
if(isset($_GET["sort"]) && $_GET["sort"] == "playername" && !isset($_GET["desc"]))
{
	echo "<a href='?sort=username&desc=true'>username</a>";
}
else
{
	echo "<a href='?sort=username'>username</a>";
}
echo "</th><th>";
if(isset($_GET["sort"]) && $_GET["sort"] == "experience" && !isset($_GET["desc"]))
{
	echo "<a href='?sort=experience&desc=true'>experience</a>";
}
else
{
	echo "<a href='?sort=experience'>experience</a>";
}
//echo "</th><th>level</th><th>options</th></tr>";
foreach($users as $user)
{
	echo "<td id=".$user['ID'].">";
	echo "<tr class='user-details'>";
	echo "<td class='username'>".$user["playername"]."</td>";
	echo "<td class='experience'>".$user["experience"]."</td>";
	echo "<td class='level'>".$user["level"]."</td>";
	//echo "<td><form method='post'>";

	//if(isset($_POST["id"]) && $_POST["id"] == $user["playername"])
	//{
		//echo "<input type='submit' value='Hide info'/>";
	//}
	//else
	//{
		//echo "<input type='hidden' name='id' value='".$user["playername"]."'/><input type='submit' value='Show info'/>";
	//}
	//echo "</form></td>";
	//echo "</tr>";

	//if(isset($_POST["id"]) && $_POST["id"] == $user["playername"])
	//{
		echo "<td class='user-info' colspan='4'>";
		$info = $g->get_user($user["playername"]);
		//echo print_r($info, true);
		echo "<span>Info about ".$info["username"]."</span>";
		echo "<p>Username: ".$info["username"]."</p>";
		echo "<p>Experience: ".$info["experience"]."</p>";
		echo "<p>Level: ".$info["level"]."</p>";
		echo "<p>Achievements: <ul>";
			foreach($info["achievements"] as $val)
			{
				if($val["status"] == "completed")
				{
					echo "<li>".$val["achievement_name"]."<br/>Badge: <img src='".$val["badge_src"]."' width='50px' border='0'/><br/>Earned : ".date("r", $val["time"])."</li>";
				}
			}
		echo "</ul></p>";
		echo "</td></tr></td>";
	//}
}
echo "</table>";

//include('footer.html');
?>