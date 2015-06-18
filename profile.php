<?php
require('format/header.php');

if($p_favorites){ $p_favorites=json_decode($p_favorites, true);}else{$p_favorites=array();}
if($p_views){ $p_views=json_decode($p_views, true);}else{$p_views=array();}
if($p_categories){ $p_categories=json_decode($p_categories, true);}else{$p_categories=array();}
if($p_feeds){ $p_feeds=json_decode($p_feeds, true);}else{$p_feeds=array();}
arsort($p_categories);

if(!$p_bio){$p_bio="Avast! I have yet to create my own biography as a gamer. I am a new gamer in My Right To Play. I obviously enjoy flash games. Maybe, you should become a MRTP member too! Join now by creating an account!";}
?>

<div class='group'>
	<div class='grid-10'>
		<h2 class='bold'><?php echo $p_fullname; ?></h2>
		<h3><strong><?php echo $p_username; ?></strong></h3>
		<h4>Joined <?php echo timestampdate($p_regdate); ?> - <?php echo $p_gold; ?> coins</h4>
	</div>
	<div class='grid-2'>
		<img src='<?php echo $p_image; ?>' class='circle'>
	</div>
</div>

<div class='group'>
	<div class='grid-8'>
		<?php if(MRTP_USER_ID == $p_id){
			echo"
			<form method='POST' action='" . ABSOLUTE_URL . "ajax/profile-save.php' id='bio_form'>
				<label><textarea name='bio'>$p_bio</textarea></label>
				<input type='submit' value='Save Bio'>
			</form>
			";
		}else{
			echo"
			$p_bio
			";
		}
		?>
	</div>
	<div class='grid-4'>
		<button class='ajax-request friend-request' id='f_id:<?php echo $p_id; ?>' style='float:right;'>Add as friend</button>
		<a href='#sendmessage'><button style='float:right; margin-right:20px;'>Send a Message</button></a>
	</div>
</div>

<div class='group'>
	<div class='grid-12 grid-parent'>
		<h3 class='bold'>Game Interests</h3>
		<div class='group'>
		<?php
		$p_categories_searchstring='';
		foreach($p_categories as $p_category){
			if($user_viewed_loop>1){
				$p_categories_searchstring .= " |$p_category";
			}else{
				$p_categories_searchstring .= "$p_category";
			}
			$p_viewed_loop++;
			if($p_viewed_loop==6){break;}
		}
		$p_categories_searchstring = trim($p_categories_searchstring);
		if(count($p_categories_count) > 3){
			$game_query=$db->query("SELECT `name`, `image`, `description` FROM games WHERE category REGEXP '$p_categories_searchstring' ORDER BY views DESC LIMIT 18");
		}else{
			$game_query=$db->query("SELECT `name`, `image`, `description` FROM games ORDER BY RAND() LIMIT 18");
		}
		$p_viewed_loop=0;
		while($game_prediction = $game_query->fetch_array()){
			$game_name=$game_prediction['name'];
			$game_image=$game_prediction['image'];
			$game_url=gameurl($game_name);
			$game_description=htmlentities($game_prediction['description'], ENT_QUOTES);
			echo"
			<div class='game grid-2' data-gamedesc='$game_description'>
				<div class='grid-2'>
				<a href='" . ABSOLUTE_URL . "$game_url'><img src='$game_image' alt='$game_name' class='radius-10'></a>
				<a href='" . ABSOLUTE_URL . "$game_url'><h5>$game_name</h5></a>
				</div>
			</div>";
			$p_viewed_loop++;
			if($p_viewed_loop==6||$p_viewed_loop==12){echo "</div><div class='group'>"; }
		}
		?>
		</div>
	</div>
</div>

<div class='group'>
	<div class='grid-12 grid-parent'>
		<h3 class='bold'>Recently Viewed</h3>
		<div class='group'>
		<?php
		$p_viewed_loop=0;
		if(count($p_views)==0){
			echo"<h3>No games viewed</h3>";
		}else{
		foreach($p_views as $p_view){
			$game_query=$db->query("SELECT `name`, `image`, `description` FROM games WHERE id='$p_view'");
			$game_query=$game_query->fetch_array();
			$game_name=$game_query['name'];
			$game_image=$game_query['image'];
			$game_url=gameurl($game_name);
			$game_description=htmlentities($game_query['description'], ENT_QUOTES);
			echo"
			<div class='grid-2 game' data-gamedesc='$game_description'>
				<div class='grid-2'>
				<a href='" . ABSOLUTE_URL . "$game_url'>
				<img src='$game_image' alt='$game_name' class='radius-10'>
				<h5>$game_name</h5>
				</a>
				</div>
			</div>";
			$p_viewed_loop++;
			if($p_viewed_loop==6){break;}
		}
		}
		?>
		</div>
	</div>
</div>

<div class='group'>
	<div class='grid-12 grid-parent'>
		<h3 class='bold'>Recent Favorites</h3>
		<div class='group'>
		<?php
		$p_viewed_loop=0;
		if(count($p_favorites)==0){
			echo"<h3>No favorites</h3>";
		}else{
		foreach($p_favorites as $p_favorite){
			$game_query=$db->query("SELECT `name`, `image`, `description` FROM games WHERE id='$p_favorite'");
			$game_query=$game_query->fetch_array();
			$game_name=$game_query['name'];
			$game_image=$game_query['image'];
			$game_url=gameurl($game_name);
			$game_description=htmlentities($game_query['description'], ENT_QUOTES);
			echo"
			<div class='grid-2 game' data-gamedesc='$game_description'>
				<div class='grid-2'>
				<a href='" . ABSOLUTE_URL . "$game_url'>
				<img src='$game_image' alt='$game_name' class='radius-10'>
				<h5>$game_name</h5>
				</a>
				</div>
			</div>";
			$p_viewed_loop++;
			if($p_viewed_loop==6){break;}
		}
		}
		?>
		</div>
	</div>
</div>
						
						
<?php
require('format/footer.php');
?>
