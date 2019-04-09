<?php

/**
 * MyAnimeList Character Page API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Page;

class Character extends \myanimelist\Builder\Page {

	/**
	 * Set type
	 */
	protected static $type = 'character';

	/**
	 * Methods to allow for prefix
	 */
	protected static $methodsToAllow = [ 'title', 'statistic', 'recent' ];

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [ 'people' => 'people/{s}', 'anime' => 'anime/{s}', 'manga' => 'manga/{s}' ];

	/**
	 * @return 		string
	 * @usage 		title()->self
	 */
	protected function getSelfWithTitleFromData() {

		$data = $this->request()::match( '</div><h1.*?>(.*?)</h1></div><div id="content" ?>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config->isOnNameConverting() ) $data = $this->text()->reverseName( $data, 3 );

		$data = $this->text()->replace( '\s*".+"\s*', ' ', $data );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		title()->nickname
	 */
	protected function getNicknameWithTitleFromData() {

		$data = $this->request()::match( '</div><h1.*?>.*?"(.*?)".*?</h1></div><div id="content" ?>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->isOnNameConverting() ) $data = $this->text()->reverseName( $data );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		category
	 */
	protected function getCategoryFromData() {

		return 'character';
	}

	/**
	 * @return 		string
	 * @usage 		poster
	 */
	protected function getPosterFromData() {

		$data = $this->request()::match( [ '(https://myanimelist.cdn-dena.com/images/characters/[0-9]+/[0-9]+\.jpg)', '(https://cdn.myanimelist.net/images/characters/[0-9]+/[0-9]+\.jpg)' ] );

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

		$data = $this->request()::match( '<div class="breadcrumb ?"[^>]*>.*?</div></div>.*?<div.*?>.*?</div>(.*?)<div[^>]*>voice actors</div>', "<br><span>" );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( 'Bounty:\s*<div class="spoiler">.*?<\/span>', '', $data, 'si' );
		$data = $this->text()->replace( '[^\n]+:[^\n]+', '', $data, 'si' );
		$data = $this->text()->descCleaner( $data );

		return $data;
	}

	/**
	 * @return 		array
	 * @usage 		none
	 */
	protected function favorite() {

		$data = $this->request()::match( 'member favorites:\s*([\d,]+)' );

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
	 * @usage 		recent()->anime
	 */
	protected function getAnimeWithRecentFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="normal_header">animeography</div>.*?<table.*?(.*?)</table>', '<tr>(.*?)</tr>',
		[ '<a href="[^"]+anime/(\d+)[^"]+">[^<>]+</a>', '<a href="[^"]+anime/\d+[^"]+">([^<>]+)</a>', '<small>([^<]+)</small>' ],
		[ 'id', 'title', 'role' ],
		static::$limit, NULL, TRUE
		);
	}

	/**
	 * @return 		array
	 * @usage 		recent()->manga
	 */
	protected function getMangaWithRecentFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="normal_header">mangaography</div>.*?<table.*?(.*?)</table>', '<tr>(.*?)</tr>',
		[ '<a href="[^"]+manga/(\d+)[^"]+">[^<>]+</a>', '<a href="[^"]+manga/\d+[^"]+">([^<>]+)</a>', '<small>([^<]+)</small>' ],
		[ 'id', 'title', 'role' ],
		static::$limit, NULL, TRUE, 'id'
		);
	}

	/**
	 * @return 		array
	 * @usage 		voiceactors
	 */
	protected function getVoiceactorsFromData() {

		$lang = 'japanese';

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'voice actors</div>(.+</table>.*<br>)', '<tr>(.*?)</tr>',
		[
		'<a href="[^"]+people/(\d+)[^"]+">[^<]+</a>.*?<div[^>]+><small>' . $lang . '</small>',
		'<a href="[^"]+people/\d+[^"]+">([^<]+)</a>.*?<div[^>]+><small>' . $lang . '</small>',
		'<a href="[^"]+people/\d+[^"]+">[^<]+</a>.*?<div[^>]+><small>(' . $lang . ')</small>'
		],
		[
		'id',
		'name',
		'lang'
		],
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