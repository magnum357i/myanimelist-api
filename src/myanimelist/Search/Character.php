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
	protected static $externalLinks = [ 'character' => 'character/{s}' ];

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
		[
		'<a[^>]+href="[^"]+character/\d+[^"]+">([^<]+)</a>', '<a[^>]+href="[^"]+character/(\d+)[^"]+">[^<]+</a>', '<img[^>]+src="([^"]+images/characters[^"]+)"[^>]+>',
		[ '<small>\s*anime:\s*<a[^>]+>(.+)</a>\s*</small>', '<small>\s*anime:\s*<a[^>]+>(.+)</a>\s*</div>' ],
		[ '<small>\s*<div>manga:\s*<a[^>]+>(.+)</a></div>\s*</small>', '<div>\s*manga:\s*<a[^>]+>(.+)</a>\s*</div>' ]
		],
		[
		'character_name', 'character_id', 'character_poster',
		'anime_titles_list',
		'manga_titles_list'
		],
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