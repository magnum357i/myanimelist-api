<?php

/**
 * MyAnimeList People API
 *
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    		0.9.0
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
	 * Set limit
	 *
	 * @return this class
	 */
	public function setLimit( $int ) {

		static::$limit = $int;

		return $this;
	}

	/**
	 * Page is correct?
	 *
	 * @return bool
	 */
	public function isSuccess() {

		return ( empty( $this->_name() ) ) ? FALSE : TRUE;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	protected function _name() {

		$key = 'name';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->match('</div><h1.*?>(.*?)</h1></div><div id="content" ?>');

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->reverseName == TRUE ) $data = static::reverseName( $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get poster
	 *
	 * @return string
	 */
	protected function _poster() {

		$key = 'poster';

		if ( !isset( static::$data[ 'saveposter' ] ) ) static::setValue( 'saveposter', 'no' );

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = static::match( '(https://myanimelist.cdn-dena.com/images/voiceactors/[0-9]+/[0-9]+\.jpg)' );

		if ( $data == FALSE ) $data = static::match( '(https://cdn.myanimelist.net/images/voiceactors/[0-9]+/[0-9]+\.jpg)' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->cache == TRUE AND static::$data[ 'saveposter' ] == 'no' ) {

			$newPoster = $this->cache()->savePoster( $data );
			$data      = $newPoster;

			static::setValue( 'saveposter', 'yes' );
		}

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	protected function _description() {

		$key = 'description';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->match( '<span class="dark_text">more:</span></div><div[^>]+">(.*?)</div>.*?</td>', '<br>', 'si' );

		if ( $data == FALSE ) return FALSE;

		$data = static::replace( '[^\n]+:[^\n]+', '', $data, 'si' );
		$data = static::descCleaner( $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get category
	 *
	 * @return string
	 */
	protected function _category() {

		$key = 'category';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = 'people';

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get favorites
	 *
	 * @return string
	 */
	protected function _favorites() {

		$key = 'favorites';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->match( '<span class="dark_text">member favorites:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = static::formatK( $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get recent voice actiong roles
	 *
	 * @return string
	 */
	protected function _recentvoice( $limit=10 ) {

		$key = 'recentvoice';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = static::matchTable(
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
	 * @param  int   		$limit   	How many rows do you want to fetch?
	 * @return string
	 */
	protected function _recentwork( $limit=10 ) {

		$key = 'recentwork';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = static::matchTable(
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
	 * @return string
	 */
	protected function _link() {

		$key = 'link';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		return static::setValue( 'link', $this->lastChanges( $this->request()::$requestData[ 'url' ] ) );
	}
}