<?php
require('../format/connect.php');
require('../format/facebook_config.php');
require('../format/session.php');
require('../format/functions.php');

$f_id = $db->real_escape_string(strip_tags(trim($_POST['f_id'])));

//Check if user being requested as a friend exists
$p_query=$db->query("SELECT `user_id` FROM users WHERE `user_id`='$f_id'");
$p_count=$p_query->num_rows;
$p_query->free();

if($p_count != '1'){
die("That user does not exist.");
}

//Check if user is logged in.
if(MRTP_USER_ID == ''){
die("You must be logged in to befriend someone on MRTP!");
}

//Check that the user is not befriending himself.
if(MRTP_USER_ID == $f_id){
die("You cannot befriend yourself!");
}

//Check that the user is not yet friends with the requested user
$fl_query=$db->query("SELECT user_friend FROM users_more WHERE user_id='" . MRTP_USER_ID . "'");
list($fl_friends)=$fl_query->fetch_array();
$fl_friends=json_decode($fl_friends, true);
if(!$fl_friends){
	$fl_friends=array();
}
if(in_array($f_id, $fl_friends)){
	die("You are already friends with the requested user!");
}
$fl_query->free();

//Check that a request hasn't been already sent
$m_query=$db->query("SELECT user_mail FROM users_more2 WHERE user_id='$f_id'");
list($m_array) = $m_query->fetch_array();
$m_array=json_decode($m_array, true);
if(!$m_array){$m_array=array();}
$m_query->free();

if(in_array(array('Friend Request', MRTP_USER_ID), $m_array)){
	die("A friend request has already been sent before.");
}


//If script still continues, then add friend request to messages of requested individual.
//Associative with multidimensional array. Key for friend request will be 'fr_' and the requesting user's userid.
$m_array[time()] = array('Friend Request', MRTP_USER_ID);
$m_array=json_encode($m_array);
$db->query("UPDATE users_more2 SET user_mail='$m_array' WHERE user_id='$f_id'");
die("Sent a friend request!");

?>
