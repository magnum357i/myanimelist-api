<?php

/**
 * MyAnimeList People API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Types;

class People extends \myanimelist\Helper\Builder {

	/**
	 * Set type
	 */
	public static $type = 'people';

	/**
	 * Prefix to call function
	 */
	public static $prefix = '';

	/**
	 * Alloed functions for prefix
	 */
	public static $allowed_methods = array(
		'statistic'
	);

	/**
	 * Set limit
	 *
	 * @param 		int 			Limit number
	 * @return 		this class
	 */
	public function setLimit( $int ) {

		static::$limit = $int;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return 		string
	 */
	protected function _name() {

		$key = 'name';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '</div><h1.*?>(.*?)</h1></div><div id="content" ?>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->reverseName == TRUE ) $data = $this->text()->reverseName( $data );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get poster
	 *
	 * @return 		string
	 */
	protected function _poster() {

		$key = 'poster';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '(https://myanimelist.cdn-dena.com/images/voiceactors/[0-9]+/[0-9]+\.jpg)' );

		if ( $data == FALSE ) $data = $this->request()->match( '(https://cdn.myanimelist.net/images/voiceactors/[0-9]+/[0-9]+\.jpg)' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->cache == TRUE ) {

			$newPoster = $this->cache()->savePoster( $data );
			$data      = $newPoster;
		}

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get description
	 *
	 * @return 		string
	 */
	protected function _description() {

		$key = 'description';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">more:</span></div><div[^>]+">(.*?)</div>.*?</td>', '<br>', 'si' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '[^\n]+:[^\n]+', '', $data, 'si' );
		$data = $this->text()->descCleaner( $data );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get category
	 *
	 * @return 		string
	 */
	protected function _category() {

		$key = 'category';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = 'people';

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get favorite
	 *
	 * @return 		array
	 */
	protected function _favorite() {

		$key = 'favorite';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">member favorites:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '[^0-9]+', '', $data );
		$data = array(
			'simple' => $this->lastChanges( $this->text()->formatK( $data ) ),
			'full'   => $this->lastChanges( $data )
		);

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get number with K of favorite
	 *
	 * @return 		string
	 */
	protected function _statisticfavorite() {

		if ( !isset( static::$data[ 'favorite' ] ) ) $this->_favorite();

		return ( isset( static::$data[ 'favorite' ][ 'simple' ] ) ) ? static::$data[ 'favorite' ][ 'simple' ] : FALSE;
	}

	/**
	 * Get number without K of favorite
	 *
	 * @return 		string
	 */
	protected function _statisticfavoriteraw() {

		if ( !isset( static::$data[ 'favorite' ] ) ) $this->_favorite();

		return ( isset( static::$data[ 'favorite' ][ 'full' ] ) ) ? static::$data[ 'favorite' ][ 'full' ] : FALSE;
	}

	/**
	 * Get recent voice actiong roles
	 *
	 * @return 		string
	 */
	protected function _recentvoice() {

		$key = 'recentvoice';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
		'voice acting roles</div><table.*?>(.+?)</table>',
		'<tr>(.*?)</tr>',
        array(
		'<a href="[^"]+/(anime/[0-9]+)/[^"]+">[^<]+</a>',
		'<a href="[^"]+/anime/[0-9]+/[^"]+">([^<]+)</a>',
		'<a href="[^"]+/(character/[0-9]+)/[^"]+">[^<]+</a>',
		'<a href="[^"]+/character/[0-9]+/[^"]+">([^<]+)</a>'
        ),
        array(
		'anime_link',
		'anime_title',
		'character_link',
		'character_name'
        ),
        static::$limit,
        TRUE,
        '<a href="[^"]+/anime/([0-9]+)/[^"]+">[^<]+</a>'
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get recent works
	 *
	 * @return 		string
	 */
	protected function _recentwork() {

		$key = 'recentwork';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
		'anime staff positions</div><table.*?>(.+?)</table>',
		'<tr>(.*?)</tr>',
        array(
		'<a href="[^"]+/(anime/[0-9]+)/[^"]+">[^<]+</a>',
		'<a href="[^"]+/anime/[0-9]+/[^"]+">([^<]+)</a>',
		'<small>([^<]+)</small>'
        ),
        array(
		'link',
		'title',
		'work'
        ),
        static::$limit,
        TRUE,
        '<a href="[^"]+/anime/([0-9]+)/[^"]+">[^<]+</a>'
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get link of the request page
	 *
	 * @return 		string
	 */
	protected function _link() {

		$key = 'link';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		return static::setValue( 'link', $this->lastChanges( $this->request()::$requestData[ 'url' ] ) );
	}
}