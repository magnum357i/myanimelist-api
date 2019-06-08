<?php

/**
 * MyAnimeList Manga Search API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Search;

use MyAnimeList\Builder\AbstractSearch;

class Manga extends AbstractSearch {

	/**
	 * @var 		array 			Key list for all purposes
	 */
	public $keyList = [ 'results' ];

	/**
	 * @return 		array
	 * @usage 		results
	 */
	protected function getResultsFromData() {

		return
		$this->request()::matchTable(
		$this->config(),
		'search results(.*?</table>)', '<tr>(.*?)</tr>',
		[ '<a[^>]+href="[^"]+manga/(\d+)[^"]+"[^>]+>.*?<strong>', '<strong>(.*?)</strong>', '<img[^>]+src="([^"]+images/manga[^"]+)"[^>]+>' ],
		[ 'id', 'title', 'poster' ],
		static::$limit
		);
	}
}