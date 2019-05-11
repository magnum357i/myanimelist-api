<?php

use PHPUnit\Framework\TestCase;

class PageCharacter extends TestCase {

	public function test_all_data() {

		$mal = new \MyAnimeList\Page\Character( 40 );

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess() )                         $success = FALSE;
		if ( $mal->titleSelf === FALSE )                  $success = FALSE;
		if ( $mal->titleNickname === FALSE )              $success = FALSE;
		if ( $mal->poster === FALSE )                     $success = FALSE;
		if ( $mal->description === FALSE )                $success = FALSE;
		if ( $mal->statisticFavorite === FALSE )          $success = FALSE;
		if ( $mal->setLimit( 5 )->recentAnime === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 5 )->recentManga === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 5 )->voiceactors === FALSE ) $success = FALSE;

		$this->assertTrue( $success );
	}
}