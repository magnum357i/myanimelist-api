<?php

namespace MyAnimeList\Builder;

abstract class AbstractSearch extends AbstractBuilder {

	/**
	 * MAL query
	 */
	public static $query = NULL;

	/**
	 * Saving directories
	 */
	public static $folders = [

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
	 * @param 		object 				$cache 			Cache class
	 * @return 		void
	 */
	public function __construct( $s, \MyAnimeList\Cache\CacheInterface $cache=NULL ) {

		static::$query = $s;

		parent::__construct( $cache );
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