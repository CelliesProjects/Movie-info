<?php
	//include('debug.php');
        //get the api key from disk
        $apiKey = trim( file_get_contents('../TMDBapikey.txt') );
	$queryUrl = 'http://api.themoviedb.org/3/search/movie?query=';
	$key   = '&api_key='.$apiKey;
	$appendResponse = '&append_to_response=trailers&language=en';
	$movieQuery = $queryUrl.urlencode($_GET['moviename']).$key.$appendResponse;	
	$json_info = file_get_contents($movieQuery);	
	echo $json_info;
?>
