<?php
require('format/header.php');

if($g_rating < 1){$g_rating='No rating';}else{$g_rating=round($g_rating,1);}

//Make sure g_width and g_height fits container_12 and does not overflow
if($g_width > 940){
	$g_height = ceil($g_height/($g_width/940));
	$g_width = 940;
}
?>

<style type='text/css'>
	#game_menu_box h5[id*="game"]{cursor:pointer;}
</style>

<script type='text/javascript'>
$(function(){
var game_width = "<?php echo $g_width; ?>";
var game_height = "<?php echo $g_height; ?>";
var window_width = $(window).width();
var window_height = $(window).height();

/* Tell backend php script that page has been visited. Send stats. */
$.post("<?php echo ABSOLUTE_URL; ?>ajax/gamepage-visit.php", {g_id:"<?php echo $g_id_real; ?>"});

/* When the h5s with IDs like 'game_' under the menu options box are clicked, do the appropriate ajax option*/
$("#game_menu_box h5[id*='game_']").click(function(){
	var action_requested = this.id;
	$.get("<?php echo ABSOLUTE_URL; ?>ajax/gamepage-actions.php", {g_action:action_requested, g_id:"<?php echo $g_id_real; ?>"}, function(data){if(data){alert(data);}});
});

/* If game_height is smaller than game_width, game_height and window_height equal each other, and vice-versa. 
	Why? Smallest dimension = safest point of reference or you risk overflowing the other dimension. */
$('#gameit_fullscreen').click(function() {
	alert('Your game will now be maximized. To minimize the game back to its original size, click anywhere outside the game.');
	$('.g_overlay').addClass('overlay').show();
	if(game_height > game_width){
		/* Game height is larger than the width. Base measurements off of smaller dimension
			to ensure that both the smaller and larger dimensions fit in the screen. */
		new_game_width = window_width - 60;
		new_game_height = Math.ceil(game_height/(game_width/new_game_width));
		//If game height is still taller than window height, keep resizing the game further
		if( new_game_height > window_height){
			new_game_height= window_height- 60;
			new_game_width = Math.ceil(game_width/(game_height/new_game_height));
		}
	}else{
		/* Game width is larger than or equal to the height. Base measurements off of smaller dimension
			to ensure that both the smaller and larger dimensions fit in the screen. */
		new_game_height= window_height- 60;
		new_game_width = Math.ceil(game_width/(game_height/new_game_height));
		//If game width is still wider than window width, keep resizing the game further
		if( new_game_width > window_width){
			new_game_width= window_width- 60;
			new_game_height = Math.ceil(game_height/(game_width/new_game_width));
		}
	}
	new_game_height = new_game_height + "px";
	new_game_width = new_game_width + "px";
	$('.g_container').css('height', new_game_height).css('width', new_game_width).css('margin-top', '20px').css('z-index', '1000');
	$('#swf_game_object').css('height', new_game_height).css('width', new_game_width).attr('height', new_game_height).attr('width', new_game_width);
	$('#swf_game_embed').css('height', new_game_height).css('width', new_game_width).attr('height', new_game_height).attr('width', new_game_width);
});

$('.g_overlay').click(function(){
	$('.g_overlay').removeClass('overlay').css('z-index', '10');
	$('.g_container').css('height', game_height).css('width', game_width).css('margin-top', '0px');
	$('#swf_game_object').css('height', game_height).css('width', game_width).attr('height', game_height).attr('width', game_width);
	$('#swf_game_embed').css('height', game_height).css('width', game_width).attr('height', game_height).attr('width', game_width);
});
/* We're preventing a click state even from happening when the g_container inside g_overlay is clicked. Why?
	we don't want players to minimize the window because they we're clicking on the game, which is surrounded
	by the g_container! */
$('.g_overlay > .g_container').click(function(event){
	event.stopPropagation();
});
});

</script>

<div class='group'>
	<div class='grid-12'>
		<div class='g_overlay'>
			<div class='g_container' style='width:<?php echo $g_width; ?>px; height:<?php echo $g_height; ?>px;'>
			<object id='swf_game_object' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,' 
			style='width:<?php echo $g_width; ?>px; height:<?php echo $g_height; ?>px; z-index:100;'>
				<param name='movie' value='<?php echo $g_url; ?>' />
				<param name='quality' value='medium' />
				<param name='wmode' value='transparent' />
			<embed id='swf_game_embed' src='<?php echo $g_url; ?>' style='width:<?php echo $g_width; ?>px; height:<?php echo $g_height; ?>px; z-index:100;' 
			quality='medium' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' wmode='transparent'></embed>
			</object>
			</div>
		</div>
	</div>
</div>

<div class='group' itemscope itemtype='http://schema.org/MediaObject'>
	<div class='grid-8 importance pad-heading' id='game_menu_box'>
		<h2 class='bold' itemprop='name'><?php echo $g_name; ?></h2>	
		<h4 itemprop='description'><?php echo $g_description; ?></h4>
		<h5 class='inline bold' id='game_favorite'>Favorite</h5>
		<h5 class='inline bold' id='game_share'>Share</h5>
		<h5 class='inline bold' id='game_report'>Report Problem</h5>
		<h5 class='inline bold' id='gameit_fullscreen'>Full Screen</h5>
		<h5 class='inline bold' id='game_rate'>Rate Game</h5>
	</div>
	<div class='grid-3 offset-1 grid-parent'>
		<div class='group'>
			<div class='grid-1'><img src='<?php echo $g_image; ?>' />
			<meta itemprop='thumbnailUrl' content='<?php echo $g_image; ?>' />
			</div>
		</div>
		<div class='group'>
			<div class='grid-3'>
			<h5 class='bold'>People Power Rating</h5> 
			<h5 itemprop='aggregateRating' itemscope itemtype='http://schema.org/AggregateRating'>
				<span itemprop='ratingValue'><?php echo $g_rating; ?></span>
				(maximum <span itemprop='bestRating'>5</span> points)
				<meta itemprop='worstRating' content='1' />
			</h5>
			<h5 class='bold'>Game Category</h5>
			<h5 itemprop='genre'><?php echo str_replace(' ', ', ' , $g_category); ?></h5>
			<h5 class='bold'>User Views</h5>
			<h5 itemprop='interactionCount'><?php echo $g_views; ?> views</h5>
			</div>
		</div>
	</div>
</div>

<div class='group'>
	<div class='grid-8' id='comment_box'>
	
	</div>
	<div class='grid-3 offset-1'>
		
	</div>
</div>


<?php
require('format/footer.php');
?>
