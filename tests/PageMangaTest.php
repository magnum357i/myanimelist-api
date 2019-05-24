<?php

use PHPUnit\Framework\TestCase;

class PageMangaTest extends TestCase {

    private $mal;

    protected function setUp(): void {

		$this->mal = new \MyAnimeList\Page\Manga( 22 );
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

	public function testScoreVote(): void {

		$this->assertTrue( ( $this->mal->scoreVote == FALSE ) ? FALSE : TRUE );
	}

	public function testScorePoint(): void {

		$this->assertTrue( ( $this->mal->scorePoint == FALSE ) ? FALSE : TRUE );
	}

	public function testGenres(): void {

		$this->assertTrue( ( $this->mal->genres == FALSE ) ? FALSE : TRUE );
	}

	public function testVolume(): void {

		$this->assertTrue( ( $this->mal->volume == FALSE ) ? FALSE : TRUE );
	}

	public function testChapter(): void {

		$this->assertTrue( ( $this->mal->chapter == FALSE ) ? FALSE : TRUE );
	}

	public function testPublishedFirst(): void {

		$this->assertTrue( ( $this->mal->publishedFirst == FALSE ) ? FALSE : TRUE );
	}

	public function testPublishedLast(): void {

		$this->assertTrue( ( $this->mal->publishedLast == FALSE ) ? FALSE : TRUE );
	}

	public function testAuthors(): void {

		$this->assertTrue( ( $this->mal->authors == FALSE ) ? FALSE : TRUE );
	}

	public function testSerialization(): void {

		$this->assertTrue( ( $this->mal->serialization == FALSE ) ? FALSE : TRUE );
	}

	public function testCategory(): void {

		$this->assertTrue( ( $this->mal->category == FALSE ) ? FALSE : TRUE );
	}

	public function testYear(): void {

		$this->assertTrue( ( $this->mal->year == FALSE ) ? FALSE : TRUE );
	}

	public function testCharacters(): void {

		$this->assertTrue( ( $this->mal->setLimit( 5 )->characters == FALSE ) ? FALSE : TRUE );
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