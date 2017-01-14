<!doctype html>
<?php
  $watchedFolder = '/media/storage/video/films';   //change this

  $programversion = '1.3';
  $apiKey = trim( file_get_contents('../TMDBapikey.txt') );
  
	$dbUrl = 'https://api.themoviedb.org/3/configuration?';
	$key   = '&api_key='.$apiKey;
	$json_config = file_get_contents($dbUrl.$key);		
	//echo $json_config;
	$config = json_decode($json_config,true);	
	$info = $config['images'];	
	$base_url = $info['base_url'];
	//echo '<br>'.$base_url;
	$backdrop_size = end($info['backdrop_sizes']);
	//echo '<br>'.$backdrop_size;	
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Movies <?php echo $programversion; ?></title>
  <meta name="description" content="Movies">
  <meta name="author" content="Cellie">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="style.css">
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>
  <div id="viewport">	
  <div id="movietitle"></div>
  	<div id="movieList">		    		  
		<?php
		exec( 'ls '.$watchedFolder , $Gevonden_films_array );
			$badchars = array(".","_"); #filenamen opschonen, alle '_' en '.' vervangen door spaties
			foreach($Gevonden_films_array as $film_naam) {
				$film_naam = str_replace($badchars,' ',$film_naam);
				echo '<p class="item">' . $film_naam . '</p>';
			}; 
		?>
	</div>
	
  <div id="movieinfo"><p></p></div>
  </div>

</body>

<script>
	
function showCast(movieID){	
	$.getJSON('castinfo.php?id='+movieID, function( data ) {
		console.log(data);
		$('#castBox').html('<p>Cast:</p>');
		$.each(data.cast,function(){
			//console.log(this.character,'',this.name);
			$('#castBox').append('<p>',this.character,' - ',this.name,'</p>');
			});
		$('#castBox').append('<hr>');
  });
}
	
  $(document).ready( function(){
	  var backdrop_size = '<?php echo $backdrop_size; ?>',
	      base_url = '<?php echo $base_url; ?>';
	      
	  $('#movieList p.item').on('click',function(){	
		  $('#movietitle').html('Loading info...');
		  $('#movieList p.item').css('background-color','black');
		  $(this).css('background-color','red');	
		  console.log($(this).text());	  	
		  $.getJSON('movieinfo.php?moviename='+$(this).text(), function( data ) {
			console.log(data);
			results = data.results;
			if (results[0].backdrop_path !== null) {
				backdrop_url = base_url+backdrop_size+results[0].backdrop_path;
				$('#viewport').css('background-image', 'url('+backdrop_url+')');
				$('#noimage').remove();
			} else {
			    $('#viewport').css('background-image', 'none').append('<p id="noimage">No movie image found.</p>');
			}
			plot = results[0].overview;
			attribution = '<p id="attribution">Movie info and graphics provided by <a href="http://themoviedb.org/" target="_blank">themoviedb.org</a></p><hr>';
			castlink = '<p id="castLink" data-movieid="'+results[0].id+'">Click here to show the cast.</p><div id="castBox"></div>';
			movieInfo = '<p id="moviedata">'+results[0].release_date.substr(0,4)+' - Language: '+results[0].original_language.toUpperCase()+ ' - Rating: '+results[0].vote_average+' with '+results[0].vote_count+' votes<hr></p>' ;
			$('#movieinfo').html(attribution+castlink+movieInfo+'<p>&nbsp;&nbsp;&nbsp;Plot:</p>'+'<p>'+plot+'</p>').scrollTop(0);
			$('#movietitle').html(results[0].original_title);
			});
    });		 
		  
	$('#movieinfo').on('click','#castLink',function(){
		//alert($(this).data('movieid'));
		$('#castBox').show().html('Loading cast data...');
		$('#castLink').html('');
		showCast($(this).data('movieid'));
  });
	
	
	$('#movieList p.item').first().click();
	});
</script>
</html>
