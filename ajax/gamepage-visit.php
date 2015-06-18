<?php
require('../format/session.php');
require('../format/connect.php');
require('../format/facebook_config.php');
require('../format/functions.php');

$g_id = $db->real_escape_string(trim(strip_tags($_POST['g_id'])));

if($g_id != '' && $g_id > 0){
if(is_numeric($g_id)){

//Update views by adding a value of 1 each time it is activated.
$db->query("UPDATE games SET views = views + 1 WHERE id='$g_id'");

if(MRTP_USER_ID != '' && MRTP_USER_ID != 0){

$game_query = $db->query("SELECT * FROM games WHERE `id` = '$g_id' ");
$game_fetch = $game_query->fetch_array();
$game_categories = explode(" ", $game_fetch['category']); //Turn game categories into an array from a list
$game_name = $game_fetch['name'];

$user_query = $db->query("SELECT * FROM users_more WHERE user_id='" . MRTP_USER_ID . "'");
$user_fetch = $user_query->fetch_array();
$user_categories = json_decode($user_fetch['user_categories'], true); // Turn user categories into an array from its json_string.
$user_viewed = json_decode($user_fetch['user_viewed'], true); // Turns user views into an array from its json_string.
$user_feed = json_decode($user_fetch['user_feed'], true); // Turns user views into an array from its json_string.

//Update player's categories
if(count($game_categories) > 0){
	foreach($game_categories as $game_category){
		if($game_category != 'Game' && $game_category != 'Casino' && $game_category != 'Rhythm'){
			$user_categories[$game_category]++;
		}
	}
}

//Update player's feed. At the most, only 10 allowable feeds
if(count($user_feed) < 15){
	$user_feed[] = array(time(), 'played', $g_id);
}else{
	unset($user_feed[0]);
	$user_feed = array_values($user_feed);
	$user_feed[] = array(time(), 'played', $g_id);
}

//Update player's recently viewed. At the most, only 18 allowable games
if(!in_array($g_id,$user_viewed)){
	if(count($user_viewed) < 18){
		$user_viewed[] = $g_id;
	}else{
		unset($user_viewed[0]);
		$user_viewed = array_values($user_viewed);
		$user_viewed[] = $g_id;
	}
}

//Encode arrays and put back into database
$user_categories = json_encode($user_categories);
$user_viewed = json_encode($user_viewed);
$user_feed = json_encode($user_feed);

$db->query("UPDATE users_more SET user_categories='$user_categories', user_viewed='$user_viewed', user_feed='$user_feed' WHERE user_id='" . MRTP_USER_ID . "'");

$game_url = "http://myrighttoplay.com/" . gameurl($game_name);
$facebook->api('me/myrighttoplay:play','POST',array('game' => "$game_url"));
}
}
}

?>