<?php

/**
 * MyAnimeList Character Api
 *
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    		0.8.2
 */

namespace myanimelist\Types;

class Character
{
	use \myanimelist\Grabber\Helper, \myanimelist\Grabber\Request;

	/**
	 * Set type
	 */
	public static $type = 'character';

	/**
	 * If true, names becomes the first-last order instead of the last-first order
	 */
	public static $reverseName = TRUE;

	/**
	 * Take object parameter and send request
	 *
	 * @param  string $id Enter page id on MAL
	 * @return void
	 */
	public function __construct( $id )
	{
		$this->request( $id );
	}

	/**
	 * Get character name
	 *
	 * @return void | string (in output)
	 */
	public function _charactername()
	{
		$key = 'charactername';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = $this->match( '</div><h1.*?>(.*?)</h1></div><div id="content" ?>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		if ( static::$reverseName == TRUE )
		{
			$data = static::reverseName( $data, 3 );
		}

		$data = static::replace( '\s*".+"\s*', ' ', $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get character name
	 *
	 * @return void | string (in output)
	 */
	public function _nickname()
	{
		$key = 'nickname';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = $this->match( '</div><h1.*?>.*?"(.*?)".*?</h1></div><div id="content" ?>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		if ( static::$reverseName == TRUE )
		{
			$data = static::reverseName( $data );
		}

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get poster
	 *
	 * @return void | string (in output)
	 */
	public function _poster()
	{
		$key = 'poster';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = $this->match( '(https://myanimelist.cdn-dena.com/images/characters/[0-9]+/[0-9]+\.jpg)' );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get description
	 *
	 * @return void | string (in output)
	 */
	public function _description()
	{
		$key = 'description';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = $this->match( '<div class="breadcrumb ?"[^>]*>.*?</div></div>.*?<div.*?>.*?</div>(.*?)<div[^>]*>voice actors</div>', "<br><span>" );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		$data = static::replace( 'Bounty:\s*<div class="spoiler">.*?<\/span>', '', $data, 'si' );
		$data = static::replace( '[^\n]+:[^\n]+', '', $data, 'si' );

		$data = static::descCleaner( $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get favorites
	 *
	 * @return void | string (in output)
	 */
	public function _favorites()
	{
		$key = 'favorites';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = $this->match( 'member favorites:\s*([\d,]+)' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		$data = static::formatK( $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get recent anime list
	 *
	 * @return void | array (in output)
	 */
	public function _recentanime( $limit=10 )
	{
		$key = 'recentanime';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

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
        $limit,
        TRUE
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get recent manga list
	 *
	 * @return void | array (in output)
	 */
	public function _recentmanga( $limit=10 )
	{
		$key = 'recentmanga';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

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
        $limit,
        TRUE
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get voice actors
	 *
	 * @return void | array (in output)
	 */
	public function _voiceactors( $limit=10, $lang='japanese' )
	{
		$key = 'voiceactors';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

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
        $limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get link to the request page
	 *
	 * @return void | string (in output)
	 */
	public function _link()
	{
		$key = 'link';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		return static::setValue( $key, static::lastChanges( static::$requestUrl ) );
	}
}