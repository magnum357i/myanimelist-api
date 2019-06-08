# myanimelist-api

![Packagist Version](https://img.shields.io/packagist/v/magnum357i/myanimelist-api.svg)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/62f62a2e43e140efb0fa1702e6f171c5)](https://app.codacy.com/app/magnum357i/myanimelist-api?utm_source=github.com&utm_medium=referral&utm_content=magnum357i/myanimelist-api&utm_campaign=Badge_Grade_Dashboard)
![](https://travis-ci.org/magnum357i/myanimelist-api.svg?branch=master)
![](https://img.shields.io/github/last-commit/magnum357i/myanimelist-api.svg)
![](https://img.shields.io/github/license/magnum357i/myanimelist-api.svg)

This is an api developed to get information from MyAnimelist. It works by scanning the html code of the page requested, so this library crashes when it changes.

# Required
* CURL
* PHP 7

# Supported Pages
- Page
	- [**anime**](#anime-page)
	- [**manga**](#manga-page)
	- [**character**](#character-page)
	- [**people**](#people-page)
- Search
	- [**anime**](#anime-search)
	- [**manga**](#manga-search)
	- [**character**](#character-search)
	- [**people**](#people-search)
- Widget
	- [**new anime**](#new-anime-widget)
	- [**upcoming anime**](#upcoming-anime-widget)
	- [**anime calendar**](#anime-calendar-widget)

# How to Install?

### Via composer

```bash
$ composer require magnum357i/myanimelist-api
```

### Via git

```bash
$ git clone --depth=50 --branch=master https://github.com/magnum357i/myanimelist-api.git magnum357i/myanimelist-api
```

# How to Use?

### Anime Page

###### Example
```php
// Create object
$mal = new \MyAnimeList\Page\Anime( 20 );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->titleOriginal;
   echo $mal->titleEnglish;
   echo $mal->titleJapanese;
   echo $mal->titleOthers;
   echo $mal->poster;
   echo $mal->description;
   echo $mal->category;
   echo $mal->status;
   echo $mal->broadcast;
   echo $mal->statisticRank;
   echo $mal->statisticMember;
   echo $mal->statisticMemberraw;
   echo $mal->statisticPopularity;
   echo $mal->statisticFavorite;
   echo $mal->statisticFavoriteraw;
   echo $mal->rating;
   echo $mal->scoreVote;
   echo $mal->scoreVoteraw;
   echo $mal->scorePoint;
   echo $mal->genres;
   echo $mal->source;
   echo $mal->airedFirst;
   echo $mal->airedLast;
   echo $mal->episode;
   echo $mal->studios;
   echo $mal->duration;
   echo $mal->producers;
   echo $mal->licensors;
   echo $mal->premiered;
   echo $mal->year;
   echo $mal->voice;
   echo $mal->staff;
   echo $mal->songOpening;
   echo $mal->songEnding;
   echo $mal->relatedAdaptation;
   echo $mal->relatedPrequel;
   echo $mal->relatedSequel;
   echo $mal->relatedParentstory;
   echo $mal->relatedSidestory;
   echo $mal->relatedSpinoff;
   echo $mal->relatedAlternativeversion;
   echo $mal->relatedOther;
   echo $mal->relatedSummary;
   echo $mal->relatedAlternativesetting;
   echo $mal->trailer;
   echo $mal->tabBase;
   echo $mal->tabItems;
   echo $mal->link();
}
else {

   echo 'No data.';
}
```

### Manga Page

###### Example

```php
// Create object
$mal = new \MyAnimeList\Page\Manga( 2 );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->titleOriginal;
   echo $mal->titleEnglish;
   echo $mal->titleJapanese;
   echo $mal->titleOthers;
   echo $mal->poster;
   echo $mal->description;
   echo $mal->type;
   echo $mal->scoreVote;
   echo $mal->scoreVoteraw;
   echo $mal->scorePoint;
   echo $mal->genres;
   echo $mal->statisticRank;
   echo $mal->statisticPopularity;
   echo $mal->statisticMember;
   echo $mal->statisticMemberraw;
   echo $mal->statisticFavorite;
   echo $mal->statisticFavoriteraw;
   echo $mal->status;
   echo $mal->publishedFirst;
   echo $mal->publishedLast;
   echo $mal->authors;
   echo $mal->volume;
   echo $mal->chapter;
   echo $mal->serialization;
   echo $mal->year;
   echo $mal->characters;
   echo $mal->relatedAdaptation;
   echo $mal->relatedSequel;
   echo $mal->relatedPrequel;
   echo $mal->relatedParentstory;
   echo $mal->relatedSidestory;
   echo $mal->relatedOther;
   echo $mal->relatedSpinoff;
   echo $mal->relatedAlternativeversion;
   echo $mal->relatedSummary;
   echo $mal->relatedAlternativesetting;
   echo $mal->tabBase;
   echo $mal->tabItems;
   echo $mal->link();
}
else {

   echo 'No data.';
}
```

### Character Page

###### Example

```php
// Create object
$mal = new \MyAnimeList\Page\Character( 40 );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->titleSelf;
   echo $mal->titleNickname;
   echo $mal->poster;
   echo $mal->description;
   echo $mal->statisticFavorite;
   echo $mal->statisticFavoriteraw;
   echo $mal->recentAnime;
   echo $mal->recentManga;
   echo $mal->voiceactors;
   echo $mal->age;
   echo $mal->height;
   echo $mal->weight;
   echo $mal->tabBase;
   echo $mal->tabItems;
   echo $mal->link();
}
else {

   echo 'No data.';
}
```

### People Page

###### Example

```php
// Create object
$mal = new \MyAnimeList\Page\People( 80 );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->name;
   echo $mal->poster;
   echo $mal->description;
   echo $mal->statisticFavorite;
   echo $mal->statisticFavoriteraw;
   echo $mal->recentVoice;
   echo $mal->recentWork;
   echo $mal->birth;
   echo $mal->death;
   echo $mal->height;
   echo $mal->weight;
   echo $mal->age;
   echo $mal->socialFacebook;
   echo $mal->socialTwitter;
   echo $mal->socialWebsite;
   echo $mal->tabBase;
   echo $mal->tabItems;
   echo $mal->link();
}
else {

   echo 'No data.';
}
```

### Anime Search

###### Example

```php
// Create object
$mal = new \MyAnimeList\Search\Anime( 'bleach' );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->results;
   echo $mal->link();
}
else {

   echo 'No data.';
}
```

### Manga Search

###### Example

```php
// Create object
$mal = new \MyAnimeList\Search\Manga( 'baka to test to shoukanjuu' );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->results;
   echo $mal->link();
}
else {

   echo 'No data.';
}
```

### Character Search

###### Example

```php
// Create object
$mal = new \MyAnimeList\Search\Character( 'yugi' );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->results;
   echo $mal->link();
}
else {

   echo 'No data.';
}
```

### People Search

###### Example

```php
// Create object
$mal = new \MyAnimeList\Search\People( 'yui' );

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->results;
   echo $mal->link();
}
else {

   echo 'No data.';
}
```

### New Anime Widget

###### Example

```php
// Create object
$mal = new \MyAnimeList\Widget\NewAnime;

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
   echo $mal->link();
}
else {

   echo 'No data.';
}
```

### Upcoming Anime Widget

###### Example

```php
// Create object
$mal = new \MyAnimeList\Widget\UpcomingAnime;

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->tv;
   echo $mal->ona;
   echo $mal->ova;
   echo $mal->movie;
   echo $mal->special;
   echo $mal->unknown;
   echo $mal->link();
}
else {

   echo 'No data.';
}
```

### Anime Calendar Widget

###### Example

```php
// Create object
$mal = new \MyAnimeList\Widget\AnimeCalendar;

// Send request
$mal->sendRequestOrGetData();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->monday;
   echo $mal->tuesday;
   echo $mal->wednesday;
   echo $mal->thursday;
   echo $mal->friday;
   echo $mal->saturday;
   echo $mal->sunday;
   echo $mal->link();
}
else {

   echo 'No data.';
}
```

# Configuration

### Override Default Cache Class

If needed, you can use your own cache class. Type your cache object to the constructor your cache class which has Cache Interface (MyAnimeList/Cache/CacheInterface).

```php

// Page

$folders = \MyAnimeList\Builder\AbstractPage::$folders
$type    = 'anime'
$mal     = new \MyAnimeList\Page\Anime( 20, new \MyCustomCache( $type, $folders ) );

// Search

$folders = \MyAnimeList\Builder\AbstractSearch::$folders
$type    = 'anime'
$mal     = new \MyAnimeList\Search\Anime( 'naruto', new \MyCustomCache( $type, $folders ) );

// Widget

$folders = \MyAnimeList\Builder\AbstractWidget::$folders
$type    = 'animecalendar'
$mal     = new \MyAnimeList\Widget\AnimeCalendar( new \MyCustomCache( $type, $folders ) );
```

### Reverse Names

```php
// Create object
$mal = new \MyAnimeList\Page\Manga( 1 );

// Reverse
$mal->config()->reversename = TRUE;

// Send request
$mal->sendRequestOrGetData();

// Test
echo $mal->authors;

// Output
// reverse name option is true: Naoki Urasawa
// reverse name option is false: Urasawa, Naoki
```

### Bigger Images

```php
// Create object
$mal = new \MyAnimeList\Page\Manga( 1 );

// Use bigger images
$mal->config()->bigimages = TRUE;

// Send request
$mal->sendRequestOrGetData();

// Test
var_dump( $mal->staff );
```

### Enable Cache

```php
// Create object
$mal = new \MyAnimeList\Page\Anime( 1 );

// Enable cache
$mal->config()->enablecache  = TRUE;
$mal->config()->expiredbyday = 5;
$mal->cache()->setPath( ROOT_PATH . '/upload' );

// Send request
$mal->sendRequestOrGetData();

// Test
echo $mal->titleEnglish;
echo $mal->poster;

// Note
// Backs up the values you use.
// So, based on the above values, this will be create a file named 1.json
// and inside of the file writes the english title of the anime.
// Also a poster named 1.jpg is saved.
// After all, it will return false even if you call another value until the cache expires.
// Please don't forget this.
```

### Capture All Data at Once

```php
// Create object
$mal = new \MyAnimeList\Page\Manga( 20 );

// Send request
$mal->sendRequestOrGetData();

// Get all data
$mal->scanAvailableValues();

// Print data
var_dump( $mal->output() );
```

### cURL Settings

```php
// Create object
$mal = new \MyAnimeList\Page\People( 1 );

// A sample setting
$mal->config()->setCurlOption( 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0', 'USERAGENT' );

// Send request
$mal->sendRequestOrGetData();
```

### Limitation

```php
// Create object
$mal = new \MyAnimeList\Page\Anime( 285 );

// Send request
$mal->sendRequestOrGetData();

$mal->setLimit( 3 ); // works for all indexed arrays

var_dump( $mal->voice );
```

### Timezone for Broadcast

```php
// Create object
$mal = new \MyAnimeList\Page\Anime( 34134 );

// Send request
$mal->sendRequestOrGetData();

// Print untoched broadcast
var_dump( $mal->broadcast ); // [ "timezone" => "Asia/Tokyo", "dayIndex" => "3", "dayTitle" => "Wednesdays", "hour" => "01", "minute" => "35"

// Print broadcast with default timezone
// my default timezone: 'Europe/Berlin'
$mal->timezone();
var_dump( $mal->broadcast ); // [ "timezone" => "Europe/Berlin", "dayIndex" => "2", "dayTitle" => "Tuesdays", "hour" => "18", "minute" => "35"

// Print broadcast with custom timezone
$mal->timezone( 'America/Los_Angeles' );
var_dump( $mal->broadcast ); // [ "timezone" => "America/Los_Angeles", "dayIndex" => "2", "dayTitle" => "Tuesdays", "hour" => "09", "minute" => "35"
```