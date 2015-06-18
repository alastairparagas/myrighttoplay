<?php
require('../format/connect.php');
require('../format/facebook_config.php');
require('../format/session.php');

$bio = $db->real_escape_string(trim(strip_tags($_POST['bio'])));
$form_errors = array();

if($bio == ''){
	$form_errors["bio"][]="No bio written!";
}else{
	$db->query("UPDATE users_more SET user_bio='$bio' WHERE user_id='" . MRTP_USER_ID . "'");
	$form_errors["success"] = "Updated bio succesfully!";
}

echo json_encode($form_errors);

?>
