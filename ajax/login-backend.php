<?php
require('../format/session.php');
require('../format/facebook_config.php');
require('../format/connect.php');

//Login sent by our form or oAuth Facebook Dialog? Blank for Facebook oAuth, legacy for MRTP login. Set <input type='hidden' name='login_type' value='legacy'> on non-Facebook login forms.
$login_type=strip_tags($_POST['login_type']);
$error=strip_tags(trim($_GET['access_denied']));
$form_errors=array();

if($login_type==''){
//If someone tries to hack the system by removing legacy as hidden form value, thereby trying to access
//accounts with no passwords - FB accounts, they are sent on a redirect loop and/or pause since Facebook can't authenticate them.
	if(!$error){
		if($facebook_id) {
			//User is logged into Facebook, but we don't know if access token is valid. If user not authenticated, he will be asked to authenticate.
			try{
				$facebook_profile = $facebook->api('/me');
			}catch(FacebookApiException $e) {
				error_log($e);
				$facebook_id = null;
			}
			
			//Finally got the id and access tokens. Is this user in our system? If not, register.
			$query=$db->query("SELECT user_id, username FROM users WHERE fb_id='$facebook_id' AND login_type='facebook'");
			$query2=$db->query("SELECT user_id, username FROM users WHERE fb_id='$facebook_id' AND login_type='legacy'");

			if($query->num_rows == 0 && MRTP_USER_ID == ''){
				//Facebook user, not an MRTP user. Let's register him.
				$userid_new=time() . rand(10000, 99999);
				$username=$facebook_profile['username'];
				$fullname=$facebook_profile['name'];
				$email=$facebook_profile['email'];
				$hash=md5(uniqid());
				$do=$db->query("INSERT INTO users VALUES ('$userid_new', '$username', '', '$fullname', '$email', 'yes', UNIX_TIMESTAMP(NOW()), '$hash', '$facebook_id', 'facebook')");
				$do2=$db->query("INSERT INTO users_more VALUES($userid_new, '10', '', '', '', '', 'http://graph.facebook.com/$facebook_id/picture?width=100&height=100', '', '', '')");
				$do3=$db->query("INSERT INTO users_more2 VALUES($userid_new, '', '')");
				if($do && $do2 && $do3){
					$_SESSION['mrtp_userid']=$userid_new;
					$_SESSION['mrtp_username']=$username;
					$_SESSION['login_type']='facebook';
					$_SESSION['fb_id']=$facebook_id;
					$_SESSION['time_start']=time();
				}
				header("Location: ../homepage.php");
			}elseif($query2->num_rows == 0 && MRTP_USER_ID != ''){
				//MRTP user, not a Facebook user until now.
				$db->query("UPDATE users SET fb_id='$facebook_id' WHERE user_id=" . MRTP_USER_ID);
				$_SESSION['fb_id']=$facebook_id;
				header("Location: ../homepage.php");
			}elseif($query->num_rows == 1){
				//Already a registered MRTP user who had a Facebook account. Log with session variables.
				$row=$query->fetch_assoc();
				$_SESSION['mrtp_userid']=$row['user_id'];
				$_SESSION['mrtp_username']=$row['username'];
				$_SESSION['login_type']='facebook';
				$_SESSION['fb_id']=$facebook_id;
				$_SESSION['time_start']=time();
				header("Location: ../homepage.php");
			}elseif($query2->num_rows == 1){
				$row=$query2->fetch_assoc();
				$_SESSION['mrtp_userid']=$row['user_id'];
				$_SESSION['mrtp_username']=$row['username'];
				$_SESSION['login_type']='facebook';
				$_SESSION['fb_id']=$facebook_id;
				$_SESSION['time_start']=time();
				header("Location: ../homepage.php");
			}else header("Location: ../homepage.php");
		}else{
			//This should fix the non-Facebook ID getting mechanism.
			header("Location: {$facebook_login}");
		}
	}else{header("Location: ../homepage.php");}
}elseif($login_type='legacy'){
	$username=strip_tags(trim($_POST['username']));
	$password=strip_tags(trim($_POST['password']));
	$query=$db->query("SELECT user_id,username,fb_id FROM users WHERE username='$username' AND password='$password' AND login_type='legacy'");
	if($query->num_rows == 1){
		$row=$query->fetch_assoc();
		$_SESSION['mrtp_userid']=$row['user_id'];
		$_SESSION['mrtp_username']=$row['username'];
		$_SESSION['login_type']='legacy';
		$_SESSION['fb_id']=$row['fb_id'];
		$_SESSION['time_start']=time();
		$form_errors['success']="Succesful Login. Redirecting...";
		$form_errors['redirect_url']="homepage.php";
	}else{
		$form_errors['error']="Wrong username or password. Please try again.";
	}
}

if(count($form_errors) > 0){
	echo json_encode($form_errors);
}

?>