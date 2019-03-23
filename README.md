# Movie-info

### About:
A single file PHP/jQuery webpage to see some info about your locally stored movies.
<br>See it in action [here](https://films.wasietsmet.nl).
### How to install?
1. Copy `index.htm` to your LAMP webhost.
2. Replace the `$api_key` variable (in `index.htm`) with your own themoviedb.org API key or (better yet) store it outside your webroot and include it. (For example with `trim(file_get_contents( ... ))`).
3. Change the `$watchedFolder` variable (in `index.htm`) to the folder where your movies are stored.

### Things to note:
- Use separate movie folders.
<br>Movie-info assumes your movies are stored in seperate subfolders and uses the foldernames as the argument for the search at `themoviedb.org`.
- Get an imdb account and API key.
<br>An account at themoviedb.org is necessary to use the API functions and obtain an api key.
<br>The api key needed to access themoviedb.org api is read from '../TMDBapikey.txt' which (obviously) is not included.
<br>You will have to provide your own.
<br>Getting a key is free (as in beer) and easy, just sign up at [themoviedb.org.](https://www.themoviedb.org/account/signup)

### Used open source libraries:
- The used icons are from [material.io](https://material.io/tools/icons/?style=baseline) and are [available under Apache2.0 license](https://www.apache.org/licenses/LICENSE-2.0.html).
- Uses [Google Roboto font](https://fonts.google.com/specimen/Roboto) which is [available under Apache2.0 license](https://www.apache.org/licenses/LICENSE-2.0.html).
- Uses [jQuery 3.3.1](https://code.jquery.com/jquery-3.3.1.js) which is [available under MIT license](https://jquery.org/license/).

Movie-info is developed on/for Linux.
### Screenshots:

Windowed:
<img src="https://cloud.githubusercontent.com/assets/24290108/24064644/f92ba2d4-0b65-11e7-949c-70aaccfd2aaf.png" />

fullscreen 1680x1050 scaled down:
<img src="https://cloud.githubusercontent.com/assets/24290108/24064651/fb5cebc6-0b65-11e7-8202-1fc60abdc388.png" width="800"/>
