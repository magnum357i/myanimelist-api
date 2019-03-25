<?php

/**
 * MyAnimeList Anime Search API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Search;

class Anime extends \myanimelist\Builder\Search {

	/**
	 * Set type
	 */
	protected static $type = 'anime';

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [

		'anime' => 'anime/{s}'
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
		'<a[^>]+href="[^"]+anime/(\d+)[^"]+"[^>]+>.*?<strong>',
		'<strong>(.*?)</strong>',
		'<img[^>]+src="(.*?)"[^>]+>'
		],
		[
		'id',
		'title',
		'poster'
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