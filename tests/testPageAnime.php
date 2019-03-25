<?php

use PHPUnit\Framework\TestCase;

class testPageAnime extends TestCase {

	public function testGetAnimePage() {

		$mal = new \myanimelist\Page\Anime( 20 );

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess() )                                   $success = FALSE;
		if ( $mal->title()->original === FALSE )                    $success = FALSE;
		if ( $mal->title()->english === FALSE )                     $success = FALSE;
		if ( $mal->title()->japanese === FALSE )                    $success = FALSE;
		if ( $mal->title()->sysnonmys === FALSE )                   $success = FALSE;
		if ( $mal->poster === FALSE )                               $success = FALSE;
		if ( $mal->description === FALSE )                          $success = FALSE;
		if ( $mal->status === FALSE )                               $success = FALSE;
		if ( $mal->broadcast()->day === FALSE )                     $success = FALSE;
		if ( $mal->broadcast()->hour === FALSE )                    $success = FALSE;
		if ( $mal->broadcast()->minute === FALSE )                  $success = FALSE;
		if ( $mal->statistic()->rank === FALSE )                    $success = FALSE;
		if ( $mal->statistic()->member === FALSE )                  $success = FALSE;
		if ( $mal->statistic()->popularity === FALSE )              $success = FALSE;
		if ( $mal->statistic()->favorite === FALSE )                $success = FALSE;
		if ( $mal->rating === FALSE )                               $success = FALSE;
		if ( $mal->episode === FALSE )                              $success = FALSE;
		if ( $mal->score()->vote === FALSE )                        $success = FALSE;
		if ( $mal->score()->point === FALSE )                       $success = FALSE;
		if ( $mal->genres === FALSE )                               $success = FALSE;
		if ( $mal->source === FALSE )                               $success = FALSE;
		if ( $mal->aired()->first()->month === FALSE )              $success = FALSE;
		if ( $mal->aired()->first()->day === FALSE )                $success = FALSE;
		if ( $mal->aired()->first()->year === FALSE )               $success = FALSE;
		if ( $mal->aired()->last()->month === FALSE )               $success = FALSE;
		if ( $mal->aired()->last()->day === FALSE )                 $success = FALSE;
		if ( $mal->aired()->last()->year === FALSE )                $success = FALSE;
		if ( $mal->studios === FALSE )                              $success = FALSE;
		if ( $mal->producers === FALSE )                            $success = FALSE;
		if ( $mal->licensors === FALSE )                            $success = FALSE;
		if ( $mal->duration()->hour === FALSE )                     $success = FALSE;
		if ( $mal->duration()->min === FALSE )                      $success = FALSE;
		if ( $mal->category === FALSE )                             $success = FALSE;
		if ( $mal->premiered()->season === FALSE )                  $success = FALSE;
		if ( $mal->premiered()->year === FALSE )                    $success = FALSE;
		if ( $mal->year === FALSE )                                 $success = FALSE;
		if ( $mal->setLimit( 5 )->voice === FALSE )                 $success = FALSE;
		if ( $mal->setLimit( 5 )->staff === FALSE )                 $success = FALSE;
		if ( $mal->setLimit( 2 )->related()->adaptation === FALSE ) $success = FALSE;
		if ( $mal->trailer === FALSE )                              $success = FALSE;

		$this->assertTrue( $success );
	}
}