<?php
require('format/header.php');
require('format/connect.php');

$email=$db->real_escape_string(strip_tags($_GET['email']));
$hash=$db->real_escape_string(strip_tags($_GET['hash']));

$query=$db->query("UPDATE users SET `active`='yes' WHERE `email`='$email' AND `hash`='$hash'");
if($query){
?>

<div class='row'>
	<h2 class='bold'>Your email has been validated!</h2>
	<h3>Thank you for validating your My Right To Play account. You may now login with your username
	and password. If you do lose your username/password, you will be able to retrieve it through
	this email address.</h3>
</div>

<?php
}
require('format/footer.php');
?>