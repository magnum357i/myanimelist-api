<?php

/**
 * MyAnimeList People Page API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Page;

class People extends \myanimelist\Builder\Page {

	/**
	 * Set type
	 */
	protected static $type = 'people';

	/**
	 * Methods to allow for prefix
	 */
	protected static $methodsToAllow = [ 'statistic', 'recent' ];

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [ 'character' => 'character/{s}', 'people' => 'people/{s}', 'anime' => 'anime/{s}', 'manga' => 'manga/{s}' ];

	/**
	 * @return 		string
	 * @usage 		name
	 */
	protected function getNameFromData() {

		$data = $this->request()::match( '</div><h1.*?>(.*?)</h1></div><div id="content" ?>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->isOnNameConverting() ) $data = $this->text()->reverseName( $data );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		poster
	 */
	protected function getPosterFromData() {

		$data = $this->request()::match( [ '(https://myanimelist.cdn-dena.com/images/voiceactors/[0-9]+/[0-9]+\.jpg)', '(https://cdn.myanimelist.net/images/voiceactors/[0-9]+/[0-9]+\.jpg)' ] );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->isOnCache() ) {

			$newPoster = $this->cache()->savePoster( $this->getImageName(), $data );
			$data      = $newPoster;
		}

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		description
	 */
	protected function getDescriptionFromData() {

		$data = $this->request()::match( '<span class="dark_text">more:</span></div><div[^>]+">(.*?)</div>.*?</td>', '<br>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '[^\n]+:[^\n]+', '', $data, 'si' );
		$data = $this->text()->descCleaner( $data );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		category
	 */
	protected function getCategoryFromData() {

		return 'people';
	}

	/**
	 * @return 		array
	 * @usage 		favorite
	 */
	protected function favorite() {

		$data = $this->request()::match( '<span class="dark_text">member favorites:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '[^0-9]+', '', $data );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		statistic()->favorite
	 */
	protected function getFavoriteWithStatisticFromData() {

		$data = $this->getFavoriterawWithStatisticFromData();

		if ( $data == FALSE ) return FALSE;

		return $this->text()->formatK( $data );
	}

	/**
	 * @return 		string
	 * @usage 		statistic()->favoriteraw
	 */
	protected function getFavoriterawWithStatisticFromData() {

		return $this->favorite();
	}

	/**
	 * @return 		array
	 * @usage 		recentvoice
	 */
	protected function getVoiceWithRecentFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'voice acting roles</div><table.*?>(.+?)</table>', '<tr>(.*?)</tr>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+">[^<]+</a>', '<a href="[^"]+anime/\d+[^"]+">([^<]+)</a>',
		'<a href="[^"]+character/(\d+)[^"]+">[^<]+</a>', '<a href="[^"]+character/\d+[^"]+">([^<]+)</a>'
		],
		[
		'anime_id', 'anime_title',
		'character_id', 'character_name'
		],
		static::$limit, NULL, TRUE, 'anime_id'
		);
	}

	/**
	 * @return 		array
	 * @usage 		recentwork
	 */
	protected function getWorkWithRecentFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'anime staff positions</div><table.*?>(.+?)</table>', '<tr>(.*?)</tr>',
		[ '<a href="[^"]+anime/(\d+)[^"]+">[^<]+</a>', '<a href="[^"]+anime/\d+[^"]+">([^<]+)</a>', '<small>([^<]+)</small>' ],
		[ 'id', 'title', 'work' ],
		static::$limit, NULL, TRUE, 'id'
		);

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		link
	 */
	public function link() {

		return $this->request()::$url;
	}
}