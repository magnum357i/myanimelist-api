<?php

/**
 * MyAnimeList People Search API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Search;

class People extends \myanimelist\Builder\Search {

	/**
	 * Set type
	 */
	protected static $type = 'people';

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [ 'people' => 'people/{s}' ];

	/**
	 * Get results
	 *
	 * @return 		array
	 * @usage 		results
	 */
	protected function getResultsFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'search results(.*?</table>)', '<tr>(.*?)</tr>',
		[ '<a[^>]+href="[^"]+people/(\d+)[^"]+">[^<]+</a>', '<a[^>]+href="[^"]+people/\d+[^"]+">([^<]+)</a>', '<img[^>]+src="([^"]+images/voiceactors[^"]+)"[^>]*>' ],
		[ 'id', 'name', 'poster' ],
		static::$limit
		);
	}

	/**
	 * Get link of the request page
	 *
	 * @return 		string
	 * @usage 		link
	 */
	public function link() {

		return $this->request()::$url;
	}
}