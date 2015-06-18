<?php
require_once('format/header.php');
require_once('format/connect.php');

$search_term = $db->real_escape_string(strip_tags($_GET['search_term']));
$game_page= !empty($_GET['pg']) ? $db->real_escape_string(strip_tags($_GET['pg'])) : 1;

$items_per_page = 15;
$rows_of_results = 15;
if($game_page<1){ $game_page = 1; }
$items_offset = ($game_page-1) * $items_per_page;
?>

<?php 
$g_query_count = @$db->query("SELECT `id` FROM games WHERE name LIKE '%$search_term%' or description LIKE '%$search_term%'");
$g_count = $g_query_count->num_rows;
$g_query_count->free();

$p_query_count = @$db->query("SELECT `user_id` FROM users WHERE username LIKE '%$search_term%' or fullname LIKE '%$search_term%' or email LIKE '%$search_term%'");
$p_count = $p_query_count->num_rows;
$p_query_count->free();

if($g_count > 0){
?>

<div class='group'>
	<div class='grid-12'>
		<h2 class='bold'>Game Results for <strong><?php if($search_term){echo $search_term;}else{echo"[blank]";} ?></strong></h2>
	</div>
</div>

<?php
	$g_query = @$db->query("SELECT `id`,`views`,`name`,`category`,`description`,`image`,`rating` FROM games WHERE name LIKE '%$search_term%' or description LIKE '%$search_term%' ORDER BY views DESC LIMIT $items_offset,$items_per_page");
	while($g_fetch = @$g_query->fetch_array()){
		list($g_id, $g_views, $g_name, $g_category, $g_description, $g_image, $g_rating) = $g_fetch;
		if($g_rating < 1){$g_rating='No rating';}else{$g_rating=round($g_rating,1);}
		$g_url="games/" . str_replace(' ', '-', urlencode($g_name));
	?>

	<div class='group'>
		<div class='grid-2'>
			<a href='<?php echo $g_url; ?>'><img class='push radius-10' src='<?php echo $g_image; ?>'></a>
		</div>
		<div class='grid-7'>
			<a href='<?php echo $g_url; ?>'><h3 class='bold'><?php echo $g_name; ?></h3></a>
			<h4><?php echo $g_description; ?></h4>
		</div>
		<div class='grid-3'>
			<h5 class='bold'>People Power Rating</h5>
			<h5><?php echo $g_rating; ?> (Maximum 5 points)</h5>
			<h5 class='bold'>Views</h5>
			<h5><?php echo $g_views; ?>~ views</h5>
		</div>
	</div>
	
<?php
	}
	$g_query->free();
}
if($p_count > 0 && $game_page==1){
	$p_query = @$db->query("SELECT users.username, users.fullname, users.email,users_more.user_gold, users_more.user_viewed, users_more.user_categories, users_more.user_picture FROM users,users_more WHERE (users.user_id = users_more.user_id) AND (username LIKE '%$search_term%' or fullname LIKE '%$search_term%' or email LIKE '%$search_term%')");
	?>
	<div class='group'>
		<div class='grid-12'>
			<h2 class='bold'>People Results for <strong><?php if($search_term){echo $search_term;}else{echo"[blank]";} ?></strong></h2>
		</div>
	</div>
	<?php
	while($p_fetch = @$p_query->fetch_array()){
		list($p_username, $p_fullname, $p_email, $p_gold, $p_viewed, $p_categories, $p_picture) = $p_fetch;
		$p_viewed=json_decode($p_viewed, true);
?>

	<div class='group'>
		<div class='grid-2'>
			<a href='profile/<?php echo $p_username; ?>'><img class='push radius-10' src='<?php echo $p_picture; ?>'></a>
		</div>
		<div class='grid-7 grid-parent'>
			<a href='profile/<?php echo $p_username; ?>'><h3 class='bold'><?php echo $p_fullname; ?> (<?php echo $p_username; ?>)</h3></a>
			<h4><strong>Recently Viewed Games</strong></h4>
			<div class='group'>
			<?php
			if(count($p_viewed) > 0){
				$gp_loop=0;
				foreach($p_viewed as $p_view){
					$gp_query=$db->query("SELECT `name`,`image` FROM games WHERE id='$p_view'");
					list($gp_name, $gp_image)=$gp_query->fetch_array();
					$gp_name_url=gameurl($gp_name);
					?>
					<div class='grid-1'>
						<a href='<?php echo $gp_name_url; ?>'><img src='<?php echo $gp_image; ?>'></a>
					</div>
					<?php
					$gp_loop++;
					if($gp_loop==7){break;}
				}
			}else{
				echo "<h5>No viewed games.</h5>";
			}
			?>
			</div>
		</div>
		<div class='grid-3'>
			<h4><strong>Gold</strong>: <?php echo $p_gold; ?> coins</h4>
			<h4><strong>Email</strong>: <?php echo $p_email; ?></h4>
		</div>
	</div>

<?php
	}
	$p_query->free();
}
if($p_count==0 && $g_count==0){
?>
	<div class='group'>
		<div class='grid-12'>
			<h2 class='bold'>No search results found.</h2>
		</div>
	</div>
<?php
}
?>

<div class='group'>
	<?php 
	//Pagination. $games_returned is a variable containing a returned value from the getGamesList function. If the $games_returned does not equal the requested
	//$items_per_page, that means we have hit the last page needed for the page set - the last page holding the remainder items (and not a complete set).
	//If it is page 1, we want to disable the prev button. If last page, disable next button.
	if($g_count||$p_count){
	$prev_game_page=$game_page-1;
	$next_game_page=$game_page+1;
	if($g_count>0){
		$last_game_page=ceil($g_count/$items_per_page);
	}elseif($p_count>0){
		$last_game_page=ceil($p_count/$items_per_page);
	}
	$first_game_page=1;
	if($game_page == $last_game_page && $game_page != $first_game_page){ 
		echo"
		<div class='grid-2 offset-10'>
			<a href='search.php?search_term=$search_term&pg=$prev_game_page'><div class='button'>< Prev</div></a>
		</div>
		"; 
	}
	elseif($game_page == $first_game_page && $game_page != $last_game_page){
		echo"
		<div class='grid-2 offset-10'>
			<a href='search.php?search_term=$search_term&pg=$next_game_page'><div class='button'>Next ></div></a>
		</div>
		"; 
	}elseif($game_page > $first_game_page && $game_page < $last_game_page){
		echo"
		<div class='grid-2 offset-8'>
			<a href='search.php?search_term=$search_term&pg=$prev_game_page'><div class='button'>< Prev</div></a>
		</div>
		<div class='grid-2'>
			<a href='search.php?search_term=$search_term&pg=$next_game_page'><div class='button'>Next ></div></a>
		</div>
		"; 
	}
	}
	?>
</div>



<?php
require_once('format/footer.php');
?>