<?php

/**
 * MyAnimeList New Anime API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Widget;

use MyAnimeList\Builder\AbstractWidget;

class NewAnime extends AbstractWidget {

	/**
	 * Set type
	 */
	public static $type = 'new';

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
	 * @usage 		tvnew
	 */
	protected function getTvnewFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="anime-header">\s*tv\s*\(new\)\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
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
	 * @usage 		tvcontinuing
	 */
	protected function getTvcontinuingFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="anime-header">\s*tv\s*\(continuing\)\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
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
	 * @usage 		ona
	 */
	protected function getOnaFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
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
		static::$limit,
		[ 'studios' => [ $this, 'custom_studios' ], 'genres' => [ $this, 'custom_genres' ] ]
		);
	}

	/**
	 * @return 		array
	 * @usage 		ova
	 */
	protected function getOvaFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
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
		static::$limit,
		[ 'studios' => [ $this, 'custom_studios' ], 'genres' => [ $this, 'custom_genres' ] ]
		);
	}

	/**
	 * @return 		array
	 * @usage 		movie
	 */
	protected function getMovieFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
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
		static::$limit,
		[ 'studios' => [ $this, 'custom_studios' ], 'genres' => [ $this, 'custom_genres' ] ]
		);
	}

	/**
	 * @return 		array
	 * @usage 		special
	 */
	protected function getSpecialFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="anime-header">\s*special\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)\s*</div>\s*</div>',
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