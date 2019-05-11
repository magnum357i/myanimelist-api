<?php

/**
 * MyAnimeList Character Search API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Search;

use MyAnimeList\Builder\AbstractSearch;

class Character extends AbstractSearch {

	/**
	 * Set type
	 */
	public static $type = 'character';

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [ 'character' => 'character/{s}' ];

	/**
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
}