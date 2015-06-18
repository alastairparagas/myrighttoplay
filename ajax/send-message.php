<?php
require('../format/session.php');
require('../format/facebook_config.php');
require('../format/connect.php');
require('../format/functions.php');

$send_profileid = strip_tags(trim($_POST['profile_id']));
$send_title = strip_tags(trim($_POST['title']));
$send_message = strip_tags(trim($_POST['message']));
$form_errors=array();

if(!$profile_id){
	$form_errors['errors'] = "You did not send a message to a valid user. Please send a message to a valid user.";
}

if(!$send_title){
	$form_errors['title'] = "You did not include a title in your message.";
}

if(!$send_message){
	$form_errors['message'] = "You did not write text in your message.";
}

if(MRTP_USER_ID == $profile_id){
	$form_errors['errors'] = "You cannot send a message to yourself. That is just sad.";
}

if(count($form_errors)==0){
	$mail_query = $db->query("SELECT user_mail FROM users_more2 WHERE user_id='$send_profileid'");
	list($mail_list) = $mail_query->fetch_array();
	if($mail_list){
		$mail_list = json_decode($mail_list, true);
	}else{
		$mail_list = array();
	}
	$mail_list[time()]=array($send_title, MRTP_USER_ID, $send_message);
	$mail_list = json_encode($mail_list);
	$db->query("UPDATE users_more2 SET user_mail='$mail_list' WHERE user_id='$send_profileid'");
	$form_errors['success'] = "Message succesfully sent. ";
	echo json_encode($form_errors);
}else{
	echo json_encode($form_errors);
}

?>