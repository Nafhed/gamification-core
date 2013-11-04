<?php
include("./gamify/gamify.php");

//create class instance with database connection
$g = new gamify("localhost", "root", "root", "gamify");

//retrieve our data from POST
$username = $_POST['username'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$email = $_POST['email'];
 
if($password1 != $password2)
    header('Location: registration.php');
 
if(strlen($username) > 30)
    header('Location: registration.php');

//http://www.sourcecodetuts.com/php/15/how-create-secure-registration-page-phpmysql-part-i
$hash = hash('sha256', $password1);
 
function createSalt()
{
    $text = md5(uniqid(rand(), true));
    return substr($text, 0, 3);
}
 
$salt = createSalt();
$password = hash('sha256', $salt . $hash);

//sanitize username
$username = mysql_real_escape_string($username);

//output pdo errors
$g->debug();

//print_r($_POST);

$g->create_user($username, $password, $email);

if($_POST['username']) {
	echo 'Thanks for regsitering';
}
?>