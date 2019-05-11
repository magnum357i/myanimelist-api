<?php

use PHPUnit\Framework\TestCase;

class WidgetNewAnime extends TestCase {

	public function test_all_data() {

		$mal = new \MyAnimeList\Widget\NewAnime;

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