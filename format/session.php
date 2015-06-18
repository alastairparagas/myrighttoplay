<?php
session_start();
define('MRTP_USER_ID', $_SESSION['mrtp_userid'], true);
define('MRTP_USER_NAME', $_SESSION['mrtp_username'], true); 
define('MRTP_LOGIN_TYPE', $_SESSION['login_type'], true);
define('MRTP_FACEBOOK_ID', $_SESSION['fb_id'], true);
//If Facebook ID is set, MRTP_USER_NAME is the same value as Facebook username.

if(!file_exists('images/profile/' . MRTP_USER_ID .'.png')){
	if(MRTP_LOGIN_TYPE == 'legacy'){
		define('MRTP_PROFILE_PIC', 'http://myrighttoplay.com/images/profile/default.png', true);
	}elseif(MRTP_LOGIN_TYPE == 'facebook'){
		define('MRTP_PROFILE_PIC', 'http://graph.facebook.com/' . MRTP_FACEBOOK_ID . '/picture?type=large', true);
	}
}else{
	define('MRTP_PROFILE_PIC', 'images/profile/' . MRTP_USER_ID . '.png', true);
}
?>