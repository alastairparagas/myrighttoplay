<?php
$link=mysql_connect('localhost','saterniv_tair','CocoLoco1234abcd') or die('There has been an error establishing server connection!');
$selectdb=mysql_select_db("saterniv_gamesite");

$q=mysql_query("SELECT * FROM news");
$shouts=array();
while($r=mysql_fetch_assoc($q)){

   $shouts[]=array(        
       
       'person' => $r['person'],   
       'face' => $r['face'],
	   'message' => $r['message']
	
    );
   }
   foreach($shouts as $s){
   $person=$s['person'];
   $face=$s['face'];
   $message=$s['message'];
   $persons.=$person."|";
   $faces.=$face."|";
   $messages.=$message."|";
   }


//return values to confirm upload
echo "Person=".$persons."&Face=".$faces."&Message=".$messages;
?>