<?php
require('facebook/facebook.php');

$facebook = new Facebook(array('appId'=>'', 'secret'=>'', 'domain'=>'www.myrighttoplay.com'));
$facebook_id = $facebook->getUser();
$facebook_login = $facebook->getLoginUrl(array(
									'scope' => 'email','user_interests', 'user_likes', 'publish_actions', 
									'redirect_uri' => ''
							));

							
?>