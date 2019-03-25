<?php

/**
 * MyAnimeList Character Search API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Search;

class Character extends \myanimelist\Builder\Search {

	/**
	 * Set type
	 */
	protected static $type = 'character';

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [

		'character' => 'character/{s}'
	];

	/**
	 * Get results
	 *
	 * @return 		string
	 * @usage 		results
	 */
	protected function _results() {

		$key = 'results';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()::isSent() ) return FALSE;

		$data = $this->request()::matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'search results(.*?</table>)',
		'<tr>(.*?)</tr>',
		[
		'<a[^>]+href="[^"]+character/\d+[^"]+">([^<]+)</a>',
		'<a[^>]+href="[^"]+character/(\d+)[^"]+">[^<]+</a>',
		'<img[^>]+src="(.*?)"[^>]+>',
		'<small>\s*anime:\s*<a[^>]+">(.+)</a>\s*</small>',
		'<small>\s*<div>manga:\s*<a[^>]+">(.+)</a></div>\s*</small>'
		],
		[
		'character_name',
		'character_id',
		'character_poster',
		'anime_titles_list',
		'manga_titles_list'
		],
		static::$limit
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