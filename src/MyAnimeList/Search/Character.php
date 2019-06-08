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
	 * @var 		array 			Key list for all purposes
	 */
	public $keyList = [ 'results' ];

	/**
	 * @return 		array
	 * @usage 		results
	 */
	protected function getResultsFromData() {

		$productEditing = function( $value ) {

			preg_match_all( '@<a href="/[^/]+/(\d+)/[^"]+">(.*?)</a>@', $value, $out, PREG_SET_ORDER );

			$rows = [];

			foreach ( $out as $row ) $rows[] = [ 'id' => trim( strip_tags( $row[ 1 ] ) ), 'title' => trim( strip_tags( $row[ 2 ] ) ) ];

			return $rows;
		};

		return
		$this->request()::matchTable(
		$this->config(),
		'search results(.*?</table>)', '<tr>(.*?)</tr>',
		[
		'<a[^>]+href="[^"]+character/\d+[^"]+">([^<]+)</a>', '<a[^>]+href="[^"]+character/(\d+)[^"]+">[^<]+</a>', '<img[^>]+src="([^"]+images/characters[^"]+)"[^>]+>',
		[ '<small>\s*anime:\s*(<a[^>]+>.+</a>)\s*</small>', '<small>\s*anime:\s*(<a[^>]+>.+</a>)\s*</div>' ],
		[ '<small>\s*<div>manga:\s*(<a[^>]+>.+</a>)</div>\s*</small>', '<div>\s*manga:\s*(<a[^>]+>.+</a>)\s*</div>' ]
		],
		[
		'name', 'id', 'poster',
		'animes',
		'mangas'
		],
		static::$limit,
		[ 'animes' => $productEditing, 'mangas' => $productEditing ]
		);
	}
}