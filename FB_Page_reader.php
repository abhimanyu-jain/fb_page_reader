<?php

//This is for one page, whose profile_id is being used.

function fetchUrl($url){

 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_TIMEOUT, 20);
 // You may need to add the line below
 // curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

 $feedData = curl_exec($ch);
 curl_close($ch); 

 return $feedData;

}

$profile_id = "####";

//App Info, needed for Auth
$app_id = "###";
$app_secret = "###";

//Retrieve auth token
$authToken = fetchUrl("https://graph.facebook.com/oauth/access_token?grant_type=client_credentials&client_id={$app_id}&client_secret={$app_secret}");

$json_object = fetchUrl("https://graph.facebook.com/{$profile_id}/posts?{$authToken}");

$feedarray = json_decode($json_object);


foreach ( $feedarray->data as $feed_data )
{	
	echo "<br/><br/>{$feed_data->message}<br/><br/>";
    
	$img_path = fetchUrl("https://graph.facebook.com/{$feed_data->id}?fields=attachments&{$authToken}");

    $x = json_decode($img_path);

    if($x->attachments->data[0]->subattachments)
    {
    	foreach ($x->attachments->data[0]->subattachments->data as $value) 
    	{
    		$path = $value->media->image->src;
    		echo "<br/><br/><img src=$path height='200px' width='200px'>";
    	}
    }

    else
    {
    	$path = $x->attachments->data[0]->media->image->src;
	    echo "<img src=$path height='200px' width='200px'>";

    }

    
   
    /*
    
    /*
	echo "<h2>{$feed_data->name}</h2><br />";
    echo '<img src="{$img_path}" alt="Smiley face" height="200" width="200">';
    */
}

?>

