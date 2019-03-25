<?php

/**
 * MyAnimeList People Page API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Page;

class People extends \myanimelist\Builder\Page {

	/**
	 * Set type
	 */
	protected static $type = 'people';

	/**
	 * Methods to allow for prefix
	 */
	protected static $methodsToAllow = [

		'statistic'
	];

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [

		'character' => 'character/{s}',
		'people'    => 'people/{s}',
		'anime'     => 'anime/{s}',
		'manga'     => 'manga/{s}'
	];

	/**
	 * Get name
	 *
	 * @return 		string
	 * @usage 		name
	 */
	protected function _name() {

		$key = 'name';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::match( '</div><h1.*?>(.*?)</h1></div><div id="content" ?>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->isOnNameConverting() ) $data = $this->text()->reverseName( $data );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get poster
	 *
	 * @return 		string
	 * @usage 		poster
	 */
	protected function _poster() {

		$key = 'poster';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchGroup( [

				'(https://myanimelist.cdn-dena.com/images/voiceactors/[0-9]+/[0-9]+\.jpg)',
				'(https://cdn.myanimelist.net/images/voiceactors/[0-9]+/[0-9]+\.jpg)'
			]
		);

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->isOnCache() ) {

			$newPoster = $this->cache()->savePoster( $this->imageName(), $data );
			$data      = $newPoster;
		}

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get description
	 *
	 * @return 		string
	 * @usage 		description
	 */
	protected function _description() {

		$key = 'description';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::match( '<span class="dark_text">more:</span></div><div[^>]+">(.*?)</div>.*?</td>', '<br>', 'si' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '[^\n]+:[^\n]+', '', $data, 'si' );
		$data = $this->text()->descCleaner( $data );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get category
	 *
	 * @return 		string
	 * @usage 		category
	 */
	protected function _category() {

		$key = 'category';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = 'people';

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get favorite
	 *
	 * @return 		array
	 * @usage 		favorite
	 */
	protected function favorite() {

		$key = 'favorite';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::match( '<span class="dark_text">member favorites:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '[^0-9]+', '', $data );
		$data = [

			'simple' => $this->lastChanges( $this->text()->formatK( $data ) ),
			'full'   => $this->lastChanges( $data )
		];

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get number with K of favorite
	 *
	 * @return 		string
	 * @usage 		statistic()->favorite
	 */
	protected function _statisticfavorite() {

		if ( !isset( static::$data[ 'favorite' ] ) ) $this->favorite();

		return ( isset( static::$data[ 'favorite' ][ 'simple' ] ) ) ? static::$data[ 'favorite' ][ 'simple' ] : FALSE;
	}

	/**
	 * Get number without K of favorite
	 *
	 * @return 		string
	 * @usage 		statistic()->favoriteraw
	 */
	protected function _statisticfavoriteraw() {

		if ( !isset( static::$data[ 'favorite' ] ) ) $this->favorite();

		return ( isset( static::$data[ 'favorite' ][ 'full' ] ) ) ? static::$data[ 'favorite' ][ 'full' ] : FALSE;
	}

	/**
	 * Get recent voice actiong roles
	 *
	 * @return 		string
	 * @usage 		recentvoice
	 */
	protected function _recentvoice() {

		$key = 'recentvoice';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'voice acting roles</div><table.*?>(.+?)</table>',
		'<tr>(.*?)</tr>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+">[^<]+</a>',
		'<a href="[^"]+anime/\d+[^"]+">([^<]+)</a>',
		'<a href="[^"]+character/(\d+)[^"]+">[^<]+</a>',
		'<a href="[^"]+character/\d+[^"]+">([^<]+)</a>'
		],
		[
		'anime_id',
		'anime_title',
		'character_id',
		'character_name'
		],
		static::$limit,
		NULL,
		TRUE,
		'anime_id'
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get recent works
	 *
	 * @return 		string
	 * @usage 		recentwork
	 */
	protected function _recentwork() {

		$key = 'recentwork';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'anime staff positions</div><table.*?>(.+?)</table>',
		'<tr>(.*?)</tr>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+">[^<]+</a>',
		'<a href="[^"]+anime/\d+[^"]+">([^<]+)</a>',
		'<small>([^<]+)</small>'
		],
		[
		'id',
		'title',
		'work'
		],
		static::$limit,
		NULL,
		TRUE,
		'id'
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