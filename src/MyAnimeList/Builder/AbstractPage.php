<?php

namespace MyAnimeList\Builder;

abstract class AbstractPage extends AbstractBuilder {

	/**
	 * MAL id
	 */
	public static $id = NULL;

	/**
	 * Saving folders
	 */
	public static $folders = [

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
	 * @param 		int 			$s 				MAL Id
	 * @param 		object 			$cache 			Cache class
	 * @return 		void
	 */
	public function __construct( $s, \MyAnimeList\Cache\CacheInterface $cache=NULL ) {

		static::$id = $s;

		parent::__construct( $cache );
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