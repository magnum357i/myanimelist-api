<?php

use PHPUnit\Framework\TestCase;

class testPageManga extends TestCase {

	public function testGetMangaPage() {

		$mal = new \myanimelist\Page\Manga( 20 );

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess() )                                   $success = FALSE;
		if ( $mal->title()->original === FALSE )                    $success = FALSE;
		if ( $mal->title()->english === FALSE )                     $success = FALSE;
		if ( $mal->title()->japanese === FALSE )                    $success = FALSE;
		if ( $mal->poster === FALSE )                               $success = FALSE;
		if ( $mal->description === FALSE )                          $success = FALSE;
		if ( $mal->status === FALSE )                               $success = FALSE;
		if ( $mal->statistic()->rank === FALSE )                    $success = FALSE;
		if ( $mal->statistic()->member === FALSE )                  $success = FALSE;
		if ( $mal->statistic()->popularity === FALSE )              $success = FALSE;
		if ( $mal->statistic()->favorite === FALSE )                $success = FALSE;
		if ( $mal->score()->vote === FALSE )                        $success = FALSE;
		if ( $mal->score()->point === FALSE )                       $success = FALSE;
		if ( $mal->genres === FALSE )                               $success = FALSE;
		if ( $mal->volume === FALSE )                               $success = FALSE;
		if ( $mal->chapter === FALSE )                              $success = FALSE;
		if ( $mal->published()->first()->month === FALSE )          $success = FALSE;
		if ( $mal->published()->first()->day === FALSE )            $success = FALSE;
		if ( $mal->published()->first()->year === FALSE )           $success = FALSE;
		if ( $mal->published()->last()->month === FALSE )           $success = FALSE;
		if ( $mal->published()->last()->day === FALSE )             $success = FALSE;
		if ( $mal->published()->last()->year === FALSE )            $success = FALSE;
		if ( $mal->authors === FALSE )                              $success = FALSE;
		if ( $mal->serialization === FALSE )                        $success = FALSE;
		if ( $mal->category === FALSE )                             $success = FALSE;
		if ( $mal->year === FALSE )                                 $success = FALSE;
		if ( $mal->setLimit( 5 )->characters === FALSE )            $success = FALSE;
		if ( $mal->setLimit( 2 )->related()->adaptation === FALSE ) $success = FALSE;

		$this->assertTrue( $success );
	}
}