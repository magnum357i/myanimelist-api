<?php

/**
 * MyAnimeList Character API
 *
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    		0.9.0
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
	 * Call title functions
	 *
	 * @return this class
	 */
	public function title() {

		static::$prefix = 'title';

		return $this;
	}

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

		return ( empty( $this->_titleself() ) ) ? FALSE : TRUE;
	}

	/**
	 * Get character name
	 *
	 * @return string
	 */
	protected function _titleself() {

		$key = 'charactername';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->match( '</div><h1.*?>(.*?)</h1></div><div id="content" ?>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config->reverseName == TRUE ) $data = static::reverseName( $data, 3 );

		$data = static::replace( '\s*".+"\s*', ' ', $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get character name
	 *
	 * @return string
	 */
	protected function _titlenickname() {

		$key = 'nickname';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->match( '</div><h1.*?>.*?"(.*?)".*?</h1></div><div id="content" ?>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->reverseName == TRUE ) $data = static::reverseName( $data );

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

		$data = 'character';

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get poster
	 *
	 * @return string
	 */
	protected function _poster() {

		$key = 'poster';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = static::match( '(https://myanimelist.cdn-dena.com/images/characters/[0-9]+/[0-9]+\.jpg)' );

		if ( $data == FALSE ) $data = static::match( '(https://cdn.myanimelist.net/images/characters/[0-9]+/[0-9]+\.jpg)' );

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

		$data = $this->match( '<div class="breadcrumb ?"[^>]*>.*?</div></div>.*?<div.*?>.*?</div>(.*?)<div[^>]*>voice actors</div>', "<br><span>" );

		if ( $data == FALSE ) return FALSE;

		$data = static::replace( 'Bounty:\s*<div class="spoiler">.*?<\/span>', '', $data, 'si' );
		$data = static::replace( '[^\n]+:[^\n]+', '', $data, 'si' );

		$data = static::descCleaner( $data );

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

		$data = $this->match( 'member favorites:\s*([\d,]+)' );

		if ( $data == FALSE ) return FALSE;

		$data = static::formatK( $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get recent anime list
	 *
	 * @return array
	 */
	protected function _recentanime() {

		$key = 'recentanime';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = static::matchTable(
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
	 * @return array
	 */
	protected function _recentmanga() {

		$key = 'recentmanga';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = static::matchTable(
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
	 * @return array
	 */
	protected function _voiceactors() {

		$key  = 'voiceactors';
		$lang = 'japanese';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = static::matchTable(
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
	 * @return string
	 */
	protected function _link() {

		$key = 'link';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		return static::setValue( 'link', $this->lastChanges( $this->request()::$requestData[ 'url' ] ) );
	}
}