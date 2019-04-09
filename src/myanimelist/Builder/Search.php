<?php

/**
 * Builder to Create API of a New Search
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Builder;

abstract class Search extends \myanimelist\Builder\Builder {

	/**
	 * MAL query
	 */
	public static $query = NULL;

	/**
	 * "single": Saves all values in a single file
	 * "multi": Creates files by first keys
	 */
	protected $cacheMode = 'single';

	/**
	 * Saving directories
	 */
	protected $folders = [

		'main'  => 'search',
		'file'  => 'json',
		'image' => 'cover'
	];

	/**
	 * base_url/?
	 */
	protected $urlPatterns = [

		'anime'     => 'anime.php?q={s}',
		'manga'     => 'manga.php?q={s}',
		'people'    => 'people.php?q={s}',
		'character' => 'character.php?q={s}'
	];

	/**
	 * Take object parameter
	 *
	 * @param 		string 				$s 				Words to search
	 * @return 		void
	 */
	public function __construct( $s ) {

		parent::__construct();

		static::$query = $s;
	}

	/**
	 * Url query
	 *
	 * @return 		string
	 */
	protected function url() {

		return str_replace( '{s}', static::$query, $this->urlPatterns[ static::$type ] );
	}

	/**
	 * File name for cache
	 *
	 * @return 		string
	 */
	protected function getFileName() {

		return mb_strtolower( preg_replace( '@[^\s0-9a-zA-Z]+@', '', static::$query ) );
	}

	/**
	 * Image name for cache
	 *
	 * @return 		string
	 */
	protected function getImageName() {

		return NULL;
	}
}