<?php
function getGamesList($order_by='id', $items='4', $rows='1', $grid_size='grid-12', $db_table='games', $db_where=NULL, $items_offset='0', $order_dir='DESC'){
	global $db;
	if($order_by=='random'){$order_dir=''; $order_by='RAND()';}
	if(is_array($db_where)){
		/* If $db_where is an array, we're asking for a search in a table column eg. id='5', name='whatever'. - array is associative
			The array keys for the $db_where array are the names of the wanted columns. eg. id, name
			The array values for the $db_where array are the values that are being searched. eg. '5', 'whatever'
			We are going to loop through the array. Each additional key/value pair will be appended to the WHERE statement as AND this='thisvalue'
		*/
		$db_columns=array_keys($db_where);
		/* If there are more than one key/value pair in $db_where array, loop through and append results. If not, just get first result.
			We are going to get the key and value pairs (the column and the search term). We're then constructing our MYSQL search statement.
		*/
		if(count($db_columns) > 1){
			foreach($db_columns as $db_column_counter){
				$db_column=$db_columns[$db_column_counter];
				$db_column_search=$db_where[$db_column];
				if($db_column_counter > 0){
					$db_search_statement.= " AND `$db_column` LIKE '%$db_column_search%'";
				}else $db_search_statement = "`$db_column` LIKE '%$db_column_search%'";
			}
		}else{
			$db_column=$db_columns[0];
			$db_column_search=$db_where[$db_column];
			$db_search_statement = "`$db_column` LIKE '%$db_column_search%'";
		}
		$g_query=$db->query("SELECT `id`, `name`, `description`, `image` FROM $db_table WHERE $db_search_statement ORDER BY $order_by $order_dir LIMIT $items_offset, $items");
	}else{
		$g_query=$db->query("SELECT `id`, `name`, `description`, `image` FROM $db_table ORDER BY $order_by $order_dir LIMIT $items_offset, $items");
	}
	$items_per_row=ceil($items/$rows);
	$grid_sizes=array('grid-12'=>array(2=>'6', 3=>'4', 4=>'3', 6=>'2', 12=>'1'), 'grid-6'=>array(2=>'3', 3=>'2'), 'grid-4'=>array(2=>'2'));
	$grid_column_size=$grid_sizes[$grid_size]; //Get custom size sets of nested boxes for the game based on elements needed for that one row.
	$grid_nest_size=$grid_column_size[$items_per_row]; //Size of nested box for the game.
	$grid_nest_desc_size=$grid_nest_size-1;
	$counter=0;
	// Let's get individual results of the loop.
	while($g_fetch=$g_query->fetch_array()){
		$g_id=$g_fetch['id'];
		$g_name=$g_fetch['name'];
		$g_description=$g_fetch['description'];
		// Escape the quotes of the game's description so it doesn't break HTML.
		$g_desc_alt=htmlentities($g_description, ENT_QUOTES);
		if(strlen($g_name) >= 15){
			$g_name_short=substr($g_name, 0, 12);
			$g_name_short.="...";
		} else $g_name_short = $g_name;
		$g_image=$g_fetch['image'];
		$g_url="games/" . str_replace(' ', '-', urlencode($g_name));
		if($counter==0){ echo "<div class='group'>"; }
		$counter++;
		// If we're pulling games, then the grid must add a class of game onto the grid child, so we can target the css hover effect with it.
		if($db_table=='games'){$grid_starter="<div class='game grid-$grid_nest_size' data-gamedesc='$g_desc_alt'>";}else{$grid_starter="<div class='grid-$grid_nest_size'>";}
		if(($grid_size=='grid-12' && $items_per_row<6) || ($grid_size=='grid-6' && $items_per_row<6)){
			echo "
			$grid_starter
				<div class='grid-1 alpha' itemscope itemtype='http://schema.org/MediaObject'>
					<a itemprop='url' href='$g_url'><img src='$g_image' class='radius-10' alt='$g_desc_alt'></a>
					<meta itemprop='name' content='$g_name' />
					<meta itemprop='description' content='$g_description' />
					<meta itemprop='thumbnailUrl' content='$g_image' />
				</div>
				<div class='grid-$grid_nest_desc_size omega pad-heading'>
					<a href='$g_url'><h5>$g_name_short</h5></a>
				</div>
			</div>
			";
		}else{
			echo "
			$grid_starter
				<div class='grid-$grid_nest_size' itemscope itemtype='http://schema.org/MediaObject'>
					<a itemprop='url' href='$g_url'><img src='$g_image' class='radius-10' alt='$g_desc_alt'></a>
					<meta itemprop='name' content='$g_name' />
					<meta itemprop='description' content='$g_description' />
					<meta itemprop='thumbnailUrl' content='$g_image' />
					<a href='$g_url'><h5>$g_name_short</h5></a>
				</div>
			</div>
			";
		}
		if($counter==$items_per_row){ echo "</div>"; $counter=0; } // Reset items per row counter. Start a new row. 
	}
	// End loop
	return $g_query->num_rows;
	$g_query->free();
}

function gameurl($game_name){
	$g_url="games/" . str_replace(' ', '-', urlencode($game_name));
	return $g_url;
}

function timestampdate($date){
	return date('M j, Y', $date);
}

function timestamptime($date){
	return date('M j, Y (G:i:s)', $date);
}

define('ABSOLUTE_URL', 'http://myrighttoplay.com/');
?>
