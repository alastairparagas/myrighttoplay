<?php
require('../format/connect.php');

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
$token_type=$auth_execute['token_type'];

$blogger_api="AIzaSyAMZ6ibyiVRi3ZbVEpEFKEhcE50c8goehk";
$blogger_title="$g_name - $current_date";
$blogger_content="$g_desc <br/><br/> Play <a href='www.myrighttoplay.com/gamepage.php?gameid=$g_id'>$g_name now</a>  @ myrighttoplay.com!";
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

/*
$auth_curl=curl_init("https://accounts.google.com/o/oauth2/token");
$auth_param='code=4/llcO9uveORQlTYFsPixkOvSTlobr.kgwkVNDsTQAZRJPr4JvC3xT2RJ2TdgI&redirect_uri=http://myrighttoplay.com/imports/test.php&client_id=763000315082-s9kiq918n59ghfju98gu5jc5kf6uc3kf.apps.googleusercontent.com&scope=&client_secret=5wzUbhH5lneS_8YG0prL6OGj&grant_type=authorization_code';
$data_length=strlen($auth_param);
curl_setopt($auth_curl, CURLOPT_POSTFIELDS, $auth_param);
curl_setopt($auth_curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($auth_curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($auth_curl, CURLOPT_HTTPHEADER, 
array(
'Host: accounts.google.com',
"Content-Length: $data_length",
'Content-Type: application/x-www-form-urlencoded'));
$auth_execute=json_decode(curl_exec($auth_curl), true);
$access_token=$auth_execute['access_token'];
$refresh_token=$auth_execute['refresh_token'];

echo $refresh_token;
*/

?>