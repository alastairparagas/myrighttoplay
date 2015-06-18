<?php
require('../format/connect.php');
require('../format/facebook_config.php');
require('../format/session.php');
require('../format/functions.php');

$friend_id = $db->real_escape_string(strip_tags(trim($_POST['friend_id'])));
$mail_identifier = $db->real_escape_string(strip_tags(trim($_POST['mail_identifier'])));
$mail_task = $db->real_escape_string(strip_tags(trim($_POST['mail_task'])));


//We're handling an adding friend request. Proceed.
if($friend_id){
	//Get current logged in person's friends list
	$p_query = $db->query("SELECT user_friend,user_mail FROM users_more,users_more2 WHERE users_more.user_id='" . MRTP_USER_ID . "' AND users_more.user_id=users_more2.user_id");
	$f_query = $db->query("SELECT user_friend FROM users_more WHERE user_id='$friend_id'");
	list($user_friend,$user_mail) = $p_query->fetch_array();
	list($other_friend) = $f_query->fetch_array();
	
	if($user_mail){
		$user_mail=json_decode($user_mail, true);
	}else{
		$user_mail=array();
	}
	
	if($user_friend){
		$user_friend=json_decode($user_friend, true);
	}else{
		$user_friend=array();
	}

	if($other_friend){
		$other_friend=json_decode($other_friend, true);
	}else{
		$other_friend=array();
	}
	
	if(!in_array($friend_id, $user_friend)){
		// Add appropriate friend ids both ways - to the requestee and the receiver
		$user_friend[]=$friend_id;
		$other_friend[]=MRTP_USER_ID;
		$user_friend=json_encode($user_friend);
		$other_friend=json_encode($other_friend);
		
		unset($user_mail[$mail_identifier]);
		$user_mail=json_encode($user_mail);
		$p_query = $db->query("UPDATE users_more SET user_friend='$user_friend' WHERE user_id='" . MRTP_USER_ID . "'");
		$p_query = $db->query("UPDATE users_more2 SET user_mail='$user_mail' WHERE user_id='". MRTP_USER_ID . "'");
		$f_query = $db->query("UPDATE users_more SET user_friend='$other_friend' WHERE user_id='$friend_id'");
		$p_query->free;
		$f_query->free;
		die("Succesfully added as friend");
	}else{
		die("You already have this individual as a friend");
	}
}

if($mail_task == 'delete'){
	$mail_query = $db->query("SELECT user_mail FROM users_more2 WHERE user_id='" . MRTP_USER_ID . "'");
	list($mail_list) = $mail_query->fetch_array();
	if($mail_list){
		$mail_list = json_decode($mail_list, true);
	}else{
		$mail_list = array();
	}
	
	unset($mail_list[$mail_identifier]);
	$mail_list = json_encode($mail_list);
	$mail_query = $db->query("UPDATE users_more2 SET user_mail='$mail_list' WHERE user_id='" . MRTP_USER_ID . "'");
	$mail_query->free;
	die("Mail was deleted. Refresh to see updated mail center.");
}



?>