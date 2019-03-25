<?php

/**
 * MyAnimeList Anime Calendar API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Widget;

class AnimeCalendar extends \myanimelist\Builder\Widget {

	/**
	 * Set type
	 */
	protected static $type = 'calendar';

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
	 * Get anime list of monday
	 *
	 * @return 		string
	 * @usage 		tvnew
	 */
	protected function _monday() {

		$key = 'monday';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<div class="anime-header">\s*monday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
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
	 * Get anime list of tuesday
	 *
	 * @return 		string
	 * @usage 		tvnew
	 */
	protected function _tuesday() {

		$key = 'tuesday';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<div class="anime-header">\s*tuesday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
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
	 * Get anime list of wednesday
	 *
	 * @return 		string
	 * @usage 		tvnew
	 */
	protected function _wednesday() {

		$key = 'wednesday';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<div class="anime-header">\s*wednesday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
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
	 * Get anime list of thursday
	 *
	 * @return 		string
	 * @usage 		tvnew
	 */
	protected function _thursday() {

		$key = 'thursday';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<div class="anime-header">\s*thursday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
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
	 * Get anime list of friday
	 *
	 * @return 		string
	 * @usage 		tvnew
	 */
	protected function _friday() {

		$key = 'friday';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<div class="anime-header">\s*friday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
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
	 * Get anime list of saturday
	 *
	 * @return 		string
	 * @usage 		tvnew
	 */
	protected function _saturday() {

		$key = 'saturday';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<div class="anime-header">\s*saturday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
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
	 * Get anime list of sunday
	 *
	 * @return 		string
	 * @usage 		tvnew
	 */
	protected function _sunday() {

		$key = 'sunday';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<div class="anime-header">\s*sunday\s*</div>(.+?<div class="information">.*?</div>[^<>]*</div>[^<>]*</div>)<div class="seasonal-anime-list js-seasonal-anime-list js-seasonal-anime-list-key-[^"]+">',
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
	 * Get link of the request page
	 *
	 * @return 		string
	 * @usage 		link
	 */
	protected function _link() {

		return $this->lastChanges( $this->request()::$url );
	}
}