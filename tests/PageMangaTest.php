<?php

use PHPUnit\Framework\TestCase;

class PageManga extends TestCase {

	public function test_all_data() {

		$mal = new \MyAnimeList\Page\Manga( 22 );

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
		if ( $mal->statisticRank === FALSE )                    $success = FALSE;
		if ( $mal->statisticMember === FALSE )                  $success = FALSE;
		if ( $mal->statisticPopularity === FALSE )              $success = FALSE;
		if ( $mal->statisticFavorite === FALSE )                $success = FALSE;
		if ( $mal->scoreVote === FALSE )                        $success = FALSE;
		if ( $mal->scorePoint === FALSE )                       $success = FALSE;
		if ( $mal->genres === FALSE )                           $success = FALSE;
		if ( $mal->volume === FALSE )                           $success = FALSE;
		if ( $mal->chapter === FALSE )                          $success = FALSE;
		if ( $mal->publishedFirst === FALSE )                   $success = FALSE;
		if ( $mal->publishedLast === FALSE )                    $success = FALSE;
		if ( $mal->authors === FALSE )                          $success = FALSE;
		if ( $mal->serialization === FALSE )                    $success = FALSE;
		if ( $mal->category === FALSE )                         $success = FALSE;
		if ( $mal->year === FALSE )                             $success = FALSE;
		if ( $mal->setLimit( 5 )->characters === FALSE )        $success = FALSE;
		if ( $mal->setLimit( 2 )->relatedAdaptation === FALSE ) $success = FALSE;

		$this->assertTrue( $success );
	}
}