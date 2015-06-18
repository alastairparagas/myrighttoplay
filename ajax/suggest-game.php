<?php
require_once('../format/connect.php');

$name = $db->real_escape_string(strip_tags(trim($_GET['name'])));
$game_url = $db->real_escape_string(strip_tags(trim($_GET['game_url'])));
$game_comment = $db->real_escape_string(strip_tags(trim($_GET['game_comment'])));

$form_errors=array();

if(empty($name)){$form_errors["name"]="You did not provide your name.";}
if(!ctype_alnum($name)){$form_errors["name"]="Your name is composed of non alphanumeric characters";}

if(empty($game_url)){$form_errors["game_url"]="You did not provide a game url.";}

if(empty($game_comment)){$form_errors["game_comment"]="You did not provide a comment/description. Please provide a short and descriptive statement.";}

if(!count($form_errors) > 0){
	$form_errors["success"] = "Your suggested game has succesfully been submitted.";
}

$form_errors=json_encode($form_errors);
echo $form_errors;

?>