<?php

use PHPUnit\Framework\TestCase;

class PagePeople extends TestCase {

	public function test_all_data() {

		$mal = new \MyAnimeList\Page\People( 9411 );

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess() )                         $success = FALSE;
		if ( $mal->name === FALSE )                       $success = FALSE;
		if ( $mal->poster === FALSE )                     $success = FALSE;
		if ( $mal->description === FALSE )                $success = FALSE;
		if ( $mal->statisticFavorite === FALSE )          $success = FALSE;
		if ( $mal->setLimit( 5 )->recentVoice === FALSE ) $success = FALSE;
		//if ( $mal->setLimit( 5 )->recentWork === FALSE )  $success = FALSE;
		if ( $mal->birth === FALSE )                      $success = FALSE;
		if ( $mal->death === FALSE )                      $success = FALSE;
		if ( $mal->height === FALSE )                     $success = FALSE;
		//if ( $mal->weight === FALSE )                     $success = FALSE;

		$this->assertTrue( $success );
	}
}