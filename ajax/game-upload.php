<?php
require('../format/connect.php');
require('../format/functions.php');

	$game_name = $db->real_escape_string(trim($_POST['game_name']));
	$game_category = $_POST['game_category'];
	$game_description = $db->real_escape_string(strip_tags(trim($_POST['game_description'])));
	$game_image = $_FILES['game_image']['tmp_name'];
	$game_swf = $_FILES['game_swf']['tmp_name'];
	$game_image_size = $_FILES['game_image']['size'];
	$game_swf_size = $_FILES['game_swf']['size'];
	$game_size = @getimagesize($game_swf);
	$game_width = $game_size[0];
	$game_height = $game_size[1];
	
	$form_errors = array();
	if($game_name==''){ $form_errors['game_name']="Game Name not provided!"; }
	if(count($game_category) < 1){ $form_errors['game_category']="Game Category not provided!"; }
	if($game_description==''){ $form_errors['game_description']="Game Description not provided!"; }
	if($game_image_size == 0){ $form_errors['game_image']="Game image not provided!"; }
	if($game_swf_size == 0){ $form_errors['game_swf']="Game swf not provided!"; }
	
	if(count($form_errors)==0){
		move_uploaded_file($game_swf, "../swfs/$game_name.swf");
		move_uploaded_file($game_image, "../icon/$game_name.gif");
		
		$game_category_string = '';
		foreach($game_category as $game_cat){
			$game_category_string .= " $game_cat";
		}
		$game_category_string = trim($game_category_string);
		
		$query = $db->query("SELECT `id` FROM games ORDER BY `id` DESC LIMIT 1");
		list($game_id) = $query->fetch_array();
		$game_id = $game_id + 1;
		$db->query("INSERT INTO games VALUES('$game_id', '0', '$game_category_string', '$game_name', '$game_description', '$game_width', '$game_height', 'http://www.myrighttoplay.com/swfs/$game_name.swf', 'http://www.myrighttoplay.com/icon/$game_name.gif', '0')");
		echo"<script type='text/javascript'>
				<!--
				window.location = 'http://www.myrighttoplay.com/uploadscriptforthermecemperors.php'
				//-->
				</script>";
	}else{
		echo"<strong>ERRORS IN UPLOADING GAME!</strong><br/><br/>";
		foreach($form_errors as $form_error_box=>$form_error){
			echo"$form_error_box:$form_error <br/><br/>";
		}
		echo"<a href='http://www.myrighttoplay.com/uploadscriptforthermecemperors.php'><button>Go back to form</button></a>";
	}

?>