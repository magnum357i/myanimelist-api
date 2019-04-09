# myanimelist-api

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/62f62a2e43e140efb0fa1702e6f171c5)](https://app.codacy.com/app/magnum357i/myanimelist-api?utm_source=github.com&utm_medium=referral&utm_content=magnum357i/myanimelist-api&utm_campaign=Badge_Grade_Dashboard)
![](https://travis-ci.org/magnum357i/myanimelist-api.svg?branch=master)
![](https://img.shields.io/github/last-commit/magnum357i/myanimelist-api.svg)
![](https://img.shields.io/github/license/magnum357i/myanimelist-api.svg)

<<<<<<< HEAD
This is an api developed to get information from MyAnimelist. It works by scanning the html code of the page requested, so this library crashes when it changes.
=======

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/62f62a2e43e140efb0fa1702e6f171c5)](https://app.codacy.com/app/magnum357i/myanimelist-api?utm_source=github.com&utm_medium=referral&utm_content=magnum357i/myanimelist-api&utm_campaign=Badge_Grade_Dashboard)

This is an api developed to get information from pages of anime, manga, character and people on MyAnimelist. It works by scanning the html code of the page requested, so this library crashes when it changes.
>>>>>>> origin/master

# Required
* CURL
* PHP 7

# Supported Pages
* Page (anime, manga, character, people)
* Search (anime, manga, character, people)
* Widget (new anime, anime calendar, upcoming anime)

# How to Install?

```bash
$ composer require magnum357i/myanimelist-api
```

# How to Use?

### Anime Page

###### Example
```php
// Create object
$mal = new \myanimelist\Page\Anime( 20 );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->title()->original;
   echo $mal->title()->english;
   echo $mal->title()->japanese;
   echo $mal->title()->sysnonmys;
   echo $mal->poster;
   echo $mal->description;
   echo $mal->category;
   echo $mal->status;
   echo $mal->broadcast()->day;
   echo $mal->broadcast()->hour;
   echo $mal->broadcast()->minute;
   echo $mal->statistic()->rank;
   echo $mal->statistic()->member;
   echo $mal->statistic()->memberraw;
   echo $mal->statistic()->popularity;
   echo $mal->statistic()->favorite;
   echo $mal->statistic()->favoriteraw;
   echo $mal->rating;
   echo $mal->score()->vote;
   echo $mal->score()->voteraw;
   echo $mal->score()->point;
   echo $mal->genres;
   echo $mal->source;
   echo $mal->aired()->first()->month;
   echo $mal->aired()->first()->day;
   echo $mal->aired()->first()->year;
   echo $mal->aired()->last()->month;
   echo $mal->aired()->last()->day;
   echo $mal->aired()->last()->year;
   echo $mal->episode;
   echo $mal->studios;
   echo $mal->duration()->minute;
   echo $mal->duration()->hour;
   echo $mal->producers;
   echo $mal->licensors;
   echo $mal->premiered()->season;
   echo $mal->premiered()->year;
   echo $mal->year;
   echo $mal->voice;
   echo $mal->staff;
   echo $mal->related()->adaptation;
   echo $mal->related()->prequel;
   echo $mal->related()->sequel;
   echo $mal->related()->parentstory;
   echo $mal->related()->sidestory;
   echo $mal->related()->spinoff;
   echo $mal->related()->alternativeversion;
   echo $mal->related()->other;
   echo $mal->related()->sequel;
   echo $mal->link;
   echo $mal->trailer;
}
else {

   echo 'No data.';
}
```

### Manga Page

###### Example

```php
// Create object
$mal = new \myanimelist\Page\Manga( 2 );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->title()->original;
   echo $mal->title()->english;
   echo $mal->title()->japanese;
   echo $mal->title()->sysnonmys;
   echo $mal->poster;
   echo $mal->description;
   echo $mal->type;
   echo $mal->score()->vote;
   echo $mal->score()->voteraw;
   echo $mal->score()->point;
   echo $mal->genres;
   echo $mal->statistic()->rank;
   echo $mal->statistic()->popularity;
   echo $mal->statistic()->member;
   echo $mal->statistic()->memberraw;
   echo $mal->statistic()->favorite;
   echo $mal->statistic()->favoriteraw;
   echo $mal->status;
   echo $mal->published()->first()->month;
   echo $mal->published()->first()->day;
   echo $mal->published()->first()->year;
   echo $mal->published()->last()->month;
   echo $mal->published()->last()->day;
   echo $mal->published()->last()->year;
   echo $mal->authors;
   echo $mal->volume;
   echo $mal->chapter;
   echo $mal->serialization;
   echo $mal->year;
   echo $mal->characters;
   echo $mal->related()->adaptation;
   echo $mal->related()->sequel;
   echo $mal->related()->prequel;
   echo $mal->related()->parentstory;
   echo $mal->related()->sidestory;
   echo $mal->related()->other;
   echo $mal->related()->spinoff;
   echo $mal->related()->alternativeversion;
   echo $mal->link;
}
else {

   echo 'No data.';
}
```

### Character Page

###### Example

```php
// Create object
$mal = new \myanimelist\Page\Character( 40 );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->title()->self;
   echo $mal->title()->nickname;
   echo $mal->poster;
   echo $mal->description;
   echo $mal->statistic()->favorite;
   echo $mal->statistic()->favoriteraw;
   echo $mal->recent()->anime;
   echo $mal->recent()->manga;
   echo $mal->voiceactors;
}
else {

   echo 'No data.';
}
```

### People Page

###### Example

```php
// Create object
$mal = new \myanimelist\Page\People( 80 );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->name;
   echo $mal->poster;
   echo $mal->description;
   echo $mal->statistic()->favorite;
   echo $mal->statistic()->favoriteraw;
   echo $mal->recent()->voice;
   echo $mal->recent()->work;
   echo $mal->link;
}
else {

   echo 'No data.';
}
```

### Anime Search

###### Example

```php
// Create object
$mal = new \myanimelist\Search\Anime( 'bleach' );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->results;
   echo $mal->link;
}
else {

   echo 'No data.';
}
```

### Manga Search

###### Example

```php
// Create object
$mal = new \myanimelist\Search\Manga( 'baka to test to shoukanjuu' );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->results;
   echo $mal->link;
}
else {

   echo 'No data.';
}
```

### Character Search

###### Example

```php
// Create object
$mal = new \myanimelist\Search\Character( 'yugi' );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->results;
   echo $mal->link;
}
else {

   echo 'No data.';
}
```

### People Search

###### Example

```php
// Create object
$mal = new \myanimelist\Search\People( 'yui' );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->results;
   echo $mal->link;
}
else {

   echo 'No data.';
}
```

### New Anime Widget

###### Example

```php
// Create object
$mal = new \myanimelist\Widget\NewAnime;

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->tvnew;
   echo $mal->tvcontinuing;
   echo $mal->ona;
   echo $mal->ova;
   echo $mal->movie;
   echo $mal->special;
   echo $mal->link;
}
else {

   echo 'No data.';
}
```

# Configuration

### Reverse Names

If true, the name order is reversed firstname-lastname instead of lastname-firstname.

```php
// Create object
$mal = new \myanimelist\Page\Manga( 1 );

// Reverse name
$mal->config()->convertName();

// Send request
$mal->sendRequestOrGetData();

// Test
echo $mal->authors;

// Output
// reverse name option is true: Naoki Urasawa
// reverse name option is false: Urasawa, Naoki
```

### Enable Cache

If true, the cache system enabled.

```php
// Create object
$mal = new \myanimelist\Page\Anime( 1 );

// Enable cache
$mal->config()->enableCache();
$mal->cache()->setExpiredTime( 5 ); // In days.
$mal->cache()->setPath( ROOT_PATH . '/upload' );

// Send request
$mal->sendRequestOrGetData();

// Test
echo $mal->title()->english;
echo $mal->poster;

// Note
// Backs up the values you use.
// So, based on the above values, this will be create a file named 1.json
// and inside of the file writes the english title of the anime.
// Also a poster named 1.jpg is saved.
// After all, it will return false even if you call another value until the cache expires.
// Please don't forget this.
```

### cURL Settings

```php
// Create object
$mal = new myanimelist\Page\People( 1 );

// A sample setting
$mal->config()->setCurlOption( 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0', 'USERAGENT' );

// Send request
$mal->sendRequestOrGetData();
```

### Limitation

If you use a value of array type, you can limit it with the setLimit function.

```php
// Create object
$mal = new myanimelist\Page\Anime( 285 );

// Send request
$mal->sendRequestOrGetData();

$mal->setLimit( 3 ); // works for all values of array type

var_dump( $mal->voice );
```