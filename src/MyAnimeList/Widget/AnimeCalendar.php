<?php

/**
 * MyAnimeList Anime Calendar API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Widget;

use MyAnimeList\Builder\AbstractWidget;

class AnimeCalendar extends AbstractWidget {

	/**
	 * Set type
	 */
	public static $type = 'calendar';

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [ 'genre' => 'anime/genre/{s}', 'producer' => 'anime/producer/{s}', 'anime' => 'anime/{s}' ];

	/**
	 * @return 		array
	 */
	public function custom_studios( $value ) {

		if ( $value == '-' ) return NULL;

		preg_match_all( '@<a href="[^"]+producer/(\d+)[^"]+"[^>]+>(.*?)</a>@', $value, $result );

		$count = count( $result[ 1 ] );

		if ( $count == 0 ) return NULL;

		$rows = [];

		for ( $i = 0; $i < $count; $i++ ) {

			$rows[] = [

				'id'    => $this->request()::reflection( $this->config(), $this->text(), $result[ 1 ][ $i ], 'id' ),
				'title' => $this->request()::reflection( $this->config(), $this->text(), $result[ 2 ][ $i ], 'title' )
			];
		}

		return $rows;
	}

	/**
	 * @return 		array
	 */
	public function custom_genres( $value ) {

		preg_match_all( '@<a href="[^"]+genre/(\d+)[^"]+"[^>]+>(.*?)</a>@', $value, $result );

		$count = count( $result[ 1 ] );

		if ( $count == 0 ) return NULL;

		$rows = [];

		for ( $i = 0; $i < $count; $i++ ) {

			$rows[] = [

				'id'    => $this->request()::reflection( $this->config(), $this->text(), $result[ 1 ][ $i ], 'id' ),
				'title' => $this->request()::reflection( $this->config(), $this->text(), $result[ 2 ][ $i ], 'title' )
			];
		}

		return $rows;
	}

	/**
	 * @return 		array
	 * @usage 		monday
	 */
	protected function getMondayFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="anime-header">\s*monday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"', '<p class="title-text">(.*?)</p>', '<img.*?src="([^"]+images/anime[^"]+)".*?>',
		'<span class="producer">(.*?)</span>', '<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id', 'title', 'poster',
		'studios', 'genres'
		],
		static::$limit,
		[ 'studios' => [ $this, 'custom_studios' ], 'genres' => [ $this, 'custom_genres' ] ]
		);
	}

	/**
	 * @return 		array
	 * @usage 		tuesday
	 */
	protected function getTuesdayFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="anime-header">\s*tuesday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"', '<p class="title-text">(.*?)</p>', '<img.*?src="([^"]+images/anime[^"]+)".*?>',
		'<span class="producer">(.*?)</span>', '<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id', 'title', 'poster',
		'studios', 'genres'
		],
		static::$limit,
		[ 'studios' => [ $this, 'custom_studios' ], 'genres' => [ $this, 'custom_genres' ] ]
		);
	}

	/**
	 * @return 		array
	 * @usage 		wednesday
	 */
	protected function getWednesdayFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="anime-header">\s*wednesday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"', '<p class="title-text">(.*?)</p>', '<img.*?src="([^"]+images/anime[^"]+)".*?>',
		'<span class="producer">(.*?)</span>', '<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id', 'title', 'poster',
		'studios', 'genres'
		],
		static::$limit,
		[ 'studios' => [ $this, 'custom_studios' ], 'genres' => [ $this, 'custom_genres' ] ]
		);
	}

	/**
	 * @return 		array
	 * @usage 		thursday
	 */
	protected function getThursdayFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="anime-header">\s*thursday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"', '<p class="title-text">(.*?)</p>', '<img.*?src="([^"]+images/anime[^"]+)".*?>',
		'<span class="producer">(.*?)</span>', '<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id', 'title', 'poster',
		'studios', 'genres'
		],
		static::$limit,
		[ 'studios' => [ $this, 'custom_studios' ], 'genres' => [ $this, 'custom_genres' ] ]
		);
	}

	/**
	 * @return 		array
	 * @usage 		friday
	 */
	protected function getFridayFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="anime-header">\s*friday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"', '<p class="title-text">(.*?)</p>', '<img.*?src="([^"]+images/anime[^"]+)".*?>',
		'<span class="producer">(.*?)</span>', '<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id', 'title', 'poster',
		'studios', 'genres'
		],
		static::$limit,
		[ 'studios' => [ $this, 'custom_studios' ], 'genres' => [ $this, 'custom_genres' ] ]
		);
	}

	/**
	 * @return 		array
	 * @usage 		saturday
	 */
	protected function getSaturdayFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="anime-header">\s*saturday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"', '<p class="title-text">(.*?)</p>', '<img.*?src="([^"]+images/anime[^"]+)".*?>',
		'<span class="producer">(.*?)</span>', '<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id', 'title', 'poster',
		'studios', 'genres'
		],
		static::$limit,
		[ 'studios' => [ $this, 'custom_studios' ], 'genres' => [ $this, 'custom_genres' ] ]
		);
	}

	/**
	 * @return 		array
	 * @usage 		sunday
	 */
	protected function getSundayFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="anime-header">\s*sunday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"', '<p class="title-text">(.*?)</p>', '<img.*?src="([^"]+images/anime[^"]+)".*?>',
		'<span class="producer">(.*?)</span>', '<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id', 'title', 'poster',
		'studios', 'genres'
		],
		static::$limit,
		[ 'studios' => [ $this, 'custom_studios' ], 'genres' => [ $this, 'custom_genres' ] ]
		);
	}
}