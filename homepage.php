<?php require_once('format/header.php'); require_once('format/connect.php'); ?>

<div class='group no-mobile'>
	<div class='grid-12'>
		<object id='swf_object' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,' 
				style='width:600px; height:300px; z-index:100; float:left;'>
			<param name='movie' value='http://myrighttoplay.com/MRTP_revised_2/swfs/Slideshow.swf' />
			<param name='quality' value='high' />
			<param name='wmode' value='transparent' />
			<embed id='swf_game_embed' src='http://myrighttoplay.com/MRTP_revised_2/swfs/Slideshow.swf' style='width:600px; height:300px; z-index:100;' 
				   quality='medium' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' wmode='transparent'></embed>
		</object>
	<object id='swf_object' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,' 
			style='width:333px; height:325px; z-index:100; float:right;'>
		<param name='movie' value='http://myrighttoplay.com/MRTP_revised_2/swfs/news.swf' />
		<param name='quality' value='high' />
		<param name='wmode' value='transparent' />
		<embed id='swf_game_embed' src='http://myrighttoplay.com/MRTP_revised_2/swfs/news.swf' style='width:333px; height:300px; z-index:100;' 
			   quality='medium' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' wmode='transparent'></embed>
	</object>
</div>
</div>

<?php
if(MRTP_USER_ID){
	$user_query=$db->query("SELECT `user_gold`, `user_picture`, `user_favorite`,`user_viewed`,`user_friend`,`user_categories` FROM users_more WHERE user_id='" . MRTP_USER_ID . "'");
	list($user_gold, $user_picture, $user_favorites, $user_viewed, $user_friend, $user_categories) = $user_query->fetch_array();
	$user_query->free();
	if($user_favorites==''){$user_favorites='{}';}
	if($user_viewed==''){$user_viewed='{}';}
	if($user_categories==''){$user_categories='{}';}
	if($user_friend==''){$user_friend='{}';}
	$user_favorites = array_reverse(json_decode($user_favorites, true));
	$user_viewed = array_reverse(json_decode($user_viewed, true));
	$user_categories = json_decode($user_categories, true);
	$user_friend = json_decode($user_friend, true);
	arsort($user_categories);
?>

<div class='group'>
	<div class='grid-2'>
		<img src='<?php echo $user_picture; ?>' class='circle'>
	</div>
	<div class='grid-3'>
		<h1 class='bold'><?php echo MRTP_USER_NAME; ?></h1>
		<h2>Gold: <?php echo $user_gold; ?> coins</h2>
	</div>
	<div class='grid-3'>
		<a href='<?php echo ABSOLUTE_URL; ?>profile/<?php echo MRTP_USER_NAME; ?>'><button>Edit my profile</button></a>
	</div>
	<div class='grid-4 grid-parent'>
		<h3 class='bold'>Recently added friends</h3>
		<div class='group'>
			<?php
				if(count($user_friend)==0){
			?>
			No recently added friends... Go look out for some! Search with the searchbox above!
			<?php
			}

	for($i=0; $i<4; $i++){
		$person_query = $db->query("SELECT username, user_picture FROM users,users_more WHERE users.user_id='$user_friend[$i]' AND users_more.user_id=users.user_id");
		list($user_person_username, $user_person_picture)=$person_query->fetch_array();
			?>
			<div class='grid-1'>
				<a href='profile/<?php echo $user_person_username; ?>'>
					<img src='<?php echo $user_person_picture; ?>' class='circle'>
				</a>
			</div>
			<?php
	}
			?>
		</div>
	</div>
</div>
<hr/>
<div class='group'>
	<div class='grid-12 grid-parent'>
		<h3 class='bold'>Games we think you'll like</h3>
		<div class='group'>
			<?php
	$user_categories_searchstring='';
	foreach($user_categories as $user_category=>$category_count){
		if($user_viewed_loop>1){
			$user_categories_searchstring .= " |$user_category";
		}else{
			$user_categories_searchstring .= "$user_category";
		}
		$user_viewed_loop++;
		if($user_viewed_loop==6){break;}
	}
	$user_categories_searchstring = trim($user_categories_searchstring);
	if(count($user_categories_count) > 3){
		$game_query=$db->query("SELECT `name`, `image`, `description` FROM games WHERE category REGEXP '$user_categories_searchstring' ORDER BY views DESC LIMIT 18");
	}else{
		$game_query=$db->query("SELECT `name`, `image`, `description` FROM games ORDER BY RAND() LIMIT 18");
	}
	$user_viewed_loop=0;
	while($game_prediction = $game_query->fetch_array()){
		$game_name=$game_prediction['name'];
		$game_image=$game_prediction['image'];
		$game_url=gameurl($game_name);
		$game_description=htmlentities($game_prediction['description'], ENT_QUOTES);
		echo"
			<div class='game grid-2' data-gamedesc='$game_description'>
				<div class='grid-2'>
				<a href='$game_url'><img src='$game_image' alt='$game_name' class='radius-10'></a>
				<a href='$game_url'><h5>$game_name</h5></a>
				</div>
			</div>";
		$user_viewed_loop++;
		if($user_viewed_loop==6||$user_viewed_loop==12){echo "</div><div class='group'>"; }
	}
			?>
		</div>
	</div>
</div>

<hr/>

<div class='group'>
	<div class='grid-12 grid-parent'>
		<h3 class='bold'>Recently Viewed</h3>
		<div class='group'>
			<?php
	$user_viewed_loop=0;
	if(count($user_viewed)==0){
		echo"<h3>No games viewed</h3>";
	}else{
		foreach($user_viewed as $user_view){
			$game_query=$db->query("SELECT `name`, `image`, `description` FROM games WHERE id='$user_view'");
			$game_query=$game_query->fetch_array();
			$game_name=$game_query['name'];
			$game_image=$game_query['image'];
			$game_url=gameurl($game_name);
			$game_description=htmlentities($game_query['description'], ENT_QUOTES);
			echo"
			<div class='grid-2 game' data-gamedesc='$game_description'>
				<div class='grid-2'>
				<a href='$game_url'>
				<img src='$game_image' alt='$game_name' class='radius-10'>
				<h5>$game_name</h5>
				</a>
				</div>
			</div>";
			$user_viewed_loop++;
			if($user_viewed_loop==6){break;}
		}
	}
			?>
		</div>
	</div>
</div>

<hr/>

<div class='group'>
	<div class='grid-12 grid-parent'>
		<h3 class='bold'>Favorites List</h3>
		<div class='group'>
			<?php
	$user_viewed_loop=0;
	if(count($user_favorites)==0){
		echo"<h3>No favorites</h3>";
	}else{
		foreach($user_favorites as $user_favorite){
			$game_query=$db->query("SELECT `name`, `image`, `description` FROM games WHERE id='$user_favorite'");
			$game_query=$game_query->fetch_array();
			$game_name=$game_query['name'];
			$game_image=$game_query['image'];
			$game_url=gameurl($game_name);
			$game_description=htmlentities($game_query['description'], ENT_QUOTES);
			echo"
			<div class='grid-2 game' data-gamedesc='$game_description'>
				<div class='grid-2'>
				<a href='$game_url'>
				<img src='$game_image' alt='$game_name' class='radius-10'>
				<h5>$game_name</h5>
				</a>
				</div>
			</div>";
			$user_viewed_loop++;
			if($user_viewed_loop==6){break;}
		}
	}
			?>
		</div>
	</div>
</div>
<hr/>
<?php } ?>


