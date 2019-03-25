<?php

use PHPUnit\Framework\TestCase;

class TestCharacter extends TestCase {

	public function testGetCharacterPage() {

		$mal = new \myanimelist\Page\Character( 40 );

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess() )                         $success = FALSE;
		if ( $mal->title()->self === FALSE )              $success = FALSE;
		if ( $mal->title()->nickname === FALSE )          $success = FALSE;
		if ( $mal->poster === FALSE )                     $success = FALSE;
		if ( $mal->description === FALSE )                $success = FALSE;
		if ( $mal->statistic()->favorite === FALSE )      $success = FALSE;
		if ( $mal->setLimit( 5 )->recentanime === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 5 )->recentmanga === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 5 )->voiceactors === FALSE ) $success = FALSE;

		$this->assertTrue( $success );
	}
}