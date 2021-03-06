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
class gamify
{
	private $con;
	private $pref = "gamify_";
	private $err = array();
	
	//create connection
	function __construct($host, $user, $pass, $db, $type = "mysql", $pref = "gamify_"){
		try{
			$this->con = new PDO($type.':host='.$host.';dbname='.$db, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		} 
		catch(PDOException $e){
			$this->err[] = 'Error connecting to MySQL!: '.$e->getMessage();
		}
		$this->con->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT );
		$this->pref = $pref;
		//timezone not set fix
		date_default_timezone_set(date_default_timezone_get());
	}
	//install sql tables
	public function install(){
		//achievements
		$sql = "CREATE TABLE IF NOT EXISTS `".($this->pref)."achievements` (
		`ID` int(11) NOT NULL AUTO_INCREMENT,
		`achievement_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
		`badge_src` text COLLATE utf8_unicode_ci NOT NULL,
		`description` text COLLATE utf8_unicode_ci,
		`amount_needed` int(11) NOT NULL,
		`time_period` int(11) NOT NULL DEFAULT '0',
		`status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
		PRIMARY KEY (`ID`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$this->con->exec($sql);

		//players
		$sql = "CREATE TABLE IF NOT EXISTS `".($this->pref)."users` (
		`userID` int(25) NOT NULL AUTO_INCREMENT,
		`username` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
		`password` text COLLATE utf8_unicode_ci NOT NULL,
		`email` text COLLATE utf8_unicode_ci,
		PRIMARY KEY (`userID`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$this->con->exec($sql);
		
		//levels
		$sql = "CREATE TABLE IF NOT EXISTS `".($this->pref)."levels` (
		`ID` int(11) NOT NULL AUTO_INCREMENT,
		`level_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
		`experience_needed` int(11) NOT NULL,
		PRIMARY KEY (`ID`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;";
		$this->con->exec($sql);
		
		try{
			//default zero level
			$sql = "INSERT INTO `".($this->pref)."levels` (`ID`, `level_name`, `experience_needed`) VALUES (1, '', 0);";
			$this->con->exec($sql);
		}
		catch(PDOException $e){
			$this->err[] = 'Error : '.$e->getMessage();
		}
		
		//user-achievement relation
		$sql = "CREATE TABLE IF NOT EXISTS `".($this->pref)."users_ach` (
		`ID` int(11) NOT NULL AUTO_INCREMENT,
		`userID` int(11) NOT NULL,
		`achID` int(11) NOT NULL,
		`amount` int(11) NOT NULL,
		`last_time` int(11) NOT NULL,
		`status` enum('active','completed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
		PRIMARY KEY (`ID`),
		UNIQUE KEY `userID` (`userID`,`achID`),
		KEY `achID` (`achID`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$this->con->exec($sql);
		
		//users and statistics
		//change ID to userID with foreign key to gamify_users->userID
		$sql = "CREATE TABLE IF NOT EXISTS `".($this->pref)."user_stats` (
		`ID` int(11) NOT NULL AUTO_INCREMENT,
		`username` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
		`experience` int(11) NOT NULL DEFAULT '0',
		`level` int(11) NOT NULL DEFAULT '1',
		PRIMARY KEY (`ID`),
		UNIQUE KEY `username` (`username`),
		KEY `level` (`level`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$this->con->exec($sql);
		
		try{
			//user-achievement relation constraints
			$sql = "ALTER TABLE `".($this->pref)."users_ach`
			ADD CONSTRAINT `".($this->pref)."users_ach_ibfk_2` FOREIGN KEY (`achID`) REFERENCES `".($this->pref)."achievements` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `".($this->pref)."users_ach_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `".($this->pref)."user_stats` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;";
			$this->con->exec($sql);
			
			//user level constraint
			$sql = "ALTER TABLE `".($this->pref)."user_stats`
			ADD CONSTRAINT `".($this->pref)."user_stats_ibfk_1` FOREIGN KEY (`level`) REFERENCES `".($this->pref)."levels` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE;";
			$this->con->exec($sql);
		}
		catch(PDOException $e){
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//output error messages
	public function debug(){
		$this->con->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	}
	
	//get internal error array
	public function get_errors(){
		return $this->err;
	}
	
	/**************************
	* USER MANIPULATIONS
	**************************/
	//create user
	//extend to create user registration/login
	//create userID relation from gamify_users table to gamify_user_stats
	public function create_user($username, $password, $email){ //$username, $password, $email (& dates)
		try{
			//$query = $this->con->prepare("SELECT ID FROM `".($this->pref)."user_stats` WHERE `username` = ?");
			$query = $this->con->prepare("SELECT userID FROM `".($this->pref)."users` WHERE `username` = ?");
			$query->execute(array($username));
			//check if user doesn't exist
			if(!$query->fetch())
			{
				$query->closeCursor();
				$user = true;
				$date = date('Y-m-d');
				//$query = $this->con->prepare("INSERT INTO `".($this->pref)."user_stats` SET `username` = ?");
				$query = $this->con->prepare("INSERT INTO `".($this->pref)."users` SET `username` = ?, `password` = ?, `email` = ?, `join_date` = '$date'");
				$query->execute(array($username, $password, $email));
				$query->closeCursor();
			}
			if($user) {
				//create userID relation to user_stats table
					
					$query = $this->con->prepare("SELECT userID, username FROM `".($this->pref)."users` WHERE `username` = ?");
					$query->execute(array($username));

					//$person = $query->fetch();
					//$query->closeCursor();
					//return $person;

			if($person = $query->fetch()) {
						
				$query = $this->con->prepare("INSERT INTO `".($this->pref)."user_stats` SET `playername` = ?, `userID` = ?");
				$query->execute(array($person["username"], $person["userID"]));
				$query->closeCursor();
				return $person;
				}
			}
			if(!$user) {
				$this->err[] = 'This user already exists, please choose a different username';
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//user login
	public function user_login($username, $password) {
		try{
			$query = $this->con->prepare("SELECT userID, username, email FROM `".($this->pref)."users` WHERE `username` = ? AND password = ?");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($username, $password));
			//$query->closeCursor();
			if($player = $query->fetch())
			{
				$query->closeCursor();
				//$query = $this->con->prepare("INSERT INTO `".($this->pref)."user_stats` SET `username` = ?");
				$query = $this->con->prepare("SELECT * FROM `".($this->pref)."users` WHERE `username` = ".$username." AND `password` = ".$password."");
				$query->setFetchMode(PDO::FETCH_ASSOC);

				//check below code (could be wrong / not working as intended)
				$query->execute();
				//$player["userID"] = $query->fetch();
				$query->closeCursor();
				return $player;
			}
			else
			{
				$this->err[] = 'Sorry, please check your details';
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//get one user information
	public function get_user($username){
		try{
			//$query = $this->con->prepare("SELECT a.ID, a.username, a.experience, b.level_name as level FROM `".($this->pref)."user_stats` as a,`".($this->pref)."levels` as b WHERE a.level = b.ID and `username` = ?");
			//$query = $this->con->prepare("SELECT player.username, player.userID, player_stats.experience, level.level_name as level FROM `".($this->pref)."users` as player,`".($this->pref)."user_stats` as player_stats,`".($this->pref)."levels` as level WHERE player_stats.level = level.ID and player.username = ?");
			
			//improved query to use join levels and stats	
			$query = $this->con->prepare("SELECT player.username, player.userID, player_stats.experience, player_stats.level, levels.level_name FROM `".($this->pref)."users` as player INNER JOIN `".($this->pref)."user_stats` as player_stats ON player.userID = player_stats.userID 
											INNER JOIN `".($this->pref)."levels` as levels ON player_stats.level = levels.ID WHERE player.username = ?");

			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($username));
			if($result = $query->fetch())
			{

				$query->closeCursor();
				//$query = $this->con->prepare("SELECT b.achievement_name, b.badge_src, a.amount as amount_got, b.amount_needed, a.last_time as time, a.status FROM `".($this->pref)."users_ach` as a, `".($this->pref)."achievements` as b WHERE a.achID = b.ID and a.userID = ?");				
				$query = $this->con->prepare("SELECT ach.achievement_name, ach.badge_src, u_ach.amount as amount_got, ach.amount_needed, u_ach.last_time as time, u_ach.status FROM `".($this->pref)."users_ach` as u_ach, `".($this->pref)."achievements` as ach WHERE u_ach.achID = ach.ID and u_ach.userID = ?");
				$query->setFetchMode(PDO::FETCH_ASSOC);
				$query->execute(array($result['userID']));
				$result["achievements"] = $query->fetchAll();
				$query->closeCursor();
				return $result;
			}
			else
			{
				$this->err[] = 'There is no user with username '. $username;
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//get multiple user information
	public function get_users($ord = "", $desc = false, $limit = 0){
		$add = "";
		if(in_array($ord, array("username", "experience")))
		{
			$add .= " ORDER BY ".$ord;
			if($desc)
			{
				$add .= " DESC";
			}
		}
		$limit = intval($limit);
		if($limit > 0)
		{
			$add .= " LIMIT ".$limit;
		}
		try{
			//$query = $this->con->prepare("SELECT a.ID, a.username, a.experience, b.level_name as level FROM `".($this->pref)."user_stats` as a,`".($this->pref)."levels` as b WHERE a.level = b.ID".$add);
			$query = $this->con->prepare("SELECT player_stats.ID, player_stats.playername, player_stats.experience, player_level.level_name as level FROM `".($this->pref)."user_stats` as player_stats,`".($this->pref)."levels` as player_level WHERE player_stats.level = player_level.ID".$add);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute();
			if($result = $query->fetchAll())
			{
				$query->closeCursor();
				return $result;
			}
			else
			{
				$this->err[] = 'There are no users';
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	public function leaderboard($ord = "", $desc = false, $limit = 0){
		$add = "";
		if(in_array($ord, array("username", "experience")))
		{
			$add .= " ORDER BY ".$ord;
			if($desc)
			{
				$add .= " DESC";
			}
		}
		$limit = intval($limit);
		if($limit > 0)
		{
			$add .= " LIMIT ".$limit;
		}
		try{
			//$query = $this->con->prepare("SELECT a.ID, a.username, a.experience, b.level_name as level FROM `".($this->pref)."user_stats` as a,`".($this->pref)."levels` as b WHERE a.level = b.ID".$add);
			$query = $this->con->prepare("SELECT player_stats.ID, player_stats.playername, player_stats.experience, player_level.level_name as level FROM `".($this->pref)."user_stats` as player_stats,`".($this->pref)."levels` as player_level WHERE player_stats.level = player_level.ID AND player_stats.last_updated > DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -1 MONTH) ".$add);
			//print_r($query);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute();
			if($result = $query->fetchAll())
			{
				$query->closeCursor();
				return $result;
			}
			else
			{
				$this->err[] = 'There are no users';
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//edit users info
	public function edit_user($id, $username = "", $experience = "", $level = ""){
		try{
			$query = $this->con->prepare("SELECT * FROM `".($this->pref)."user_stats` WHERE `ID` = ?");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($id));
			if($user = $query->fetch())
			{
				$query->closeCursor();
				$username = ($username == "") ? $user["username"] : $username;
				$experience = ($experience == "") ? $user["experience"] : $experience;
				$level = ($level == "") ? $user["level"] : $level;
				$query = $this->con->prepare("UPDATE `".($this->pref)."user_stats` SET `username` = ?, `experience` = ?, `level` = ? WHERE `ID` = ?");
				$query->execute(array($username, $experience, $level, $id));
				$query->closeCursor();
			}
			else
			{
				$this->err[] = 'User with ID '.$id.' does not exist';
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//delete user
	public function delete_user($username){
		try{
			$query = $this->con->prepare("DELETE FROM `".($this->pref)."user_stats` WHERE `username` = ?");
			$query->execute(array($username));
			$query->closeCursor();
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	
	/**************************
	* LEVEL MANIPULATIONS
	**************************/
	//create new level
	public function create_level($name, $exp){
		try{
			$query = $this->con->prepare("INSERT INTO `".($this->pref)."levels` SET `level_name` = ?, `experience_needed` = ?");
			$query->execute(array($name, $exp));
			$query->closeCursor();
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//get level
	public function get_level($id){
		try{
			$query = $this->con->prepare("SELECT * FROM `".($this->pref)."levels` WHERE `ID` = ?");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($id));
			if($result = $query->fetch())
			{
				$query->closeCursor();
				return $result;
			}
			else
			{
				$this->err[] = 'There are no level with ID '.$id;
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	// get the next level
	public function get_next_level($id){
		try{
			$query = $this->con->prepare("SELECT * FROM `".($this->pref)."levels` WHERE `ID` > ? LIMIT 1");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($id));
			if($result = $query->fetch())
			{
				$query->closeCursor();
				return $result;
			}
			else
			{
				$this->err[] = 'There are no level with ID '.$id;
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//get levels
	public function get_levels($ord = "", $desc = false, $limit = 0){
		$add = "";
		if(in_array($ord, array("level_name", "experience_needed")))
		{
			$add .= " ORDER BY ".$ord;
			if($desc)
			{
				$add .= " DESC";
			}
		}
		$limit = intval($limit);
		if($limit > 0)
		{
			$add .= " LIMIT ".$limit;
		}
		try{
			$query = $this->con->prepare("SELECT * FROM `".($this->pref)."levels`".$add);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute();
			if($result = $query->fetchAll())
			{
				$query->closeCursor();
				return $result;
			}
			else
			{
				$this->err[] = 'There are no levels';
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//edit level
	public function edit_level($id, $name = "", $experience = ""){
		try{
			$query = $this->con->prepare("SELECT * FROM `".($this->pref)."levels` WHERE `ID` = ?");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($id));
			if($level = $query->fetch())
			{
				$query->closeCursor();
				$name = ($name == "") ? $level["level_name"] : $name;
				$experience = ($experience == "") ? $level["experience_needed"] : $experience;
				$query = $this->con->prepare("UPDATE `".($this->pref)."levels` SET `level_name` = ?, `experience_needed` = ? WHERE `ID` = ?");
				$query->execute(array($name, $experience, $id));
				$query->closeCursor();
			}
			else
			{
				$this->err[] = 'There are no level with ID '.$id;
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//delete level
	public function delete_level($id){
		if($id != 1)
		{
			try{
				$query = $this->con->prepare("DELETE FROM `".($this->pref)."user_stats` WHERE `ID` = ?");
				$query->execute(array($id));
				$query->closeCursor();
				$query = $this->con->prepare("UPDATE `".($this->pref)."user_stats` SET `level` = '1' WHERE `level` = ?");
				$query->execute(array($id));
				$query->closeCursor();
			}
			catch(PDOException $e) {
				$this->err[] = 'Error : '.$e->getMessage();
			}
		}
		else
		{
			$this->err[] = 'Can\'t delete default level. It can only be edited';
		}
	}
	
	/**************************
	* ACHIEVEMENT MANIPULATIONS
	**************************/
	//create new achievement
	public function create_achievement($name, $amount, $period = 0, $badge = "", $description = ""){
		try{
			$query = $this->con->prepare("INSERT INTO `".($this->pref)."achievements` SET `achievement_name` = ?, `amount_needed` = ?, `time_period` = ?, `badge_src` = ?, `description` = ?");
			$query->execute(array($name, $amount, $period, $badge, $description));
			$query->closeCursor();
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
			
	}
	//get achievement
	public function get_achievement($id){
		try{
			$query = $this->con->prepare("SELECT * FROM `".($this->pref)."achievements` WHERE `ID` = ?");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($id));
			if($result = $query->fetch())
			{
				$query->closeCursor();
				return $result;
			}
			else
			{
				$this->err[] = 'There are no achievements with ID '.$id;
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//get achievements
	public function get_achievements($ord = "", $desc = false, $limit = 0){
		$add = "";
		if(in_array($ord, array("achievement_name", "amount_needed")))
		{
			$add .= " ORDER BY ".$ord;
			if($desc)
			{
				$add .= " DESC";
			}
		}
		$limit = intval($limit);
		if($limit > 0)
		{
			$add .= " LIMIT ".$limit;
		}
		try{
			$query = $this->con->prepare("SELECT * FROM `".($this->pref)."achievements`".$add);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute();
			if($result = $query->fetchAll())
			{
				$query->closeCursor();
				return $result;
			}
			else
			{
				$this->err[] = 'There are no achievements';
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//edit achievement
	public function edit_achievement($id, $name = "", $amount = "", $period = "", $badge = "", $description = "", $status = "active"){
		try{
			$query = $this->con->prepare("SELECT * FROM `".($this->pref)."achievements` WHERE `ID` = ?");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($id));
			if($ach = $query->fetch())
			{
				$query->closeCursor();
				$name = ($name == "") ? $ach["achievement_name"] : $name;
				$badge = ($badge == "") ? $ach["badge_src"] : $badge;
				$description = ($description == "") ? $ach["description"] : $description;
				$amount = ($amount == "") ? $ach["amount_needed"] : $amount;
				$period = ($period == "") ? $ach["time_period"] : $period;
				$query = $this->con->prepare("UPDATE `".($this->pref)."achievements` SET `achievement_name` = ?, `badge_src` = ?, `amount_needed` = ?, `time_period` = ?, `status` = ? WHERE `ID` = ?");
				$query->execute(array($name, $badge, $amount, $period, $status, $id));
				$query->closeCursor();
			}
			else
			{
				$this->err[] = 'There are no achievements with ID '.$id;
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//delete achievement
	public function delete_achievement($id){
		try{
			$query = $this->con->prepare("DELETE FROM `".($this->pref)."achievements` WHERE `ID` = ?");
			$query->execute(array($id));
			$query->closeCursor();
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}
	//disable achievement
	public function disable_achievement($id){
		$this->edit_achievement($id, "", "", "", "", "", "inactive");
	}
	//enable achievement
	public function enable_achievement($id){
		$this->edit_achievement($id, "", "", "", "", "", "active");
	}
	
	/**************************
	* POST DIRECTORY
	**************************/
	//get recipe information
	public function get_posts() {
		try{
			$query = $this->con->prepare("SELECT post_id, post_slug, post_title, post_content, post_date FROM `".($this->pref)."posts` ORDER BY post_id DESC");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			//$query->execute(array($id));
			$query->execute();
			if($posts = $query->fetchAll()) {
				$query->closeCursor;
				return $posts;
			}
			else 
			{
				//throw an error
				$this->err[] = 'Error post unavailable';
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error'.$e->getMessage();
		}
	}

	//view individual post
	public function view_post($slug) {
		try {
			$query = $this->con->prepare("SELECT post_id, post_title, post_content, post_date, post_experience FROM `".($this->pref)."posts` WHERE `post_slug` = ?");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($slug));

			if($result = $query->fetch()) {
				
				$query->closeCursor;
				return $result;
				
			}
			else 
			{
				//throw an error
				$this->err[] = 'There are no posts';
			}
		}
		catch(PDOException $e) {
			$this->error = 'Error no posts'.$e->getMessage();
		}
	}

	public function post_category($post_id) {
		try {
			$query = $this->con->prepare("SELECT post_category.category_id, category_slug, category_name FROM `".($this->pref)."posts_category` as post_category, `".($this->pref)."posts_directory` as post_directory WHERE post_category.category_id = post_directory.category_id AND post_directory.post_id = ? ");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($post_id));

			if($categories = $query->fetchAll()) {
				$query->closeCursor();
				return $categories;
			}
			else 
			{
			//throw an error
			$this->err[] = 'There are no posts';
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error no posts'.$e->getMessage();
		}
	}

	public function get_category($cat_slug) {
		$query = $this->con->prepare("SELECT category_id, category_name, category_slug FROM `".($this->pref)."posts_category` WHERE category_slug = ?");
		$query->setFetchMode(PDO::FETCH_ASSOC);
		$query->execute(array($cat_slug));

		if($category = $query->fetch()) {
			$query->closeCursor();
			return $category;
		}
	}

	//get list of all categories for menu
	public function get_categories() {
		$query = $this->con->prepare("SELECT * FROM `".($this->pref)."posts_category`");
		$query->setFetchMode(PDO::FETCH_ASSOC);
		$query->execute();

		if($category = $query->fetchAll()) {
			$query->closeCursor();
			return $category;
		}
	}

	//view posts based on their category
	public function category_posts($cat) {
		try {
			$query = $this->con->prepare("SELECT post.post_id, post.post_title, post.post_slug, post.post_content, post.post_date FROM `".($this->pref)."posts` as post, `".($this->pref)."posts_directory` as post_directory WHERE post.post_id = post_directory.post_id AND post_directory.category_id = ? ORDER BY post.post_id DESC");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($cat));

			if($result = $query->fetchAll()) {
				$query->closeCursor;
				return $result;
			}
		}
		catch(PDOException $e) {
			$this->error= 'Error no posts'.$e->getMessage();
		}
	}

	//complete a recipe marking as complete
	public function complete_recipe($userID, $postID) {
		try {
			$query = $this->con->prepare("SELECT userID, post_id FROM `".($this->pref)."user_posts` WHERE `userID` = ? AND `post_id` = ?");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($userID, $postID));

				# if user hasn't completed this post
				if(!$query->fetch()) {
					
					//$query->closeCursor();
					//insert into gamify_user_posts table
					//$query = $this->con->prepare("INSERT INTO `".($this->pref)."user_posts` SET `userID` = ? AND `post_id` = ?");
										//insert into gamify_user_posts table
					$query = $this->con->prepare("INSERT INTO `".($this->pref)."user_posts` (`userID`, `post_id`) VALUES (?, ?)");
					//echo print_r($result, true);

					$query->execute(array($userID, $postID));
					//$query->execute(array($result['userID'], $result['post_id']));
					$query->closeCursor();
				}
				else
				{
					# user has complete the post
					$this->err[] = 'You have already completed this post.';
				}
			//}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error '.$e->getMessage();
		}
	}

	/**************************
	* USER INTERACTION
	**************************/
	//add experience to user
	public function add_experience($username, $exp){
		try{
			//get info
			//$query = $this->con->prepare("SELECT * FROM `".($this->pref)."user_stats` WHERE `username` = ?");
			$query = $this->con->prepare("SELECT * FROM `".($this->pref)."users` WHERE `username` = ?");		
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($username));
			//get current date
			$date = date('Y-m-d G-i-s');
			if($row = $query->fetch())
			{
				$query->closeCursor();
				$exp += $row["experience"];
				//check if new level
				$query = $this->con->prepare("SELECT * FROM `".($this->pref)."levels` WHERE `experience_needed` = (SELECT max(`experience_needed`) FROM `".($this->pref)."levels` WHERE `experience_needed` <= ?)");
				$query->execute(array($exp));
				$query->setFetchMode(PDO::FETCH_ASSOC);
				$level = $query->fetch();
				if($level && $level["ID"] != $row["level"])
				{
					$query->closeCursor();
					//update experience and level info

					// ID to userID
					$query = $this->con->prepare("UPDATE `".($this->pref)."user_stats` SET `experience` = ?, `level` = ?, `last_updated` = '$date' WHERE `userID` = ?");
					$query->execute(array($exp, $level["ID"], $row["userID"]));
					$query->closeCursor();
					return $level;
				}
				else
				{
					$query->closeCursor();
					//update experience info

					// ID to userID
					$query = $this->con->prepare("UPDATE `".($this->pref)."user_stats` SET `experience` = ?, `last_updated` = '$date' WHERE `userID` = ?");
					$query->execute(array($exp, $row["userID"]));
					$query->closeCursor();
				}
			}
			else
			{	
				//user doesn't exist, create user and add experience
				$this->create_user($username, $password, $email);
				$this->add_experience($username, $exp);
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
		return false;
	}


	public function check_achievement($player){
		//Achievement: First post
		try {
				$query = $this->con->prepare("SELECT * FROM `".($this->pref)."user_posts` WHERE userID = ?");
				$query->setFetchMode(PDO::FETCH_ASSOC);
				$query->execute(array($player));
				if($result = !$query->fetch())
				{
					$query->closeCursor();
					$query = $this->con->prepare("SELECT * FROM `".($this->pref)."achievements` WHERE `ID` = 5");
					$query->setFetchMode(PDO::FETCH_ASSOC);
					$query->execute();
					if($ach = $query->fetch())
					{
						$query->closeCursor();
						return $ach;
					}
				}
			}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}

		//Achievement:
		try {
				$query = $this->con->prepare("SELECT * FROM `".($this->pref)."user_posts` as user_posts, `".($this->pref)."posts_directory` as post_directory WHERE user_posts.userID = ? AND post_directory.category_id = 4");
				$query->setFetchMode(PDO::FETCH_ASSOC);
				$query->execute(array($player));

				if($result = $query->fetch())
				{
					$query->closeCursor();
					$query = $this->con->prepare("SELECT * FROM `".($this->pref)."achievements` WHERE `ID` = 6");
					$query->setFetchMode(PDO::FETCH_ASSOC);
					$query->execute();
					if($ach = $query->fetch())
					{
						$query->closeCursor();
						return $ach;
					}
				}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}

	//check achievement criteria
	/*public function check_achievement() {
		try{
			$query = $this->con->prepare("SELECT * FROM `".($this->pref)."user_posts`");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute();
			//$query->execute();
			if($ach = $query->fetchAll()) {
				$query->closeCursor();
				return $ach;
			}
			else 
			{
				//throw an error
				$this->err[] = 'Error post unavailable';
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error'.$e->getMessage();
		}
	}
	public function check_achievement($player) {
		try{
			# complete first post achievement
			$query = $this->con->prepare("SELECT userID FROM `".($this->pref)."user_posts` WHERE `userID` = ?");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($player));
			$query->fetchAll();
			if($row = !$query->fetch()) {
				echo 'testing';
					//$query->closeCursor();
					$query = $this->con->prepare("SELECT * FROM `".($this->pref)."achievements` WHERE `ID` = ?");
					$query->setFetchMode(PDO::FETCH_ASSOC);
					$query->execute(array('5'));
					$query->fetch();
					$query->closeCursor();
					return $row;
				} else {
				$this->err[] = 'This post has been completed';
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
	}*/

	//times of completed actions for achievements
	public function action($username, $achievement, $amount = 1){
		try{
			$query = $this->con->prepare("SELECT * FROM `".($this->pref)."user_stats` WHERE `playername` = ?");
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$query->execute(array($username));
			if($user = $query->fetch())
			{
				$query->closeCursor();
				$query = $this->con->prepare("SELECT * FROM `".($this->pref)."achievements` WHERE `ID` = ?");
				$query->setFetchMode(PDO::FETCH_ASSOC);
				$query->execute(array($achievement));
				if($ach = $query->fetch())
				{
					//checking if achievement is enabled
					if($ach["status"] == "active")
					{
						$now = time();
						$complete = false;
						$query->closeCursor();
						$query = $this->con->prepare("SELECT * FROM `".($this->pref)."users_ach` WHERE `userID` = ? and `achID` = ?");
						$query->setFetchMode(PDO::FETCH_ASSOC);
						$query->execute(array($user["ID"], $ach["ID"]));
						if($rel = $query->fetch())
						{
							$query->closeCursor();
							//checking if achievement is not completed yet
							if($rel["status"] == "active")
							{
								$amount += $rel["amount"];
								//checking if needed period of time is passed
								if($now >= $rel["last_time"] + $ach["time_period"])
								{
									//checking if no we have completed an achievement
									if($amount >= $ach["amount_needed"])
									{
										//complete achievement
										$query = $this->con->prepare("UPDATE `".($this->pref)."users_ach` SET `amount` = ?, `status` = 'completed', `last_time` = ? WHERE ID = ?");
										$complete = true;
									}
									else
									{
										//update existing relation
										$query = $this->con->prepare("UPDATE `".($this->pref)."users_ach` SET `amount` = ?, `last_time` = ? WHERE `ID` = ?");
									}
									$query->execute(array($amount, $now, $rel["ID"]));
									$query->closeCursor();
									if($complete)
									{
										return $ach;
									}
								}
							}
						}
						else
						{
							$query->closeCursor();
							$status = "active";
							if($amount >= $ach["amount_needed"])
							{
								$status = "completed";
								$complete = true;
							}
							//create relation
							$query = $this->con->prepare("INSERT INTO `".($this->pref)."users_ach` SET `userID` = ?, `achID` = ?, `amount` = ?, `last_time` = ?, `status` = ?");
							//$user["ID"] to $user["userID"]
							$query->execute(array($user["userID"], $ach["ID"], $amount, $now, $status));
							$query->closeCursor();
							if($complete)
							{
								return $ach;
							}
						}
					}
				}
				else
				{	
					$this->err[] = "Achievement with ID ".$achievement." does not exist";
				}
			}
			else
			{	
				//user doesn't exist, create user and perform action
				$this->create_user($username);
				$this->action($username, $achievement, $amount);
			}
		}
		catch(PDOException $e) {
			$this->err[] = 'Error : '.$e->getMessage();
		}
		return false;
	}
	
	//free resources
	function __destruct(){
		$this->con = NULL;
	}
}
?>