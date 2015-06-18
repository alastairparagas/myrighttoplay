<?php
/* This script registers the user normally without the need for making an account with a Facebook login - this is called the "legacy" login type.
	Throughout the form, there are count($form_errors["nameofinputtextbox"]) < 1 - these allow us to check whether there had already been errors before hand.
	If there were, and they are kind of redundant, this boolean statement prevents the redundant messages from appearing (for ex) - 'password not provided'
	and 'password must be longer than 25 characters and less than 7 characters' - redundant because firsthand, there was no password text to begin with!
	If there are no errors, put the user in the database, and echo out success. Form errors are passed as arrays and then encoded as JSON so our jQuery ajax
	functionality catches the JSON object and then reads it and styles errors on our form.
 */
 
require('../format/connect.php');

// Input Sanitation. We strip tags, then escape characters for database safety.
$username=$db->real_escape_string(strip_tags($_POST['user_name']));
$fullname=$db->real_escape_string(strip_tags($_POST['full_name']));
$password=$db->real_escape_string(strip_tags($_POST['user_pass']));
$password2=$db->real_escape_string(strip_tags($_POST['user_pass_again']));
$email=$db->real_escape_string(strip_tags($_POST['user_email']));
$honeypot=$db->real_escape_string(strip_tags($_POST['message']));
$form_errors=array();

// Checking the reliability of input information, outputting any warnings.
if($honeypot!=''){$form_errors["spam"][]="Spam alert. ";}
if(!$username){$form_errors["user_name"][]="Username not given.";}
if(!$fullname){$form_errors["full_name"][]="No name provided.";}
if(!$password){$form_errors["user_pass"][]="No password provided.";}
if(!$password2){$form_errors["user_pass_again"][]="No password verification provided.";}
if($password != $password2){$form_errors["user_pass_again"][]="Password and Repeat Password fields do not match.";}
if(!$email){$form_errors["user_email"][]="Email not provided.";}
if(!filter_var($email, FILTER_VALIDATE_EMAIL)  && count($form_errors["user_email"]) < 1){$form_errors["user_email"][]="Email not valid.";}
if((strlen($username) < 7 || strlen($username) > 25) && count($form_errors["user_name"]) < 1){$form_errors["user_name"][]="Username should be at least 7 and not more than 25 characters.";}
if((strlen($password) < 7 || strlen($password) > 25) && count($form_errors["user_pass"]) < 1){$form_errors["user_pass"][]="Password must be at least 7 and not more than 25 characters.";}


// Checking if this user already existed.
$query=$db->query("SELECT `user_id` FROM users WHERE email='$email'");
$email_e_check=$query->num_rows;
if($email_e_check > 0 && count($form_errors["user_email"]) < 1){$form_errors["user_email"][]="Someone already has that email address.";} 
$query->free();

$query=$db->query("SELECT `user_id` FROM users WHERE username='$username'");
$username_e_check=$query->num_rows;
if($username_e_check > 0 && count($form_errors["user_name"]) < 1){$form_errors["user_name"][]="Someone already has that user name.";} 
$query->free();

$userid_new=time() . rand(10000, 99999);

// Create a unique hash for the user (password token in the future and such
$hash=md5(uniqid());


