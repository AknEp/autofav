<?php
/* 
 * <autofav.php>
 * 
 * by あおみかん@AknEpヾ(❀╹◡╹)ﾉﾞ
 * 
 */

/* You have to create <config{.n}.php> 
 * ============================== * 
 * $consumer_key        = "hoge";
 * $consumer_secret     = "hoge";
 * $access_token        = "hoge";
 * $access_token_secret = "hoge";
 * $username            = "eri_twin_fav_bot";
 * $password            = "eri_twin_fav_bot_password";
 * $target              = 252004214; // DO NOT CHANGE!!!! HA-HA-HA!
 * ============================== */

$getnum = 0;
if(count($argv) >= 2){
	$getnum = intval($argv[1]);
}
if($getnum > 0){
	require(sprintf('config.%d.php',$getnum));
}else{
	require('config.php');
}

require("twitteroauth/twitteroauth.php");
$twitter = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);

$stream = fopen("https://{$username}:{$password}@stream.twitter.com/1/statuses/filter.json?follow={$target}", "r");
while ($json = fgets($stream ) ) {
	$post= json_decode($json,true);
	if(is_array($post) && array_key_exists('user',$post) && array_key_exists('id',$post['user']) ){
		if($post['user']['id']==$target){
			$fav_post_id = intval($post['id']);
			if($fav_post_id > 0){
				$result = $twitter->OAuthRequest("https://api.twitter.com/1/favorites/create/{$fav_post_id}.json","POST",array());
				printf("favved [%d] %s \n",$fav_post_id,$post['text']);
			}
		}
	}
}