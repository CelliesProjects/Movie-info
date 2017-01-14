<?php
  $apiKey = trim( file_get_contents('../TMDBapikey.txt') );
  $Url = 'http://api.themoviedb.org/3/movie/';
  $castUrl = '/casts?api_key='.$apiKey;
  $castQuery = $Url.urlencode($_GET['id']).$castUrl;	
  $json_info = file_get_contents($castQuery);	
  echo $json_info;
?>
