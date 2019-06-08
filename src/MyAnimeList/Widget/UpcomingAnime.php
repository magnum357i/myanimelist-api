<?php

/**
 * MyAnimeList Upcoming Anime API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Widget;

use MyAnimeList\Builder\AbstractWidget;

class UpcomingAnime extends AbstractWidget {

	/**
	 * @var 		array 			Key list for all purposes
	 */
	public $keyList = [ 'tv', 'ona', 'ova', 'movie', 'special', 'unknown' ];

	/**
	 * @return 		array
	 * @usage 		tv
	 */
	protected function getTvFromData() {

		return
		$this->request()::matchTable(
		$this->config(),
		'<div class="anime-header">\s*tv\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"', '<p class="title-text">(.*?)</p>', '<img.*?src="([^"]+images/anime[^"]+)".*?>',
		'<span class="producer">(.*?)</span>', '<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id', 'title', 'poster',
		'studios', 'genres'
		],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		ona
	 */
	protected function getOnaFromData() {

		return
		$this->request()::matchTable(
		$this->config(),
		'<div class="anime-header">\s*ona\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"', '<p class="title-text">(.*?)</p>', '<img.*?src="([^"]+images/anime[^"]+)".*?>',
		'<span class="producer">(.*?)</span>', '<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id', 'title', 'poster',
		'studios', 'genres'
		],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		ova
	 */
	protected function getOvaFromData() {

		return
		$this->request()::matchTable(
		$this->config(),
		'<div class="anime-header">\s*ova\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"', '<p class="title-text">(.*?)</p>', '<img.*?src="([^"]+images/anime[^"]+)".*?>',
		'<span class="producer">(.*?)</span>', '<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id', 'title', 'poster',
		'studios', 'genres'
		],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		movie
	 */
	protected function getMovieFromData() {

		return
		$this->request()::matchTable(
		$this->config(),
		'<div class="anime-header">\s*movie\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"', '<p class="title-text">(.*?)</p>', '<img.*?src="([^"]+images/anime[^"]+)".*?>',
		'<span class="producer">(.*?)</span>', '<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id', 'title', 'poster',
		'studios', 'genres'
		],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		special
	 */
	protected function getSpecialFromData() {

		return
		$this->request()::matchTable(
		$this->config(),
		'<div class="anime-header">\s*special\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"', '<p class="title-text">(.*?)</p>', '<img.*?src="([^"]+images/anime[^"]+)".*?>',
		'<span class="producer">(.*?)</span>', '<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id', 'title', 'poster',
		'studios', 'genres'
		],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		unknown
	 */
	protected function getUnknownFromData() {

		return
		$this->request()::matchTable(
		$this->config(),
		'<div class="anime-header">\s*unknown\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)\s*</div>\s*</div>',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"', '<p class="title-text">(.*?)</p>', '<img.*?src="([^"]+images/anime[^"]+)".*?>',
		'<span class="producer">(.*?)</span>', '<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id', 'title', 'poster',
		'studios', 'genres'
		],
		static::$limit
		);
	}
}