<?php
require_once('format/header.php');
require_once('format/connect.php');

$game_page= !empty($_GET['pg']) ? $db->real_escape_string(strip_tags($_GET['pg'])) : 1;
$game_cat = !empty($_GET['cat']) ? $db->real_escape_string(strip_tags($_GET['cat'])) : 'all';

$items_per_page = 36;
$rows = 6;
$offset = $items_per_page * ($game_page - 1);

//If the category passed in the URL is not an MRTP game category, present the invalid message. We don't want hacks.
$game_categories=array(action, adventure, driving, shooting, strategy, sports, other);
if(!in_array($game_cat, $game_categories)){ echo"<h2 class='bold'>Invalid category name</h2>"; }else{
?>

<div class='group'>
	<h2 class='bold'><?php echo ucfirst($game_cat); ?> Games</h2>
</div>

<?php
if($game_page <= 1){
$game_page = 1;
?>

<div class='group'>
	<?php
	//Loop to get the newest, highest rated, and most viewed games for a certain category - either it be action, adventure, other, etc
	$top_section_ratings = array(id=>'Newest', rating=>'Highest Rated', views=>'Most Viewed');
	foreach($top_section_ratings as $top_section_rating=>$human_name){
		echo"
		<div class='grid-4 grid-parent'>
			<h3 class='bold'>$human_name Games</h3>";
			getGamesList($top_section_rating, 6, 3, 'grid-4', games, array(category=>$game_cat));
		echo "</div>";
	}
	?>
</div>

<div class='group'>
	<hr/>
</div>

<?php
}
?>

<div class='group'>
	<div class='grid-12 grid-parent'>
		<?php $games_returned = getGamesList(name, $items_per_page, $rows, 'grid-12', games, array(category=>$game_cat), $offset, ASC); ?>
	</div>
</div>


<div class='group'>
	<?php 
	}
	
	//Pagination. $games_returned is a variable containing a returned value from the getGamesList function. If the $games_returned does not equal the requested
	//$items_per_page, that means we have hit the last page needed for the page set - the last page holding the remainder items (and not a complete set).
	//If it is page 1, we want to disable the prev button. If last page, disable next button.
	$prev_game_page=$game_page-1;
	$next_game_page=$game_page+1;
	if($games_returned < $items_per_page){ 
		echo"
		<div class='grid-2 offset-10'>
			<a href='games_cat.php?cat=$game_cat&pg=$prev_game_page'><div class='button'>< Prev</div></a>
		</div>
		"; 
	}
	elseif($game_page == 1){
		echo"
		<div class='grid-2 offset-10'>
			<a href='games_cat.php?cat=$game_cat&pg=$next_game_page'><div class='button'>Next ></div></a>
		</div>
		"; 
	}
	else{
		echo"
		<div class='grid-2 offset-8'>
			<a href='games_cat.php?cat=$game_cat&pg=$prev_game_page'><div class='button'>< Prev</div></a>
		</div>
		<div class='grid-2'>
			<a href='games_cat.php?cat=$game_cat&pg=$next_game_page'><div class='button'>Next ></div></a>
		</div>
		"; 
	}
	?>
</div>

<?php require('format/footer.php'); ?>