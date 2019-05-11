<?php

use PHPUnit\Framework\TestCase;

class WidgetAnimeCalendar extends TestCase {

	public function test_all_data() {

		$mal = new \MyAnimeList\Widget\AnimeCalendar;

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess() )                       $success = FALSE;
		if ( $mal->setLimit( 3 )->monday    === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->tuesday   === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->wednesday === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->thursday  === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->friday    === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->saturday  === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->sunday    === FALSE ) $success = FALSE;

		$this->assertTrue( $success );
	}
}