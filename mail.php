<?php
require('format/header.php');
require('format/connect.php');

$user_mail_query = $db->query("SELECT user_mail FROM users_more2 WHERE user_id = '" . MRTP_USER_ID .  "'");
list($user_mail) = $user_mail_query->fetch_array();
$user_mail = json_decode($user_mail, true);
if($user_mail == ''){$user_mail = array();}
$user_mail_count = count($user_mail);
$user_mail_query->free();

echo"<h1 class='bold'>" . MRTP_USER_NAME . "'s Mail Center</h1>";

if($user_mail_count < 1){
	echo "<h2>You have no mail. </h2>";
}else{
	foreach($user_mail as $mail_time=>$mail_contents){
		$mail_title=$mail_contents[0];
		$mail_from=$mail_contents[1];
		$mail_message=$mail_contents[2];

		$mail_from_query=$db->query("SELECT username, fullname, user_picture FROM users, users_more WHERE users.user_id = '$mail_from' AND users_more.user_id='$mail_from'");
		list($mail_from_username, $mail_real_name, $mail_from_picture)=$mail_from_query->fetch_array();
		
		if($mail_title == 'Friend Request' && $mail_message==''){
			$mail_message="$mail_real_name ($mail_from_username) wants to be your friend. <br/>
			<button class='ajax-request mail-actions' id='friend_id:$mail_from,mail_identifier:$mail_time'>Add as Friend</button>";
		}
		?>
		
		<div class='group'>
			<div class='grid-2'>
				<a href='<?php echo ABSOLUTE_URL . "profile/" . $mail_from_username; ?>'><img src='<?php echo $mail_from_picture; ?>' class='circle'></a>
				<h4><?php echo $mail_from_username; ?></h4>
			</div>
			<div class='grid-7'>
				<h3><strong><?php echo $mail_title; ?></strong></h3>
				<h4><?php echo $mail_message; ?></h4>
			</div>
			<div class='grid-2'>
				<h5><?php echo timestamptime($mail_time); ?></h5>
			</div>
			<div class='grid-1'>
				<span class='ajax-request mail-actions modernpics' id='mail_identifier:<?php echo $mail_time;?>,mail_task:delete'>x</span>
				<span class='ajax-request mail-actions modernpics' id='mail_identifier:<?php echo $mail_time; ?>,mail_task:delete'>o</span>
			</div>
		</div>
		
		<?php
	}
}

require('format/footer.php');
?>