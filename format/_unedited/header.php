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
	<title>My Right To Play Games</title>
	<meta property='fb:app_id' content='178484742269222'>
	<meta http-equiv='content-type' content='text/html; charset=utf-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>
	<?php if(basename($_SERVER['PHP_SELF']) == 'gamepage.php'){ 
	require('format/connect.php');

	$gameid=$db->real_escape_string(strip_tags(urldecode($_GET['gameid'])));
	if(is_int($gameid)){
		$g_query=$db->query("SELECT `id`, `views`, `category`, `name`, `description`, `width`, `height`, `url`, `image`,`rating` FROM games WHERE id='$gameid'");
	}else{
		$g_query=$db->query("SELECT `id`, `views`, `category`, `name`, `description`, `width`, `height`, `url`, `image`,`rating` FROM games WHERE name='$gameid'");
	}

	list($g_id_real, $g_views, $g_category, $g_name, $g_description, $g_width, $g_height, $g_url, $g_image, $g_rating) = $g_query->fetch_array();
	$g_query->free();
	?>
	<meta property="og:type"   content="myrighttoplay:game" /> 
	<meta property="og:url"    content="http://www.myrighttoplay.com<?php echo $_SERVER['REQUEST_URI']; ?>" /> 
	<meta property="og:title"  content="<?php echo $g_name ?>" /> 
	<meta property="og:image"  content="<?php echo $g_image ?>" /> 
	<meta property="og:description" content="<?php echo $g_description ?>" />
	<meta name='description' content='<?php echo"$g_name:$g_description"; ?>'>
	<?php }else{ ?>
	<meta name='description' content='My Right To Play Games provide free flash games for all sorts of online players, regardless of age. We provide the newest and best flash games daily!'>
	<?php } ?>
	<link rel='icon' type='image/png' href='logo.png' />
	<link rel='stylesheet' type='text/css' href='<?php echo ABSOLUTE_URL; ?>format/reset.css' />
	<link rel='stylesheet' type='text/css' href='<?php echo ABSOLUTE_URL; ?>format/quantum_framework.css' />
	<link rel='stylesheet' type='text/css' href='<?php echo ABSOLUTE_URL; ?>format/format.css' />
	<!--[if gte IE 9]><style type='text/css'>[class*='gradient']{filter: none; }</style><![endif]-->
	<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'></script>
	<script type='text/javascript' src='<?php echo ABSOLUTE_URL; ?>format/jquery.cookie.js'></script>
	<script type='text/javascript' src='<?php echo ABSOLUTE_URL; ?>format/format.js'></script>
	<!--[if lt IE 9]><script type='text/javascript' src='http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js'>IE7_PNG_SUFFIX = ".png";</script><![endif]-->
<body>

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
	
	<div class='overlay' id='sendmessage'>
		<div class='container-12'>
			<div class='group'>
				<div class='grid-12'>
					<h2 class='bold'>Send a Message</h2>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	}
	?>
	
<div class='header'>
	<div class='logo_box'>
		<a href='<?php echo ABSOLUTE_URL; ?>'><img src='<?php echo ABSOLUTE_URL; ?>images/logo.png' alt='My Right To Play logo' class='radius-5'></a>
	</div>
	<div class='searchbar'>
		<form method='GET' action='<?php echo ABSOLUTE_URL; ?>search.php'>
		<input type='text' placeholder='Search for a game or MRTP user' name='search_term' autocomplete='off' class='insetshadow' />
		<input type='submit' value='Search' class='green-gradient' />
		</form>
	</div>
	<div class='menu_holder'>
		<ul class='menu'>
			<?php
				$menu_items=array('Home'=>'homepage.php', 'Games'=>array('games.php','gamepage.php','games_cat.php'), 'Forums'=>'forums.php', 'Developer'=>'devs.php', 'Market'=>'market.php', 'About'=>'about.php');
				foreach($menu_items as $menu_item=>$menu_url){
					/* If array is multidimensional, check to see if current URL is one of the values inside the value array of a category.
						If current url is in the array of the category array, highlight that category as 'selected' CSS class.
						If not, make the category anyway as a menu item, but without the 'selected' CSS class.
					*/
					if(is_array($menu_url)){
						if(in_array(basename($_SERVER['PHP_SELF']), $menu_url)){
							echo"<li class='selected'><a href='" . ABSOLUTE_URL . "$menu_url[0]'>$menu_item</a></li>";
						}else{
							echo"<li><a href='" . ABSOLUTE_URL . "$menu_url[0]'>$menu_item</a></li>";
						}
					}else{
						if($menu_url == basename($_SERVER['PHP_SELF'])){
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

<div class='sidebar'>
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
				<li><a href='<?php echo ABSOLUTE_URL; ?>profile/<?php echo MRTP_USER_NAME; ?>'>My Profile<span class='modernpics-icon'>k</span></a></li>
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
