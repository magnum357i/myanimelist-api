# myanimelist-api
This is an api developed to get information from anime, manga, character and people pages of MyAnimelist.

### Required
* Curl
* PHP 5.4 or higher

### Usage (Anime)

```php
$mal = new myanimelist\Types\Anime( 20 );

$mal->_titleoriginal();
$mal->_titleenglish();
$mal->_titlejapanese();
$mal->_titlesysnonmys();
$mal->_poster();
$mal->_description();
$mal->_type();
$mal->_status();
$mal->_broadcast();
$mal->_members();
$mal->_popularity();
$mal->_favorites();
$mal->_rating();
$mal->_rank();
$mal->_vote();
$mal->_point();
$mal->_genres();
$mal->_source();
$mal->_firstepisode();
$mal->_lastepisode();
$mal->_episode();
$mal->_studios();
$mal->_duration();
$mal->_premiered();
$mal->_year();
$mal->_voice( 3 );
$mal->_staff( 3 );
$mal->_related( 'adaptation',         10 );
$mal->_related( 'prequel',            10 );
$mal->_related( 'sequel',             10 );
$mal->_related( 'parentstory',        10 );
$mal->_related( 'sidestory',          10 );
$mal->_related( 'spinoff',            10 );
$mal->_related( 'alternativeversion', 10 );
$mal->_related( 'other',              10 );
$mal->_related( 'sequel',             10 );
$mal->_link();
$mal->_trailer();

$data = $mal->output();

if ( count( $data ) <= 3 )
{
	die( 'There is a problem retrieving data.' );
}

var_dump( $data );
```

### Usage (Manga)

```php
$mal = new myanimelist\Types\Manga( 2 );

$mal->_titleoriginal();
$mal->_titleenglish();
$mal->_titlejapanese();
$mal->_poster();
$mal->_description();
$mal->_type();
$mal->_rank();
$mal->_vote();
$mal->_point();
$mal->_genres();
$mal->_popularity();
$mal->_members();
$mal->_favorites();
$mal->_status();
$mal->_published();
$mal->_authors();
$mal->_volume();
$mal->_chapter();
$mal->_serialization();
$mal->_firstchapter();
$mal->_lastchapter();
$mal->_year();
$mal->_characters( 7 );
$mal->_related( 'adaptation',         5 );
$mal->_related( 'sequel',             5 );
$mal->_related( 'prequel',            5 );
$mal->_related( 'parentstory',        5 );
$mal->_related( 'sidestory',          5 );
$mal->_related( 'other',              5 );
$mal->_related( 'spinoff',            5 );
$mal->_related( 'alternativeversion', 5 );
$mal->_link();

$data = $mal->output();

if ( count( $data ) <= 3 )
{
	die( 'There is a problem retrieving data.' );
}

var_dump( $data );
```

### Usage (Character)

```php
$mal = new myanimelist\Types\Character( 40 );

$mal->_charactername();
$mal->_nickname();
$mal->_poster();
$mal->_description();
$mal->_favorites();
$mal->_recentanime( 5 );
$mal->_recentmanga( 5 );
$mal->_voiceactors( 1 );

$data = $mal->output();

if ( count( $data ) <= 3 )
{
	die( 'There is a problem retrieving data.' );
}

var_dump( $data );
```

### Usage (People)

```php
$mal = new myanimelist\Types\Anime( 80 );

$mal->_name();
$mal->_poster();
$mal->_description();
$mal->_favorites();
$mal->_recentvoice( 5 );
$mal->_recentwork( 5 );
$mal->_link();

$data = $mal->output();

if ( count( $data ) <= 3 )
{
	die( 'There is a problem retrieving data.' );
}

var_dump( $data );
```
