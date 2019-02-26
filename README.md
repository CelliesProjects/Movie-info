# Movie-info

### About:
A single file PHP/jQuery webpage to see some info about your locally stored movies.
### How to use?
1. Copy `index.htm` to your LAMP webhost.
2. Change the `$api_key` variable (in `index.htm`) to your themoviedb.org API key or (better yet) store it outside your webroot and include it. (For example with `trim(file_get_contents( ... ))`).
3. Change the `$watchedFolder` variable (in `index.htm`) to the folder where your movies are stored.
### Things to note.
#### Use separate movie folders.
Movie-info assumes your movies are stored in seperate subfolders and uses the foldernames as the argument for the search at `themoviedb.org`.
#### Get an imdb account and API key.
An account at themoviedb.org is necessary to use the API functions and obtain an api key.

The api key needed to access themoviedb.org api is read from '../TMDBapikey.txt' which (obviously) is not included.
You will have to provide your own.
<br>Getting a key is free (as in beer) and easy, just sign up at [themoviedb.org.](https://www.themoviedb.org/account/signup)

Developed on/for Linux. Needs some work to run on Windows.
#### creenshots:
<img src="https://cloud.githubusercontent.com/assets/24290108/24064644/f92ba2d4-0b65-11e7-949c-70aaccfd2aaf.png" />
windowed

<img src="https://cloud.githubusercontent.com/assets/24290108/24064651/fb5cebc6-0b65-11e7-8202-1fc60abdc388.png" width="800"/>
fullscreen 1680x1050 scaled down
