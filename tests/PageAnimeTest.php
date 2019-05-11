<?php

use PHPUnit\Framework\TestCase;

class PageAnime extends TestCase {

	public function test_all_data() {

		$mal = new \MyAnimeList\Page\Anime( 20 );

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess() )                               $success = FALSE;
		if ( $mal->titleOriginal === FALSE )                    $success = FALSE;
		if ( $mal->titleEnglish === FALSE )                     $success = FALSE;
		if ( $mal->titleJapanese === FALSE )                    $success = FALSE;
		if ( $mal->titleOthers === FALSE )                      $success = FALSE;
		if ( $mal->poster === FALSE )                           $success = FALSE;
		if ( $mal->description === FALSE )                      $success = FALSE;
		if ( $mal->status === FALSE )                           $success = FALSE;
		if ( $mal->broadcast === FALSE )                        $success = FALSE;
		if ( $mal->statisticRank === FALSE )                    $success = FALSE;
		if ( $mal->statisticMember === FALSE )                  $success = FALSE;
		if ( $mal->statisticPopularity === FALSE )              $success = FALSE;
		if ( $mal->statisticFavorite === FALSE )                $success = FALSE;
		if ( $mal->rating === FALSE )                           $success = FALSE;
		if ( $mal->episode === FALSE )                          $success = FALSE;
		if ( $mal->scoreVote === FALSE )                        $success = FALSE;
		if ( $mal->scorePoint === FALSE )                       $success = FALSE;
		if ( $mal->genres === FALSE )                           $success = FALSE;
		if ( $mal->source === FALSE )                           $success = FALSE;
		if ( $mal->airedFirst === FALSE )                       $success = FALSE;
		if ( $mal->airedLast === FALSE )                        $success = FALSE;
		if ( $mal->studios === FALSE )                          $success = FALSE;
		if ( $mal->producers === FALSE )                        $success = FALSE;
		if ( $mal->licensors === FALSE )                        $success = FALSE;
		if ( $mal->duration === FALSE )                         $success = FALSE;
		if ( $mal->category === FALSE )                         $success = FALSE;
		if ( $mal->premiered === FALSE )                        $success = FALSE;
		if ( $mal->year === FALSE )                             $success = FALSE;
		if ( $mal->setLimit( 5 )->voice === FALSE )             $success = FALSE;
		if ( $mal->setLimit( 5 )->staff === FALSE )             $success = FALSE;
		if ( $mal->setLimit( 2 )->relatedAdaptation === FALSE ) $success = FALSE;
		if ( $mal->trailer === FALSE )                          $success = FALSE;

		$this->assertTrue( $success );
	}
}