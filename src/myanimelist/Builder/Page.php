<?php

/**
 * Builder to Create API of a New Page
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Builder;

abstract class Page extends \myanimelist\Builder\Builder {

	/**
	 * MAL id
	 */
	public static $id = NULL;

	/**
	 * "single": Saves all values in a single file
	 * "multi": Creates files by first keys
	 */
	protected $cacheMode = 'single';

	/**
	 * Saving directories
	 */
	protected $folders = [

		'main'  => 'page',
		'file'  => 'json',
		'image' => 'cover'
	];

	/**
	 * base_url/?
	 */
	protected $urlPatterns = [

		'anime'     => 'anime/{s}',
		'manga'     => 'manga/{s}',
		'people'    => 'people/{s}',
		'character' => 'character/{s}'
	];

	/**
	 * Take object parameter
	 *
	 * @param 		int 				$s 				MAL Id
	 * @return 		void
	 */
	public function __construct( $s ) {

		static::$id = $s;

		parent::__construct();
	}

	/**
	 * Url query
	 *
	 * @return 		string
	 */
	protected function url() {

		return str_replace( '{s}', static::$id, $this->urlPatterns[ static::$type ] );
	}

	/**
	 * File name for cache
	 *
	 * @return 		string
	 */
	protected function getFileName() {

		return static::$id;
	}

	/**
	 * Image name for cache
	 *
	 * @return 		string
	 */
	protected function getImageName() {

		return static::$id;
	}
}