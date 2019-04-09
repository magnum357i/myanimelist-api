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
	protected static $externalLinks = [ 'anime' => 'anime/{s}' ];

	/**
	 * @return 		array
	 * @usage 		results
	 */
	protected function getResultsFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'search results(.*?</table>)', '<tr>(.*?)</tr>',
		[ '<a[^>]+href="[^"]+anime/(\d+)[^"]+"[^>]+>.*?<strong>', '<strong>(.*?)</strong>', '<img[^>]+src="([^"]+images/anime[^"]+)"[^>]+>' ],
		[ 'id', 'title', 'poster' ],
		static::$limit
		);
	}

	/**
	 * @return 		string
	 * @usage 		link
	 */
	public function link() {

		return $this->request()::$url;
	}
}