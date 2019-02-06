<?php
$api_key        = trim(file_get_contents('../TMDBapikey.txt'));
$watchedFolder  = '/mnt/storage/video/films';   //change this
$programversion = '1.4';
if (isset($_GET['moviename']))
{
  echo file_get_contents("https://api.themoviedb.org/3/search/movie?query=".urlencode( $_GET['moviename'] )."&language=en-US&api_key=".$api_key);
  die();
}
if (isset($_GET['cast']))
{
  echo file_get_contents("https://api.themoviedb.org/3/movie/".urlencode( $_GET['cast'] )."/casts?api_key=".$api_key);
  die();
}
if ( count($_GET) ) die('ERROR unknown request.');

$config = json_decode(file_get_contents('https://api.themoviedb.org/3/configuration?&api_key='.$api_key),true);
$info = $config['images'];
$secure_base_url = $info['secure_base_url'].end($info['backdrop_sizes']);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Movies <?php echo $programversion; ?></title>
<meta name="description" content="Movies">
<meta name="author" content="Cellie">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<style>
html, body {
  min-height:100%;
  height:100%;
  margin:0;
  color:yellow;
  font-variant: small-caps;
  background-color:black;
  font-size:20px;
}
a {
  color:yellow;
  text-decoration:none;
}
#viewport{
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background: no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
#movieList{
  position:absolute;
  top:0;
  right:0;
  bottom:0;
  width:30%;
  overflow-Y:auto;
  text-align:center;
  background-color:black;
  opacity:0.7;
}
#movieList p.item{
  padding:15px;
  font-size:1.3em;
  color: yellow;
}
#movietitle {
  position: absolute;
  top:0;
  left:0;
  width: 70%;
  background-color: black;
  padding: 10px 0;
  text-align:center;
  color:yellow;
  font-size:2em;
  opacity:0.7;
}
#movieinfo {
  position: absolute;
  bottom: 0;
  max-height: 50%;
  background-color: black;
  left: 0;
  right: 0;
  width:70%;
  opacity:0.7;
  overflow-y:auto;
}
#movieinfo p{
  margin: 10px;
  font-size:20px;
}
#movieList p.item:hover{
  cursor:pointer;
}
#attribution, #moviedata {
  text-align:right;
}
#noimage {
  font-size: 2em;
  position: absolute;
  top: 30%;
  transform: translateY(-50%);
  left: 50%;
  transform: translateX(-50%);
}
#castLink {
  cursor:pointer;
}
#castBox{
  text-align: center;
}
</style>
</head>
<body>
<div id="viewport">
<div id="movietitle"></div>
<div id="movieList"><?php
exec('ls '.$watchedFolder,$movie_array);
$badchars = array(".","_");                                              #filenamen opschonen, alle '_' en '.' vervangen door spaties
foreach($movie_array as $film_name)
  echo '<p class="item">'.str_replace($badchars,' ',$film_name).'</p>';
?>
</div>
<div id="movieinfo"></div>
</div>
</body>
<script>
$(document).ready( function(){

  $('#movieList p.item').on('click',function(){
    $('#viewport').fadeTo("fast", 0.4);
    $('#movieinfo').html('');
    $('#movietitle').html('Loading...');
    $('#movieList p.item').css('background-color','black');
    $(this).css('background-color','red');

    $.getJSON(window.location.href+'?moviename='+encodeURI($(this).text()),function(data){
      if (!data.total_results){
        $('#viewport').css('background-image', 'none');
        $('#movietitle').html('No movie information found.');
        $('#viewport').fadeTo("fast", 1);
        return;
      }
      result=data.results[0];
      if (result.backdrop_path !== null){
          $('#viewport').css('background-image', 'url('+'<?php echo $secure_base_url;?>'+result.backdrop_path+')').fadeTo("fast", 1);
          $('#noimage').remove();
      } else {
          $('#viewport').css('background-image', 'none').append('<p id="noimage">No movie image found.</p>');
      }
      attribution = '<p id="attribution">Movie info and graphics provided by <a href="https://themoviedb.org/" target="_blank">themoviedb.org</a></p><hr>';
      castlink = '<p id="castLink" data-movieid="'+result.id+'">Click here to show the cast.</p><div id="castBox"></div>';
      movieInfo = '<p id="moviedata">'+result.release_date.substr(0,4)+' - Language: '+result.original_language.toUpperCase()+ ' - Rating: '+result.vote_average+' with '+result.vote_count+' votes<hr></p>' ;
      $('#movieinfo').html(attribution+castlink+movieInfo+'<p>&nbsp;&nbsp;&nbsp;Plot:</p>'+'<p>'+result.overview+'</p>').scrollTop(0);
      $('#movietitle').html(result.original_title);
    });
  });

  $('#movieinfo').on('click','#castLink',function(){
    $('#castBox').show().html('Loading cast data...');
    $('#castLink').html('');
    $.getJSON(window.location.href+'?cast='+$(this).data('movieid'),function(data){
      $('#castBox').html('<p>Cast:</p>');
      $.each(data.cast,function(){
        $('#castBox').append('<p>',this.character,' - ',this.name,'</p>');
      });
      $('#castBox').append('<hr>');
    });
  });

  $('#movieList p.item').first().click();
});
</script>
</html>
