<?php

use PHPUnit\Framework\TestCase;

class testWidgetNewAnime extends TestCase {

	public function testGetNewAnime() {

		$mal = new \myanimelist\Widget\NewAnime;

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess() )                     $success = FALSE;
		if ( $mal->setLimit( 3 )->tvnew   === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->ona     === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->ova     === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->movie   === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 3 )->special === FALSE ) $success = FALSE;

		$this->assertTrue( $success );
	}
}