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

		'newanime'      => 'anime/season',
		'upcominganime' => 'anime/season/later',
		'animecalendar' => 'anime/season/schedule'
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

		return 'widget';
	}
}