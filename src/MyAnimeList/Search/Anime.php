<?php

/**
 * MyAnimeList Anime Search API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Search;

use MyAnimeList\Builder\AbstractSearch;

class Anime extends AbstractSearch {

	/**
	 * Set type
	 */
	public static $type = 'anime';

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
}