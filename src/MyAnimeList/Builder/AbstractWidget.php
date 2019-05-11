<?php

namespace MyAnimeList\Builder;

abstract class AbstractWidget extends AbstractBuilder {

	/**
	 * Saving directories
	 */
	public static $folders = [

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
	protected function getFileName() {

		return 'newanime';
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