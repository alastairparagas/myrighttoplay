<?php
require('../format/session.php');
require('../format/facebook_config.php');
require('../format/connect.php');
require('../format/functions.php');

$time_spent = time() - $_SESSION['time_start'];
if($time_spent >= 1200){
	// One gold coin per 20 minutes of session time. Round down fraction, and get remainders into time_uncalculated.
	$whole_gold = floor($time_spent/1200);
	$bits_gold = $time_spent % 1200;
	$db->query("UPDATE users_more SET user_gold=user_gold + $whole_gold WHERE user_id = '" . MRTP_USER_ID . "'");
	$db->query("UPDATE users_more2 SET time_uncalculated=time_uncalculated + $bits_gold WHERE user_id = '" . MRTP_USER_ID . "'");
}else{
	//If the user hasn't been spending more than 20 minutes on his session, save the spending duration
	$db->query("UPDATE users_more2 SET time_uncalculated=time_uncalculated + $time_spent WHERE user_id = '" . MRTP_USER_ID . "'");
	//How many uncalculated time do we currently have? Has it been about or over 1200 seconds since last add? If it is, award gold.
	$gold_query = $db->query("SELECT time_uncalculated FROM users_more2 WHERE user_id = '" . MRTP_USER_ID . "'");
	$gold_fetch = $gold_query->fetch_array();
	$time_uncalculated = $gold_fetch['time_uncalculated'];
	if($time_uncalculated >= 1200){
		$whole_gold = floor($time_uncalculated/1200);
		$bits_gold = $time_uncalculated % 1200;
		$db->query("UPDATE users_more SET user_gold=user_gold + $whole_gold WHERE user_id = '" . MRTP_USER_ID . "'");
		$db->query("UPDATE users_more2 SET time_uncalculated=$bits_gold WHERE user_id = '" . MRTP_USER_ID . "'");	
	}
}

if($facebook_id != 0){
$facebook->destroySession();
session_destroy();
header("Location: ". ABSOLUTE_URL . "homepage.php");

}else{
session_destroy();
header("Location: ". ABSOLUTE_URL . "homepage.php");
}

?>