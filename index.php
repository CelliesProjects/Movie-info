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
if (isset($_GET['icon']))
{
  if ($_GET['icon']=='search')
 {
    header("Content-Type: image/svg+xml");
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/><path d="M0 0h24v24H0z" fill="none"/></svg>';
    //https://material.io/tools/icons/?icon=search&style=baseline
    die();
  }
  die('invalid icon');
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
<meta name="viewport" content="minimal-ui, width=device-width, initial-scale=.7, maximum-scale=.7, user-scalable=no">
<title>Movies <?php echo $programversion; ?></title>
<meta name="description" content="Movies">
<meta name="author" content="Cellie">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">  <!-- https://fonts.google.com/specimen/Roboto?selection.family=Roboto -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<style>
html, body{
  min-height:100%;
  height:100%;
  margin:0;
  color:yellow;
  background-color:black;
  font-size:20px;
  font-family:'Roboto', sans-serif;
}
a{
  color:yellow;
  text-decoration:none;
}
#viewport{
  position:fixed;
  top:0;
  left:0;
  bottom:0;
  right:0;
  background:no-repeat center center fixed;
  -webkit-background-size:cover;
  -moz-background-size:cover;
  -o-background-size:cover;
  background-size:cover;
}
#movietitle{
  position:absolute;
  top:0;
  left:0;
  width:70%;
  background-color:black;
  text-align:center;
  color:yellow;
  font-size:2em;
  opacity:0.7;
  height:60px;
  line-height:60px;
  white-space:nowrap;
  overflow:hidden;
}
#searchBox{
  width:30%;
  height:60px;
  position:absolute;
  top:0;
  right:0;
  display:flex;
}
#searchText{
  width:100%;
  text-align:center;
  background:gray;
  font-size:xx-large;
  color:yellow;
  opacity:0.7;
  border:none;
  }
#searchButton{
  cursor:pointer;
  width:70px;
  min-width:70px;
  min-width:70px;
  background-color:yellow;
  opacity:0.5;
}
#movieList{
  position:absolute;
  top:60px;
  right:0;
  bottom:0;
  width:20%;
  overflow-Y:auto;
  text-align:center;
  background-color:black;
  opacity:0.7;
}
.item{
  padding:15px;
  color:yellow;
}
.item:hover{
  cursor:pointer;
  background-color:green;
}
#movieinfo{
  position:absolute;
  bottom:0;
  max-height:50%;
  background-color:black;
  left:0;
  right:20%;
  opacity:0.7;
  overflow-y:auto;
}
#movieinfo p{
  margin:0 5px;
}
#attribution, #moviedata{
  text-align:right;
  font-size:smaller;
}
#noimage{
  font-size:2em;
  position:absolute;
  top:30%;
  transform:translateY(-50%);
  left:50%;
  transform:translateX(-50%);
}
#castLink{
  cursor:pointer;
}
#castBox{
  text-align:center;
}
.noselect{
  -webkit-touch-callout:none; /* iOS Safari */
    -webkit-user-select:none; /* Safari */
     -khtml-user-select:none; /* Konqueror HTML */
       -moz-user-select:none; /* Firefox */
        -ms-user-select:none; /* Internet Explorer/Edge */
            user-select:none; /* Non-prefixed version, currently
                                  supported by Chrome and Opera */
}
</style>
</head>
<body class="noselect">
<div id="viewport">
<div>
<div id="movietitle"></div>
<div id="searchBox"><input id="searchText" type="text"><img src="?icon=search" id="searchButton"></div>
</div>
<div id="movieList"><p style="color:white;">Local movies:</p><?php
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
$(document).ready(function(){

  $('body').on('click','.item',function(){
    $('#searchText').val('');
    $('#viewport').fadeTo("fast", 0.4);
    $('#movieinfo').html('');
    $('#movietitle').html('Loading...');
    $('.item').css('background-color','');
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
      } else{
          $('#viewport').css('background-image', 'none').append('<p id="noimage">No movie image found.</p>').fadeTo("fast", 1);
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

  $('#searchBox').keyup(function(e){
    if (e.key==="Enter"){
      $('#searchButton').click();
    }
  });

  $('#searchButton').on('click',function(){
    if(!$('#searchText').val())return;
    //add a invisible item to the movielist, click it and delete it
    var nList=document.createElement("p");
    var t=document.createTextNode($('#searchText').val());
    nList.appendChild(t);
    nList.style.display="none";
    nList.className="item";
    document.getElementById("movieList").appendChild(nList);
    $('.item').last().click();
    document.getElementById("movieList").removeChild(nList);
    //remove focus from searchText (to remove the android keyboard from screen)
    //todo: set width and height to zero! (before adding to DOM)
    var field = document.createElement('input');
    field.setAttribute('type', 'text');
    document.body.appendChild(field);
    field.focus();
    field.style.display="none";
    document.body.removeChild(field);
  });

  $('.item').first().click();
});
</script>
</html>
