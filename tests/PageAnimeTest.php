<?php

use PHPUnit\Framework\TestCase;

class PageAnimeTest extends TestCase {

    private $mal;

    protected function setUp(): void {

		$this->mal = new \MyAnimeList\Page\Anime( 20 );
		$this->mal->sendRequestOrGetData();
		$this->mal->config()->enablecache = TRUE;
	}

	public function testTitleOriginal(): void {

		$this->assertTrue( ( $this->mal->titleOriginal == FALSE ) ? FALSE : TRUE );
	}

	public function testTitleEnglish(): void {

		$this->assertTrue( ( $this->mal->titleEnglish == FALSE ) ? FALSE : TRUE );
	}

	public function testTitleJapanese(): void {

		$this->assertTrue( ( $this->mal->titleJapanese == FALSE ) ? FALSE : TRUE );
	}

	public function testTitleOthers(): void {

		$this->assertTrue( ( $this->mal->titleOthers == FALSE ) ? FALSE : TRUE );
	}

	public function testPoster(): void {

		$this->assertTrue( ( $this->mal->poster == FALSE ) ? FALSE : TRUE );
	}

	public function testDescription(): void {

		$this->assertTrue( ( $this->mal->description == FALSE ) ? FALSE : TRUE );
	}

	public function testStatus(): void {

		$this->assertTrue( ( $this->mal->status == FALSE ) ? FALSE : TRUE );
	}

	public function testOpening(): void {

		$this->assertTrue( ( $this->mal->songOpening == FALSE ) ? FALSE : TRUE );
	}

	public function testEnding(): void {

		$this->assertTrue( ( $this->mal->songEnding == FALSE ) ? FALSE : TRUE );
	}

	public function testBroadcast(): void {

		$this->assertTrue( ( $this->mal->broadcast == FALSE ) ? FALSE : TRUE );
	}

	public function testStatisticRank(): void {

		$this->assertTrue( ( $this->mal->statisticRank == FALSE ) ? FALSE : TRUE );
	}

	public function testStatisticMember(): void {

		$this->assertTrue( ( $this->mal->statisticMember == FALSE ) ? FALSE : TRUE );
	}

	public function testStatisticPopularity(): void {

		$this->assertTrue( ( $this->mal->statisticPopularity == FALSE ) ? FALSE : TRUE );
	}

	public function testStatisticFavorite(): void {

		$this->assertTrue( ( $this->mal->statisticFavorite == FALSE ) ? FALSE : TRUE );
	}

	public function testRating(): void {

		$this->assertTrue( ( $this->mal->rating == FALSE ) ? FALSE : TRUE );
	}

	public function testEpisode(): void {

		$this->assertTrue( ( $this->mal->episode == FALSE ) ? FALSE : TRUE );
	}

	public function testScoreVote(): void {

		$this->assertTrue( ( $this->mal->scoreVote == FALSE ) ? FALSE : TRUE );
	}

	public function testScorePoint(): void {

		$this->assertTrue( ( $this->mal->scorePoint == FALSE ) ? FALSE : TRUE );
	}

	public function testGenres(): void {

		$this->assertTrue( ( $this->mal->genres == FALSE ) ? FALSE : TRUE );
	}

	public function testSource(): void {

		$this->assertTrue( ( $this->mal->source == FALSE ) ? FALSE : TRUE );
	}

	public function testAiredFirst(): void {

		$this->assertTrue( ( $this->mal->airedFirst == FALSE ) ? FALSE : TRUE );
	}

	public function testAiredLast(): void {

		$this->assertTrue( ( $this->mal->airedLast == FALSE ) ? FALSE : TRUE );
	}

	public function testStudios(): void {

		$this->assertTrue( ( $this->mal->studios == FALSE ) ? FALSE : TRUE );
	}

	public function testProducers(): void {

		$this->assertTrue( ( $this->mal->producers == FALSE ) ? FALSE : TRUE );
	}

	public function testLicensors(): void {

		$this->assertTrue( ( $this->mal->licensors == FALSE ) ? FALSE : TRUE );
	}

	public function testDuration(): void {

		$this->assertTrue( ( $this->mal->duration == FALSE ) ? FALSE : TRUE );
	}

	public function testCategory(): void {

		$this->assertTrue( ( $this->mal->category == FALSE ) ? FALSE : TRUE );
	}

	public function testPremiered(): void {

		$this->assertTrue( ( $this->mal->premiered == FALSE ) ? FALSE : TRUE );
	}

	public function testYear(): void {

		$this->assertTrue( ( $this->mal->year == FALSE ) ? FALSE : TRUE );
	}

	public function testVoice(): void {

		$this->assertTrue( ( $this->mal->setLimit( 5 )->voice == FALSE ) ? FALSE : TRUE );
	}

	public function testStaff(): void {

		$this->assertTrue( ( $this->mal->setLimit( 5 )->staff == FALSE ) ? FALSE : TRUE );
	}

	public function testRelated(): void {

		$this->assertTrue( ( $this->mal->setLimit( 2 )->relatedAdaptation == FALSE ) ? FALSE : TRUE );
	}

	public function testTabBase(): void {

		$this->assertTrue( ( $this->mal->tabBase == FALSE ) ? FALSE : TRUE );
	}

	public function testTabItems(): void {

		$this->assertTrue( ( $this->mal->tabItems == FALSE ) ? FALSE : TRUE );
	}
}