// If no error, input stuff into database. If there are, pass them back through ajax.
if(count($form_errors) < 1){
$do1=$db->query("INSERT INTO users VALUES ('$userid_new', '$username', '$password', '$fullname', '$email', 'no', UNIX_TIMESTAMP(NOW()), '$hash', '', 'legacy')");
$do2=$db->query("INSERT INTO users_more VALUES($userid_new, '10', '', '', '', '', 'http://www.myrighttoplay.com/images/profile/default.png', '', '', '')");
$do3=$db->query("INSERT INTO users_more2 VALUES($userid_new, '', '')");
if(!$do1 || !$do2 || !$do3){
echo"{\"failure\":\"Database error. Please try again.\"}";
}else{
$form_errors['success'] = "Hurah! You have been successfully registered. We sent you an email validation link on the provided email address. Please validate
it as soon as possible (now would be a good time, don't you think?) Game on, gamer!";
echo json_encode($form_errors);

//Send Email with the HERO responsive template various for screen sizes
$subject="Activate your My Right To Play account - My Right To Play";
$message="
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
<head style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
<meta name='viewport' content='width=device-width' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>

<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
<title style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>My Right To Play</title>

</head>
 
<body bgcolor='#FFFFFF' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;height: 100%;width: 100%;'>

<table class='head-wrap' bgcolor='#313C66' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 100%;'>
	<tr style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
		<td style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'></td>
		<td class='header container' style='margin: 0 auto;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;display: block;max-width: 600px;clear: both;'>
				
				<div class='content' style='margin: 0 auto;padding: 15px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;max-width: 600px;display: block;'>
				<table bgcolor='#647AD2' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 100%;'>
					<tr style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
						<td style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'><img src='http://myrighttoplay.com/images/logo.png' style='width: 80px;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;max-width: 100%;'></td>
						<td style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'><h2 class='collapse' style='margin: 0;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;, &quot;Helvetica Neue Light&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Lucida Grande&quot;, sans-serif;line-height: 1.1;margin-bottom: 15px;color: #000;font-weight: 200;font-size: 37px;'>My Right To Play</h2></td>
					</tr>
				</table>
				</div>
				
		</td>
		<td style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'></td>
	</tr>
</table>
<table class='body-wrap' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 100%;'>
	<tr style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
		<td style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'></td>
		<td class='container' bgcolor='#FFFFFF' style='margin: 0 auto;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;display: block;max-width: 600px;clear: both;'>

			<div class='content' style='margin: 0 auto;padding: 15px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;max-width: 600px;display: block;'>
			<table style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 100%;'>
				<tr style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
					<td style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
						<h3 style='margin: 0;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;, &quot;Helvetica Neue Light&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Lucida Grande&quot;, sans-serif;line-height: 1.1;margin-bottom: 15px;color: #000;font-weight: 500;font-size: 27px;'>Hi, $username</h3>
						<p class='lead' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 17px;line-height: 1.6;'>You have succesfully registered an account in My Right To Play. To activate your account, <a href='http://www.myrighttoplay.com/email-validate.php?email=$email&hash=$hash' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;color: #2BA6CB;'>please click this link.</a></p>
						<p style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;'>Becoming a My Right To Play member is your first step to being the ultimate gaming master. Prove your skills by earning points as you play free flash games.
						Invite your friends and battle over medals and achievements. Share your gaming activity both within the My Right To Play community and Facebook with our new
						Facebook account logins. All is possible when you're given the right to play.</p>
						<p class='callout' style='margin: 0;padding: 15px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 15px;font-weight: normal;font-size: 14px;line-height: 1.6;background-color: #ECF8FF;'>
							As always, you can play your favorite games on My Right To Play even without an account. You just don't reap the benefits though. Sad face. <a href='http://www.myrighttoplay.com' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;color: #2BA6CB;font-weight: bold;'>Play now! &raquo;</a>
						</p>			
						<table class='social' width='100%' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;background-color: #ebebeb;width: 100%;'>
							<tr style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
								<td style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
									<table align='left' class='column' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 280px;float: left;min-width: 279px;'>
										<tr style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
											<td style='margin: 0;padding: 15px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>				
												
												<h5 class='' style='margin: 0;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;, &quot;Helvetica Neue Light&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Lucida Grande&quot;, sans-serif;line-height: 1.1;margin-bottom: 15px;color: #000;font-weight: 900;font-size: 17px;'>Connect with Us:</h5>
												<p class='' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;'>
												<a href='https://www.facebook.com/myrighttoplay' class='soc-btn fb' style='margin: 0;padding: 3px 7px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;color: #FFF;font-size: 12px;margin-bottom: 10px;text-decoration: none;font-weight: bold;display: block;text-align: center;background-color: #3B5998;'>Facebook</a>
												<a href='https://twitter.com/myrighttoplay' class='soc-btn tw' style='margin: 0;padding: 3px 7px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;color: #FFF;font-size: 12px;margin-bottom: 10px;text-decoration: none;font-weight: bold;display: block;text-align: center;background-color: #1daced;'>Twitter</a>
												<a href='https://plus.google.com/117854462403309487825/about' class='soc-btn gp' style='margin: 0;padding: 3px 7px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;color: #FFF;font-size: 12px;margin-bottom: 10px;text-decoration: none;font-weight: bold;display: block;text-align: center;background-color: #DB4A39;'>Google+</a></p>
											</td>
										</tr>
									</table>
									<table align='left' class='column' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 280px;float: left;min-width: 279px;'>
										<tr style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
											<td style='margin: 0;padding: 15px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>										
												<h5 class='' style='margin: 0;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;, &quot;Helvetica Neue Light&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Lucida Grande&quot;, sans-serif;line-height: 1.1;margin-bottom: 15px;color: #000;font-weight: 900;font-size: 17px;'>Contact Info:</h5>												
                Email: <strong style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'><a href='emailto:myright2play@gmail.com' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;color: #2BA6CB;'>myright2play@gmail.com</a></strong>
                
											</td>
										</tr>
									</table>
									<span class='clear' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;display: block;clear: both;'></span>	
								</td>
							</tr>
						</table>
						
					</td>
				</tr>
			</table>
			</div>						
		</td>
		<td style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'></td>
	</tr>
</table>
<table class='footer-wrap' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 100%;clear: both;'>
	<tr style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
		<td style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'></td>
		<td class='container' style='margin: 0 auto;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;display: block;max-width: 600px;clear: both;'>
				<div class='content' style='margin: 0 auto;padding: 15px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;max-width: 600px;display: block;'>
				<table style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 100%;'>
				<tr style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
					<td align='center' style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'>
						<p style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;'>
							Copyright 2013 - My Right To Play
						</p>
					</td>
				</tr>
			</table>
				</div>	
		</td>
		<td style='margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;'></td>
	</tr>
</table>
</body>
</html>
";

$headers = 'MIME-Version: 1.0' . "\r\n";
$headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers.= 'From: My Right To Play <us@myrighttoplay.com>' . "\r\n";
$headers.= 'To: ' . $fullname . ' <' . $email . ' >' . "\r\n";

mail($email, $subject, $message, $headers);

}

}else{echo json_encode($form_errors);}


require('../format/connect_close.php');
?>