<div class='group'>
	<div class='grid-12 grid-parent'>
		<h3 class='bold'>Fresh Games</h3>
		<?php 
getGamesList(id, 6, 1, 'grid-12'); ?>
	</div>
</div>

<hr/>

<div class='group'>
	<div class='grid-12 grid-parent'>
		<h3 class='bold'>Highest Rated Games</h3>
		<?php 
getGamesList(rating, 6, 1, 'grid-12'); ?>
	</div>
</div>

<div class='group'>
	<hr>
</div>

<div class='group'>
	<div class='grid-12 grid-parent'>
		<h3 class='bold'>Most Popular Games</h3>
		<?php getGamesList(rating, 6, 1, 'grid-12'); ?>
	</div>
</div>

<hr/>

<div class='group'>
	<div class='grid-12 grid-parent'>
		<h3 class='bold'>Other Cool Games</h3>
		<?php getGamesList(random, 18, 3, 'grid-12'); ?>
	</div>
</div>

<hr/>

<div class='group'>
	<div class='grid-12 grid-parent'>
		<h3 class='bold'>Hall of Framers</h3>
		<div class='group'>
			<?php
$p_query=$db->query("SELECT users.user_id, users.username, users_more.user_picture FROM users, users_more WHERE users.user_id=users_more.user_id ORDER BY users_more.user_gold DESC LIMIT 6");
while($p_fetch=$p_query->fetch_array()){
	list($p_userid, $p_username, $p_image) = $p_fetch;
	echo"
		<div class='grid-2' itemscope itemtype='http://schema.org/Person'>
			<a itemprop='url' href='" . ABSOLUTE_URL . "profile/$p_username'>
				<img src='$p_image' alt='Profile Image of $p_username' class='circle'>
				<meta itemprop='image' content='$p_image' />
				<h5 itemprop='name'>$p_username</h5>
			</a>
		</div>

		";
}
$p_query->free();
			?>
		</div>
	</div>
</div>

<hr/>

<div class='group' itemscope itemtype='http://schema.org/Organization'>
	<div class='grid-7'>
		<h1 class='bold' itemprop='brand'>My Right To Play Games</h1>
		<h4 itemprop='description'>My Right To Play is a free online flash game portal site committed to delivering you the best
			flash games daily. We have our servers scrape the whole internet every 24 hours to search for the best
			games out in the internet and have them stored in our website, myrighttoplay.com. My Right To Play also
			hosts some social networking functions that will enhance your site experience, from meeting new friends,
			earning points, bookmarking favorite games, and more. See what's all about My Right To Play by signing up!</h4>
		<h3 class='bold'>Follow Us around Facebook, Twitter, and Youtube!</h3>
		<h4>We're available on your favorite Social Media Networks - Facebook,
			Youtube, and Twitter! Whenever we add a new game, subscribers
			to our Facebook and Twitter pages automatically get the first scoop about it!
			New site features and updates will also be posted.</h4>
		<h4>Our Youtube videos, on the other hand, will consist of our monthly game
			reviews and reactions from both the inside MRTP staff and outsiders. 
			<strong>Have fun socializing with us!</strong></h4>
	</div>
	<div class='grid-5 grid-parent'>
		<?php
$social_media=array(Facebook=>'facebook.com/myrighttoplay', Twitter=>'twitter.com/myrighttoplay', Youtube=>'youtube.com/myrighttoplay');
foreach($social_media as $s_platform=>$s_link){
	$s_link_real = 'https://www.' . $s_link;
	echo"
			<br/>
			<div class='group'>
				<div class='grid-2'>
					<meta itemprop='sameAs' content='$s_link_real' />
					<a href='$s_link_real' target='_new'><img src='images/social_media/$s_platform.png'></a>
				</div>
				<div class='grid-3'>
					<a href='$s_link_real' target='_new'><h4 class='bold'>$s_platform</h4><h5>$s_link</h5></a>
				</div>
			</div><br/>
			";
}
		?>
	</div>
</div>

<?php require_once('format/footer.php'); ?>