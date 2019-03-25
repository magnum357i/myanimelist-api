<?php

/**
 * MyAnimeList Upcoming Anime API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Widget;

class UpcomingAnime extends \myanimelist\Builder\Widget {

	/**
	 * Set type
	 */
	protected static $type = 'upcoming';

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [

		'genre'     => 'anime/genre/{s}',
		'producer'  => 'anime/producer/{s}',
		'anime'     => 'anime/{s}'
	];

	/**
	 * Edits poster
	 *
	 * @return 		array
	 */
	public function custom_poster( $value ) {

		if ( $this->text()->validate( [ 'mode' => 'regex', 'regex_code' => 'anime\/\d+' ], $value ) ) {

			return $this->request()::reflection( [ $this, 'lastChanges' ], $this->config(), $this->text(), $value, 'poster' );
		}

		return NULL;
	}

	/**
	 * Edits studios
	 *
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

				'id'    => $this->request()::reflection( [ $this, 'lastChanges' ], $this->config(), $this->text(), $result[ 1 ][ $i ], 'id' ),
				'title' => $this->request()::reflection( [ $this, 'lastChanges' ], $this->config(), $this->text(), $result[ 2 ][ $i ], 'title' )
			];
		}

		return $rows;
	}

	/**
	 * Edits genres
	 *
	 * @return 		array
	 */
	public function custom_genres( $value ) {

		preg_match_all( '@<a href="[^"]+genre/(\d+)[^"]+"[^>]+>(.*?)</a>@', $value, $result );

		$count = count( $result[ 1 ] );

		if ( $count == 0 ) return NULL;

		$rows = [];

		for ( $i = 0; $i < $count; $i++ ) {

			$rows[] = [

				'id'    => $this->request()::reflection( [ $this, 'lastChanges' ], $this->config(), $this->text(), $result[ 1 ][ $i ], 'id' ),
				'title' => $this->request()::reflection( [ $this, 'lastChanges' ], $this->config(), $this->text(), $result[ 2 ][ $i ], 'title' )
			];
		}

		return $rows;
	}

	/**
	 * Get anime list of tv
	 *
	 * @return 		string
	 * @usage 		tv
	 */
	protected function _tv() {

		$key = 'tv';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<div class="anime-header">\s*tv\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"',
		'<p class="title-text">(.*?)</p>',
		'<img.*?src="(.*?)".*?>',
		'<span class="producer">(.*?)</span>',
		'<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id',
		'title',
		'poster',
		'studios',
		'genres'
		],
		static::$limit,
		[
		'poster'  => [ $this, 'custom_poster' ],
		'studios' => [ $this, 'custom_studios' ],
		'genres'  => [ $this, 'custom_genres' ],
		]
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get anime list of ona
	 *
	 * @return 		string
	 * @usage 		ona
	 */
	protected function _ona() {

		$key = 'ona';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<div class="anime-header">\s*ona\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"',
		'<p class="title-text">(.*?)</p>',
		'<img.*?src="(.*?)".*?>',
		'<span class="producer">(.*?)</span>',
		'<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id',
		'title',
		'poster',
		'studios',
		'genres'
		],
		static::$limit,
		[
		'poster'  => [ $this, 'custom_poster' ],
		'studios' => [ $this, 'custom_studios' ],
		'genres'  => [ $this, 'custom_genres' ]
		]
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get anime list of ova
	 *
	 * @return 		string
	 * @usage 		ova
	 */
	protected function _ova() {

		$key = 'ova';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<div class="anime-header">\s*ova\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"',
		'<p class="title-text">(.*?)</p>',
		'<img.*?src="(.*?)".*?>',
		'<span class="producer">(.*?)</span>',
		'<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id',
		'title',
		'poster',
		'studios',
		'genres'
		],
		static::$limit,
		[
		'poster'  => [ $this, 'custom_poster' ],
		'studios' => [ $this, 'custom_studios' ],
		'genres'  => [ $this, 'custom_genres' ]
		]
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get anime list of movie
	 *
	 * @return 		string
	 * @usage 		movie
	 */
	protected function _movie() {

		$key = 'movie';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<div class="anime-header">\s*movie\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"',
		'<p class="title-text">(.*?)</p>',
		'<img.*?src="(.*?)".*?>',
		'<span class="producer">(.*?)</span>',
		'<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id',
		'title',
		'poster',
		'studios',
		'genres'
		],
		static::$limit,
		[
		'poster'  => [ $this, 'custom_poster' ],
		'studios' => [ $this, 'custom_studios' ],
		'genres'  => [ $this, 'custom_genres' ]
		]
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get anime list of special
	 *
	 * @return 		string
	 * @usage 		special
	 */
	protected function _special() {

		$key = 'special';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<div class="anime-header">\s*special\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"',
		'<p class="title-text">(.*?)</p>',
		'<img.*?src="(.*?)".*?>',
		'<span class="producer">(.*?)</span>',
		'<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id',
		'title',
		'poster',
		'studios',
		'genres'
		],
		static::$limit,
		[
		'poster'  => [ $this, 'custom_poster' ],
		'studios' => [ $this, 'custom_studios' ],
		'genres'  => [ $this, 'custom_genres' ]
		]
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get anime list of unknown
	 *
	 * @return 		string
	 * @usage 		unknown
	 */
	protected function _unknown() {

		$key = 'special';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<div class="anime-header">\s*unknown\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)\s*</div>\s*</div>',
		'<div [^>]+data-genre[^>]+>(.*?<div class="information">.*?</div>)[^<>]*</div>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+"',
		'<p class="title-text">(.*?)</p>',
		'<img.*?src="(.*?)".*?>',
		'<span class="producer">(.*?)</span>',
		'<div class="genres-inner js-genre-inner">(.*?)</div>'
		],
		[
		'id',
		'title',
		'poster',
		'studios',
		'genres'
		],
		static::$limit,
		[
		'poster'  => [ $this, 'custom_poster' ],
		'studios' => [ $this, 'custom_studios' ],
		'genres'  => [ $this, 'custom_genres' ]
		]
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get link of the request page
	 *
	 * @return 		string
	 * @usage 		link
	 */
	protected function _link() {

		return $this->lastChanges( $this->request()::$url );
	}
}