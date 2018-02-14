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

class People
{
	use \myanimelist\Grabber\Helper, \myanimelist\Grabber\Request;

	/**
	 * Set type
	 */
	public static $type = 'people';

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
	 * Get name
	 *
	 * @return void | string (in output)
	 */
	public function _name()
	{
		$key = 'name';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = $this->match('</div><h1.*?>(.*?)</h1></div><div id="content" ?>');

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

		$data = $this->match( '(https://myanimelist.cdn-dena.com/images/voiceactors/[0-9]+/[0-9]+\.jpg)' );

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

		$data = $this->match( '<span class="dark_text">more:</span></div><div[^>]+">(.*?)</div>.*?</td>', '<br>', 'si' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

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

		$data = $this->match( '<span class="dark_text">member favorites:</span>(.*?)</div>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		$data = static::formatK( $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get recent voice actiong roles
	 *
	 * @return void | string (in output)
	 */
	public function _recentvoice( $limit=10 )
	{
		$key = 'recentvoice';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

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
        $limit,
        TRUE,
        '<a href="[^"]+/anime/([0-9]+)/[^"]+">[^<]+</a>'
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get recent works
	 *
	 * @return void | string (in output)
	 */
	public function _recentwork( $limit=10 )
	{
		$key = 'recentwork';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

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
        $limit,
        TRUE,
        '<a href="[^"]+/anime/([0-9]+)/[^"]+">[^<]+</a>'
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