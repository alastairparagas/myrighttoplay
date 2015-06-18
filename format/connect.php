<?php
date_default_timezone_set("America/New_York");
$db = new mysqli('localhost','root','', 'saterniv_gamesite');
if($db->connect_errno > 0){die('Unable to connect to database:' . $db->connect_error . ']');}
?>