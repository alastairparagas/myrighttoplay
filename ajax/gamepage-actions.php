<?php
require_once('../format/session.php');
require_once('../format/connect.php');
require_once('../format/facebook_config.php');
require_once('../format/functions.php');

$g_action = strip_tags(trim($_GET['g_action']));
$g_id = strip_tags(trim($_GET['g_id']));

if($g_action=='game_favorite'){
	if(!MRTP_USER_ID){die("You must be logged in to your account to favorite games");}
	$p_query=$db->query("SELECT user_favorite, user_feed FROM users_more WHERE user_id='" . MRTP_USER_ID . "'");
	list($p_favorite, $p_feed)=$p_query->fetch_array();
	if(!$p_favorite){$p_favorite='{}';}
	if(!$p_feed){$p_feed='{}';}
	$p_favorite=json_decode($p_favorite, true);
	$p_feed=json_decode($p_feed, true);

	if(in_array($g_id,$p_favorite)){die("Game is already in your favorites");}
	if(!$g_id){die("No game provided!");}
	
	//Only maximum of 30 favorite games allowed.
	if(count($p_favorite) < 30){
		$p_favorite[] = $g_id;
	}else{
		die("You already have 30 favorite games. That's too much favorites! Consider cleaning up your Favorites bookmarks.");
	}

	//Only a maximum of 15 feeds allowed.
	if(count($p_feed) < 15){
		$p_feed[] = array(time(), 'favorited', $g_id);
	}else{
		unset($p_feed[0]);
		$p_feed = array_values($p_feed);
		$p_feed[] = array(time(), 'favorited', $g_id);
	}

	$p_favorite=json_encode($p_favorite);
	$p_feed=json_encode($p_feed);
	$f_query=$db->query("UPDATE users_more SET user_favorite='$p_favorite', user_feed='$p_feed' WHERE user_id='" . MRTP_USER_ID . "'");
	$f_query->free;
	$p_query->free;
	die("Game added to your favorites.");
}
?>
