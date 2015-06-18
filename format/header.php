<?php
date_default_timezone_set("America/New_York"); 
require('format/session.php');
require('format/facebook_config.php');
require('format/functions.php');
?>

<!DOCTYPE HTML>
<!--[if IE 9]><html class='ie9' xmlns:fb="http://ogp.me/ns/fb#"><![endif]-->
<!--[if IE 8]><html class='ie8' xmlns:fb="http://ogp.me/ns/fb#"><![endif]-->
<!--[if IE 7]><html class='ie7' xmlns:fb="http://ogp.me/ns/fb#"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html xmlns:fb="http://ogp.me/ns/fb#"><!--<![endif]-->
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# myrighttoplay: http://ogp.me/ns/fb/myrighttoplay#">
	<meta property='fb:app_id' content='178484742269222'>
	<meta http-equiv='content-type' content='text/html; charset=utf-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>
	<?php 
	if(basename($_SERVER['PHP_SELF']) == 'gamepage.php'){ 
		require_once('format/connect.php');
		$gameid=$db->real_escape_string(strip_tags(urldecode($_GET['gameid'])));
		if($gameid=='Space-Fishers'){ $gameid='Space Fishers'; }
		if(is_numeric($gameid)){
			$g_query=$db->query("SELECT `id`, `views`, `category`, `name`, `description`, `width`, `height`, `url`, `image`,`rating` FROM games WHERE id='$gameid'");
		}else{
			$g_query=$db->query("SELECT `id`, `views`, `category`, `name`, `description`, `width`, `height`, `url`, `image`,`rating` FROM games WHERE name='$gameid'");
		}
		list($g_id_real, $g_views, $g_category, $g_name, $g_description, $g_width, $g_height, $g_url, $g_image, $g_rating) = $g_query->fetch_array();
		$g_query->free();
	?>
		<title><?php echo $g_name; ?></title>
		<meta property="og:type"   content="myrighttoplay:game" /> 
		<meta property="og:url"    content="http://www.myrighttoplay.com<?php echo $_SERVER['REQUEST_URI']; ?>" /> 
		<meta property="og:title"  content="<?php echo $g_name; ?>" /> 
		<meta property="og:image"  content="<?php echo $g_image; ?>" /> 
		<meta property="og:description" content="<?php echo $g_description; ?>" />
		<meta name="description" content="<?php echo"$g_name:$g_description"; ?>" />
	<?php
	}elseif(basename($_SERVER['PHP_SELF']) == 'profile.php'){
		require_once('format/connect.php');
		$profile_username = strip_tags(trim($_GET['user']));
		$profile_query = $db->query("SELECT users.user_id, users.username, users.fullname, users.reg_d, 
								users_more.user_gold, users_more.user_favorite, users_more.user_viewed, 
								users_more.user_categories, users_more.user_picture, users_more.user_bio, 
								users_more.user_feed FROM users, users_more WHERE users.username='$profile_username' AND users_more.user_id=users.user_id");
		list($p_id, $p_username, $p_fullname, $p_regdate, $p_gold, $p_favorites, $p_views, $p_categories, $p_image, $p_bio, $p_feeds) = $profile_query->fetch_array();
		$profile_query->free();
	?>
		<title><?php echo $p_fullname; ?> - Gamer Profile</title>
		<meta property="og:url"    content="http://www.myrighttoplay.com<?php echo $_SERVER['REQUEST_URI']; ?>" /> 
		<meta property="og:title"  content="<?php echo $p_fullname; ?> - Gamer Profile" /> 
		<meta property="og:image"  content="<?php echo $p_image; ?>" /> 
		<meta property="og:description" content="<?php echo $p_bio; ?>" />
		<meta name="description" content="<?php echo"$p_fullname:$p_bio"; ?>" />
	<?php
	}elseif(basename($_SERVER['PHP_SELF']) == 'search.php'){
		require_once('format/connect.php');
		$search_term = $db->real_escape_string(strip_tags($_GET['search_term']));
	?>
		<title>Search Results: <?php echo $search_term; ?></title>
		<meta property="og:url"    content="http://www.myrighttoplay.com<?php echo $_SERVER['REQUEST_URI']; ?>" /> 
		<meta property="og:title"  content="Search Results: <?php echo $search_term; ?>" /> 
		<meta property="og:image"  content="http://myrighttoplay.com/images/favicon_large.png" /> 
		<meta property="og:description" content="Search for a game or a new friend now with My Right To Play search!" />
		<meta name="description" content="Search for a game or a new friend now with My Right To Play search!" />
	<?php
	}else{ 
	$menu_items=array('All'=>'games.php', 'Action'=>'games_cat.php?cat=action', 'Adventure'=>'games_cat.php?cat=adventure', 'Driving'=>'games_cat.php?cat=driving', 
	'Shooting'=>'games_cat.php?cat=shooting','Strategy'=>'games_cat.php?cat=strategy','Sports'=>'games_cat.php?cat=sports','Misc'=>'games_cat.php?cat=other');
	$meta_array = array(
		'homepage.php' => array("Free Flash Games - My Right To Play", "My Right To Play offers thousands of free online flash games for players like you. We provide the newest and best flash games daily! Come and play now!"),
		'games.php' => array("List of Games - Game Directory", "This is a list of some of the best games in My Right To Play from all categories - Action, Adventure, Driving, Shooting, Strategy, Sports, and more."),
		'games_cat.php?cat=action' => array("Action Games", "Action games primarily involve fast-paced combat with some form of movement, and elements of puzzle solving, strategy, plot, and story line. 
								These elements are not prime themes of the games, but may still be important features. "),
		'games_cat.php?cat=adventure' => array("Adventure Games", "Adventure games focus on puzzle solving within a narrative framework, generally with few or no action elements."),
		'games_cat.php?cat=driving' => array("Driving Games", "Racing games can be divided into two sub-genres: racing simulation, in which the physics and real-world aspects of the vehicles are emphasized; and arcade racing, 
								in which an accurate representation is not important. Kart racing falls in the arcade racing sub-genre, and tends to focus on humorous obstacles."),
		'games_cat.php?cat=shooting' => array("Shooting Games", "Shooter games are among the leading video game genres, they focus on shooting projectiles and are made in a multitude of settings. Shooters were really first made popular 
								in the 90’s by games such as Wolfenstein 3D and Doom. These games were among the first to let players move around in a three dimensional game world and theoretically shoot at monsters and people."),
		'games_cat.php?cat=strategy' => array("Strategy Games", "Strategy Games are games which require a strategy in order for you to win. These range from moving units or pieces to selecting what spell may suit the situation best."),
		'games_cat.php?cat=sports' => array("Sports Games", "Sports games are one of the biggest one off genres of video games. In sports games players play simulations of real life sports. The player will control one player in the game and usually compete against NPC opponents. 
								Sometimes players can compete with each other in a co-op mode. Other times players can go head to head in a PvP mode."),
		'games_cat.php?cat=other' => array("Misc Games", "Other games that can't just be labeled by our mortally-bound categories. Play 'em!"),
		'about.php' => array("About Us", "About My Right To Play. Questions? Contact us through the form below or through our email, myright2play@gmail.com")
	);
	foreach($meta_array as $page_url=>$meta_details){
		$meta_title=$meta_details[0];
		$meta_description=$meta_details[1];
		if(basename($_SERVER['REQUEST_URI']) == $page_url){
	?>
		<title><?php echo $meta_title; ?></title>
		<meta name="description" content="<?php echo $meta_description; ?>" />
		<meta property="og:title"  content="<?php echo $meta_title; ?>" /> 
		<meta property="og:image"  content="http://myrighttoplay.com/images/favicon_large.png" /> 
		<meta property="og:description" content="<?php echo $meta_description; ?>" />
	<?php }}} ?>
	<link rel='icon' type='image/png' href='http://myrighttoplay.com/images/favicon.png' />
	<link rel='stylesheet' type='text/css' href='<?php echo ABSOLUTE_URL; ?>format/style.css' />
	<!--[if gte IE 9]><style type='text/css'>[class*='gradient']{filter: none; }</style><![endif]-->
	<script type='text/javascript' src='<?php echo ABSOLUTE_URL; ?>format/jquery.min.js'></script>
	<script type='text/javascript' defer src='<?php echo ABSOLUTE_URL; ?>format/jquery.cookie.js'></script>
	<script type='text/javascript' defer src='<?php echo ABSOLUTE_URL; ?>format/format.js'></script>
	<!--[if lt IE 9]><script type='text/javascript' src='<?php echo ABSOLUTE_URL; ?>format/IE9.js'>IE7_PNG_SUFFIX = ".png";</script><![endif]-->
<body>

	<style>
	.mrtp-side-ad { width: 320px; height: 50px; position:fixed; bottom:0px; left:0px; }
	@media(min-width: 500px) { .mrtp-side-ad { width: 468px; height: 60px; position:fixed; bottom:0px; left:0px; } }
	@media(min-width: 800px) { .mrtp-side-ad { width: 200px; height: 600px; position:fixed; right:25px; top:100px; } }
	</style>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<!-- MRTP Side Ad -->
	<ins class="adsbygoogle mrtp-side-ad"
	     style="display:inline-block"
	     data-ad-client="ca-pub-4776892752602978"
	     data-ad-slot="3416917847"></ins>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script>

	<div class='overlay' id='suggest'>
		<div class='container-12'>
		<div class='group'>
			<div class='grid-12'>
				<h2 class='bold'>Suggest a Game</h2>
				<a class='close' href='#release'>&#935;</a>
				<h3><strong>My Right To Play</strong> depends on loyal and hard-core online gamers like you to continue expanding
				our grand constitution! Our servers scan the web daily for the newest and best in flash gaming content with our
				proprietary technologies. However, we need you, the gamer, to help us in the cause!</h3>
			</div>
		</div>
		<div class='group'>
			<div class='grid-6 offset-3'>
				<form action='<?php echo ABSOLUTE_URL; ?>ajax/suggest-game.php' id='suggest_form' method='GET'>
					<label>Your Name<input type='text' name='name' class='form_error'/></label>
					<label>Game URL<input type='text' name='game_url' /></label>
					<label>Comments<textarea name='game_comment' class='form_error'></textarea></label>
					<input type='submit' value='Suggest Game'>
				</form>
			</div>
		</div>
		</div>
	</div>

	<?php
	if(!MRTP_USER_ID){
	?>
	<div class='overlay' id='login'>
		<div class='container-12'>
		<div class='group'>
			<div class='grid-12'>
				<h2 class='bold'>Login, Gamer!</h2>
				<a class='close' href='#release'>&#935;</a>
				<h3>Login to My Right To Play now! Logging in allows you to rack up points, socialize with your friends,
				share your gaming activity, bookmark games, create judging reviews of the games you've played, and help
				make My Right To Play a better place for all!</h3>
			</div>
		</div>
		<div class='group'>
			<div class='grid-4'>
				<a href='<?php echo $facebook_login; ?>'><button>Login with my Facebook account</button></a>
			</div>
			<div class='grid-2'>
				<h3>OR</h3>
			</div>
			<div class='grid-6'>
				<form action='<?php echo ABSOLUTE_URL; ?>/ajax/login-backend.php' id='login_form' method='POST'>
					<label>Username<input type='text' name='username'></label>
					<label>Password<input type='password' name='password'></label>
					<input type='hidden' name='login_type' value='legacy'>
					<input type='submit' value='Login my account'>
				</form>
			</div>
		</div>
		</div>
	</div>
	
	<div class='overlay' id='signup'>
		<div class='container-12'>
			<div class='group'>
				<div class='grid-12'>
					<h2 class='bold'>Sign up, Gamer!</h2>
					<a class='close' href='#release'>&#935;</a>
					<h3>Sign up and make your My Right To Play account! MRTP users earn points that are redeemable for 
					points on the market, socialize with fellow flash-gaming friends, comment on games, talk on the forums,
					enjoy additional site features exclusive for MRTP users, and more!</h3>
				</div>
			</div>
			<div class='group'>
				<div class='grid-6 offset-3'>
					<form action='<?php echo ABSOLUTE_URL; ?>/ajax/register.php' id='register_form' method='POST'>
						<label>Full Name (Real)<input type='text' name='full_name'></label>
						<label>Username<input type='text' name='user_name'></label>
						<label>Password<input type='password' name='user_pass'></label>
						<label>Repeat Password<input type='password' name='user_pass_again'></label>
						<label>Email<input type='text' name='user_email'></label>
						<input type='text' name='message'>
						<input type='submit' value='Sign Up'>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php
	}else{
	require('format/connect.php');
	?>
	
	<div class='overlay' id='favorites'>
		<div class='container-12'>
			<div class='group'>
				<div class='grid-12'>
					<h2 class='bold'>Favorites Bookmark</h2>
					<a class='close' href='#release'>&#935;</a>
					<h3>Below are your favorite games. You can only have a maximum of 30 games.</h3>
				</div>
			</div>
			<div class='group'>
				<div class='grid-12 grid-parent'>
				<div class='group'>
				<?php
					$counter=0;
					$user_favorite_query = $db->query("SELECT `user_favorite` FROM users_more WHERE user_id='" . MRTP_USER_ID . "'");
					list($user_favorites) = $user_favorite_query->fetch_array();
					$user_favorites = json_decode($user_favorites, true);
					$user_favorites_count = count($user_favorites);
					if($user_favorites_count > 0){
						foreach($user_favorites as $user_favorite){
							$game_query = $db->query("SELECT `name`, `image`, `description` FROM games WHERE `id`='$user_favorite'");
							list($game_name, $game_image, $game_desc) = $game_query->fetch_array();
							$game_url=gameurl($game_name);
							$game_desc=htmlentities($game_desc, ENT_QUOTES);
							echo"
							<div class='game grid-2' data-gamedesc='$game_desc'>
								<div class='grid-2'>
								<a href='" . ABSOLUTE_URL . "$game_url'>
								<img src='$game_image' class='radius-10'><br/>
								<h5>$game_name</h5>
								</a>
								</div>
							</div>
							";
							$game_query->free();
							$counter++;
							if($counter==6||$counter==12||$counter==18||$counter==24){echo"</div><div class='group'>";}
						}
					}else{
						echo"
						<div class='grid-12'>
							<h3>No favorite games...</h3>
						</div>";
					}
					$user_favorite_query->free();
				?>
				</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class='overlay' id='friends'>
		<div class='container-12'>
			<div class='group'>
				<div class='grid-12'>
					<h2 class='bold'>Friends List</h2>
					<a class='close' href='#release'>&#935;</a>
					<h3>Your list of friends. Get too much, and it'll be a pain handling them. Be wise in choosing.</h3>
				</div>
			</div>
				<?php
				$user_friend_query = $db->query("SELECT user_friend FROM users_more WHERE user_id='" . MRTP_USER_ID . "'");
				list($user_friends) = $user_friend_query->fetch_array();
				$user_friends = json_decode($user_friends, true);
				$row_counter=0;
				$all_counter=0;
				foreach($user_friends as $user_friend){
					$row_counter++;
					$all_counter++;
					if($row_counter==1){
						echo"<div class='group'>";
					}
					$person_query = $db->query("SELECT username, user_picture FROM users,users_more WHERE users.user_id='$user_friend' AND users_more.user_id=users.user_id");
					list($user_person_username, $user_person_picture)=$person_query->fetch_array();
					?>
					<div class='grid-2'>
						<a href='<?php echo ABSOLUTE_URL; ?>profile/<?php echo $user_person_username; ?>'>
						<img src='<?php echo $user_person_picture; ?>' class='circle'>
						<?php echo $user_person_username; ?>
						</a>
					</div>
				<?php
					if($row_counter==6||$all_counter==count($user_friends)){
						$row_counter=0;
						echo"</div>";
					}
				}
				$user_friend_query->free;
				?>
		</div>
	</div>
	
	<?php
	if(basename($_SERVER['PHP_SELF'])=='profile.php'){
		$profile_id = strip_tags(trim($_GET['user']));
		$profile_query = $db->query("SELECT user_id FROM users WHERE username='$profile_id'");
		list($profile_userid) = $profile_query->fetch_array();
	}
	?>
	<div class='overlay' id='sendmessage'>
		<div class='container-12'>
			<div class='group'>
				<div class='grid-12'>
					<h2 class='bold'>Send a Message</h2>
					<a class='close' href='#release'>&#935;</a>
					<h3>You are sending a message to <strong><?php echo $profile_id; ?></strong></h3>
				</div>
			</div>
			<div class='group'>
				<div class='offset-3 grid-6'>
					<form action='<?php echo ABSOLUTE_URL; ?>/ajax/send-message.php' id='message-form' method='POST'>
						<label>Title<input type='text' name='title'></label>
						<label>Message<textarea name='message'></textarea></label>
						<label><input type='hidden' name='profile_id' value='<?php echo $profile_userid; ?>'></input></label>
						<input type='submit' value='Send Message'>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	}
	?>
	
<div class='header' itemscope itemtype='http://schema.org/WPHeader'>
	<div class='logo_box'>
		<a href='<?php echo ABSOLUTE_URL; ?>'><img src='<?php echo ABSOLUTE_URL; ?>images/logo.png' alt='My Right To Play logo' class='radius-5'></a>
		<meta name='name' content='My Right To Play' />
		<meta name='image' content='<?php echo ABSOLUTE_URL; ?>images/logo.png' />
	</div>
	<div class='searchbar' itemscope itemtype='http://schema.org/SiteNavigationElement'>
		<meta name='name' content='search' />
		<meta name='about' content='Search box to search for games inside the website and registered members of My Right To Play' />
		<form method='GET' action='<?php echo ABSOLUTE_URL; ?>search.php'>
		<input type='text' placeholder='Play a flash game or find a new friend!' name='search_term' autocomplete='off' class='insetshadow' />
		<input type='submit' value='Search' class='green-gradient' />
		</form>
	</div>
	<div class='menu_holder'>
		<ul class='menu' itemscope itemtype='http://schema.org/SiteNavigationElement'>
			<meta name='name' content='menu' />
			<meta name='about' content='Menu bar of My Right To Play (myrighttoplay.com), including the All, Adventure, Shooting, Stategy, Sports, and Misc categories' />
			<?php
			//All | Action | Adventure | Driving | Shooting | Strategy | Sports | Misc
				$menu_items=array('All'=>'games.php', 'Action'=>'games_cat.php?cat=action', 'Adventure'=>'games_cat.php?cat=adventure', 'Driving'=>'games_cat.php?cat=driving', 'Shooting'=>'games_cat.php?cat=shooting','Strategy'=>'games_cat.php?cat=strategy','Sports'=>'games_cat.php?cat=sports','Misc'=>'games_cat.php?cat=other');
				foreach($menu_items as $menu_item=>$menu_url){
					/* If array is multidimensional, check to see if current URL is one of the values inside the value array of a category.
						If current url is in the array of the category array, highlight that category as 'selected' CSS class.
						If not, make the category anyway as a menu item, but without the 'selected' CSS class.
					*/
					if(is_array($menu_url)){
						if(in_array(basename($_SERVER['REQUEST_URI']), $menu_url)){
							echo"<li class='selected'><a href='" . ABSOLUTE_URL . "$menu_url[0]'>$menu_item</a></li>";
						}else{
							echo"<li><a href='" . ABSOLUTE_URL . "$menu_url[0]'>$menu_item</a></li>";
						}
					}else{
						if($menu_url == basename($_SERVER['REQUEST_URI'])){
							echo"<li class='selected'><a href='" . ABSOLUTE_URL . "$menu_url'>$menu_item</a></li>";
						}else{
							echo"<li><a href='" . ABSOLUTE_URL . "$menu_url'>$menu_item</a></li>";
						}
					}
				}
			?>
		</ul>
	</div>
</div>

<div class='sidebar' itemscope itemtype='http://schema.org/WPSideBar'>
	<div class='container'>
		<?php
		if(MRTP_USER_ID){?>
			<div class='option_box text-center'>
				<img src='<?php echo MRTP_PROFILE_PIC; ?>' class='circle'>
				<h2><?php echo MRTP_USER_NAME; ?></h2>
			</div>
			
		<?php }else{ ?>
			<div class='option_box'>
				<h2>Join Up!</h2>
				<h4>Welcome to MRTP! Join and enjoy the new era of flash gaming! <?php echo MRTP_USER_NAME; ?></h4>
			</div>
		<?php } ?>
		<?php
		if(MRTP_USER_ID){?>
			<ul class='user_menu'>
				<li><a href='<?php echo ABSOLUTE_URL; ?>'>Front Page<span class='modernpics-icon'>B</span></a></li>
				<li><a href='#favorites'>Favorites<span class='modernpics-icon'>j</span></a></li>
				<li><a href='<?php echo ABSOLUTE_URL; ?>mail/'>Mail<span class='modernpics-icon'>m</span></a></li>
				<li><a href='#friends'>Friends<span class='modernpics-icon'>g</span></a></li>
				<li><a href='#suggest'>Suggest Game<span class='modernpics-icon'>l</span></a></li>
				<li><a href='<?php echo ABSOLUTE_URL; ?>ajax/logout.php'>Logout <span class='modernpics-icon'>x</span></a></li>
			</ul>
		<?php }else{ ?>
			<ul class='user_menu'>
				<li><a href='#login'>Login <span class='modernpics-icon'>o</span></a></li>
				<li><a href='#signup'>Sign Up<span class='modernpics-icon'>Y</span></a></li>
				<li><a href='#suggest'>Suggest Game<span class='modernpics-icon'>l</span></a></li>
				<li><a href='#contact'>Contact Us<span class='modernpics-icon'>b</span></a></li>
			</ul>
		<?php } ?>
	</div>
</div>

<div class='container-12 radius-10 body'>