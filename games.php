<?php require_once('format/header.php'); ?>


<div class='group'>
	<div class='grid-4 grid-parent'>
		<h3 class='bold'>New Games</h3>
		<?php
		require_once('format/connect.php');
		getGamesList(id, 6, 3, 'grid-4');
		?>
	</div>
	<div class='grid-4 grid-parent'>
		<h3 class='bold'>Highest Rated Games</h3>
		<?php
		require_once('format/connect.php');
		getGamesList(rating, 6, 3, 'grid-4');
		?>
	</div>
	<div class='grid-4 grid-parent'>
		<h3 class='bold'>Most Viewed Games</h3>
		<?php
		getGamesList(views, 6, 3, 'grid-4');
		?>
	</div>
</div>

<div class='group'>
	<hr/>
</div>

<?php 
$categories_array=array(action, adventure, driving, shooting, strategy, sports, other);
foreach($categories_array as $g_cat){
	$g_cat_name=ucfirst($g_cat);
	echo"
	<div class='group'>
		<div class='grid-12 grid-parent'>
			<h3 class='bold'>$g_cat_name Games</h3>";
	getGamesList(views, 12, 2, 'grid-12', games, array(category=>$g_cat));
	echo"
		</div>
		<div class='grid-3 offset-8'>
			<a href='games_cat.php?cat=$g_cat'><div class='button'>More $g_cat_name Games</div></a>
		</div>
	</div>
	";
}
?>

<?php require_once('format/footer.php'); ?>
