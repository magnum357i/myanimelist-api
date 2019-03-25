<?php

/**
 * Builder to Create API of a New Widget
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Builder;

abstract class Widget extends \myanimelist\Builder\Builder {

	/**
	 * "single": Saves all values in a single file
	 * "multi": Creates files by first keys
	 */
	protected $cacheMode = 'multi';

	/**
	 * Saving directories
	 */
	protected $folders = [

		'main'  => 'widget',
		'file'  => 'json',
		'image' => 'cover'
	];

	/**
	 * base_url/?
	 */
	protected $urlPatterns = [

		'new'      => 'anime/season',
		'upcoming' => 'anime/season/later',
		'calendar' => 'anime/season/schedule'
	];

	/**
	 * Url query
	 *
	 * @return 		string
	 */
	protected function url() {

		return $this->urlPatterns[ static::$type ];
	}

	/**
	 * File name for cache
	 *
	 * @return 		string
	 */
	protected function fileName() {

		return 'newanime';
	}

	/**
	 * Image name for cache
	 *
	 * @return 		string
	 */
	protected function imageName() {

		return NULL;
	}
}