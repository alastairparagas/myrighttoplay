<?php
require('../format/connect.php');

$g_json = "http://feedmonger.mochimedia.com/feeds/query/?q=(not%20languages%3Azh-cn)%20and%20recommendation%3A%3E%3D4&limit=10";
$g_array = json_decode(file_get_contents($g_json),true);

foreach($g_array[games] as $game){

$g_name=$db->real_escape_string($game['name']);
$g_slug=$db->real_escape_string($game['slug']);
$g_auth=$db->real_escape_string($game['author']);
$g_auth_link=$db->real_escape_string($game['author_link']);
$g_desc=$db->real_escape_string($game['description']);
$g_width=$db->real_escape_string($game['width']);
$g_height=$db->real_escape_string($game['height']);
$g_keyword=$db->real_escape_string(implode(" ",$game['categories']));
$g_largeimage=$game['thumbnail_large_url'];


$g_swf_addr = "../swfs/$g_slug.swf";
$swf = fopen($g_swf_addr, "w");
fwrite($swf, file_get_contents($game['swf_url']));
$g_swf_addr = "http://www.myrighttoplay.com/swfs/$g_slug.swf";

$g_img_addr = "../icon/$g_slug.gif";
$img = fopen($g_img_addr, "w");
fwrite($img, file_get_contents($game['thumbnail_url']));
$g_img_addr = "http://www.myrighttoplay.com/icon/$g_slug.gif";

$g_tquery=$db->query("SELECT name FROM games WHERE `name`='$g_name'");
$g_count=$g_tquery->num_rows;
if($g_count>0){
$db->query("UPDATE `games` SET `category`='$g_keyword' WHERE `name`='$g_name'");

}else{
$g_max_query=$db->query("SELECT `id` FROM games ORDER BY `id` DESC");
$g_max_fetch=$g_max_query->fetch_array();
$g_max_id=$g_max_fetch['id'];
$g_id = $g_max_id + 1;
$db->query("INSERT INTO `games` VALUES('$g_id','0','$g_keyword','$g_name','$g_desc','$g_width','$g_height','$g_swf_addr','$g_img_addr','0')");
$current_date=date("m/d/y");
$g_max_query->free();

//Let's update social media - Blogger, Facebook, Twitter
$g_desc=str_replace('\n', ' ', $g_desc);
$g_name_safe=str_replace(' ', '-', $g_name);
$g_name_safe_url=urlencode("http://www.myrighttoplay.com/games/$g_name_safe");
$short_json=json_decode(file_get_contents("http://po.st/api/shorten?longUrl=$g_name_safe_url&apiKey=9F126D0D-51C4-47E2-831E-2FBF0D35092E"), true);
$short_url=$short_json['short_url'];

//Start with Blogger - authorize access token and send a blog post.
$auth_curl=curl_init("https://accounts.google.com/o/oauth2/token");
$auth_parameters='client_id=763000315082-s9kiq918n59ghfju98gu5jc5kf6uc3kf.apps.googleusercontent.com&client_secret=5wzUbhH5lneS_8YG0prL6OGj&refresh_token=1/K1k8q2Xl_ivjnUqP5_lXx-RNyK8gwSRWi8PFxfysbMs&grant_type=refresh_token';
$data_length=strlen($auth_parameters);
curl_setopt($auth_curl, CURLOPT_POSTFIELDS, $auth_parameters);
curl_setopt($auth_curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($auth_curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($auth_curl, CURLOPT_HTTPHEADER, 
array(
'Host: accounts.google.com',
"Content-Length: $data_length",
'Content-Type: application/x-www-form-urlencoded'));
$auth_execute=json_decode(curl_exec($auth_curl), true);
$access_token=$auth_execute['access_token'];
$blogger_api="AIzaSyAMZ6ibyiVRi3ZbVEpEFKEhcE50c8goehk";
$blogger_title="$g_name - $current_date";
$blogger_content="$g_desc <br/><br/> Play <a href='http://www.myrighttoplay.com/games/$g_name_safe'>$g_name</a> now  @ myrighttoplay.com!";
$blogger_data = json_encode(array('kind' => 'blogger#post', 'blog' => array('id' => '2092057069570934927'), 'title' => "$blogger_title", 'content' => "$blogger_content"));
$blogger_curl=curl_init("https://www.googleapis.com/blogger/v3/blogs/2092057069570934927/posts/?key=$blogger_api");
curl_setopt($blogger_curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($blogger_curl, CURLOPT_POSTFIELDS, $blogger_data);
curl_setopt($blogger_curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($blogger_curl, CURLOPT_HTTPHEADER, 
array(
"Authorization: Bearer $access_token",
'Content-Type: application/json'));
$blogger_execute=curl_exec($blogger_curl);
echo $blogger_execute;


//Facebook API - autopost to wall - send through email with Mime
$g_largeimage=file_get_contents($g_largeimage);
$mime_boundary=md5(time());
$fb_header .= 'From: us@myrighttoplay.com <us@myrighttoplay.com>' . "\n";
$fb_header .= 'MIME-Version: 1.0'. "\n";
$fb_header .= "Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"". "\n"; 
$fb_attachment .= "--".$mime_boundary. "\n";
$fb_attachment .= "Content-Type: text/plain; charset=iso-8859-1". "\n";
$fb_attachment .= "Content-Transfer-Encoding: 7bit". "\n\n";
$fb_attachment .= "--".$mime_boundary. "\n";
$fb_attachment .= "Content-Type: image/png; name=\"gamesnapshot.png\"". "\n";
$fb_attachment .= "Content-Transfer-Encoding: base64". "\n";
$fb_attachment .= "Content-Disposition: attachment; filename=\"gamesnapshot.png\"". "\n\n";
$fb_attachment .= chunk_split(base64_encode($g_largeimage)) . "\n\n";
$fb_attachment .= "--".$mime_boundary."--". "\n\n";
$fb_content="$g_name: $g_desc $short_url";
mail("nashua316pilfer@m.facebook.com","$fb_content","$fb_attachment","$fb_header");
mail("alastairparagas@gmail.com","$fb_content","$fb_attachment","$fb_header");
}
}


?>