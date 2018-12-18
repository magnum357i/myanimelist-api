<?php

/**
 * MyAnimeList Character API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Types;

class Character extends \myanimelist\Helper\Builder {

	/**
	 * Set type
	 */
	public static $type = 'character';

	/**
	 * Prefix to call function
	 */
	public static $prefix = '';

	/**
	 * Alloed functions for prefix
	 */
	public static $allowed_methods = array(
		'title',
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
	 * Get character name
	 *
	 * @return 		string
	 */
	protected function _titleself() {

		$key = 'charactername';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '</div><h1.*?>(.*?)</h1></div><div id="content" ?>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config->reverseName == TRUE ) $data = $this->text()->reverseName( $data, 3 );

		$data = $this->text()->replace( '\s*".+"\s*', ' ', $data );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get character name
	 *
	 * @return 		string
	 */
	protected function _titlenickname() {

		$key = 'nickname';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '</div><h1.*?>.*?"(.*?)".*?</h1></div><div id="content" ?>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->reverseName == TRUE ) $data = $this->text()->reverseName( $data );

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

		$data = 'character';

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

		$data = $this->request()->match( '(https://myanimelist.cdn-dena.com/images/characters/[0-9]+/[0-9]+\.jpg)' );

		if ( $data == FALSE ) $data = $this->request()->match( '(https://cdn.myanimelist.net/images/characters/[0-9]+/[0-9]+\.jpg)' );

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

		$data = $this->request()->match( '<div class="breadcrumb ?"[^>]*>.*?</div></div>.*?<div.*?>.*?</div>(.*?)<div[^>]*>voice actors</div>', "<br><span>" );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( 'Bounty:\s*<div class="spoiler">.*?<\/span>', '', $data, 'si' );
		$data = $this->text()->replace( '[^\n]+:[^\n]+', '', $data, 'si' );

		$data = $this->text()->descCleaner( $data );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get favorites
	 *
	 * @return 		array
	 */
	protected function _favorite() {

		$key = 'favorite';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( 'member favorites:\s*([\d,]+)' );

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
	 * Get recent anime list
	 *
	 * @return 		array
	 */
	protected function _recentanime() {

		$key = 'recentanime';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
		'<div class="normal_header">animeography</div>.*?<table.*?(.*?)</table>',
		'<tr>(.*?)</tr>',
        array(
		'<a href="[^"]+/(anime/[0-9]+)/[^"]+">[^<]+</a>',
		'<a href="[^"]+/anime/[0-9]+/[^"]+">([^<]+)</a>',
		'<small>([^<]+)</small>'
        ),
        array(
		'link',
		'title',
		'role'
        ),
        static::$limit,
        TRUE
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get recent manga list
	 *
	 * @return 		array
	 */
	protected function _recentmanga() {

		$key = 'recentmanga';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
		'<div class="normal_header">mangaography</div>.*?<table.*?(.*?)</table>',
		'<tr>(.*?)</tr>',
        array(
		'<a href="[^"]+/(manga/[0-9]+)/[^"]+">[^<]+</a>',
		'<a href="[^"]+/manga/[0-9]+/[^"]+">([^<]+)</a>',
		'<small>([^<]+)</small>'
        ),
        array(
		'link',
		'title',
		'role'
        ),
        static::$limit,
        TRUE
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get voice actors
	 *
	 * @return 		array
	 */
	protected function _voiceactors() {

		$key  = 'voiceactors';
		$lang = 'japanese';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
		'voice actors</div>(.+</table>.*<br>)',
		'<tr>(.*?)</tr>',
        array(
		'<a href="[^"]+/(people/[0-9]+)/[^"]+">[^<]+</a>.*?<div[^>]+><small>' . $lang . '</small>',
		'<a href="[^"]+/people/[0-9]+/[^"]+">([^<]+)</a>.*?<div[^>]+><small>' . $lang . '</small>',
		'<a href="[^"]+/people/[0-9]+/[^"]+">[^<]+</a>.*?<div[^>]+><small>(' . $lang . ')</small>'
        ),
        array(
		'people_link',
		'people_name',
		'people_lang'
        ),
        static::$limit
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