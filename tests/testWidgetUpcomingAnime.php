<?php

use PHPUnit\Framework\TestCase;

class TestAnime extends TestCase {

	public function testGetUpcomingAnime() {

		$mal = new \myanimelist\Widget\UpcomingAnime;

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess() )                     $success = FALSE;
		if ( $mal->setLimit( 3 )->tv      === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->ona     === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->ova     === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->movie   === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->special === FALSE ) $success = FALSE;

		$this->assertTrue( $success );
	}
}