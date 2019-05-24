<?php

include( '../autoload.php' );
include( 'functions.php' );

$ram1 = memory_get_usage();
$time = microtime( TRUE );

$mal = new \MyAnimeList\Search\Character( $query );

$mal->config()->enablecache  = TRUE;
$mal->config()->reversename  = TRUE;
$mal->config()->bigimages    = TRUE;
$mal->config()->expiredbyday = 2;

// If required
// $mal->config()->setCurlOption( 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0', 'USERAGENT' );

$mal->cache()->setPath( ROOT_PATH . '/upload' );
$mal->sendRequestOrGetData();

if ( $mal->isSuccess() ) {

	echo '<h3>Usage</h3>';
	echo '<div class="mb-3">' . highlight_string( "<?php \$mal = new \\MyAnimeList\\Search\\Character( \"{$query}\" ); ?>", TRUE ) . '</div>';
	echo '<table class="table table-striped table-sm table-bordered m-0 mb-5">';
	echo '<thead>';
	echo '<th scope="col" class="align-middle">Variable</th>';
	echo '<th scope="col" class="align-middle">Type</th>';
	echo '<th scope="col" class="align-middle" style="width: 100%">Value</th>';
	echo '<th scope="col" class="align-middle">View Code</th>';
	echo '</thead>';
	echo '<tbody>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">results</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle miniImage">';

	$output = <<<EX

\$mal->setLimit( 10 );

if ( isset( \$mal->results ) ) {

	echo '<ul class="list-group list-group-flush">';

	foreach ( \$mal->results as \$result ) {

		echo '<li class="list-group-item" style="background: none;">';
		echo '<div class="row">';
		echo '<div class="col">';

		if ( isset( \$result[ 'poster' ] ) ) echo "<img src=\"{\$result[ 'poster' ]}\" class=\"pr-2\">";

		echo "<a href=\"" . \$mal->externalLink( 'character', \$result[ 'id' ] ) . "\" target=\"_blank\">{\$result[ 'name' ]}</a>";
		echo '</div>';

		if ( isset( \$result[ 'mangas' ] ) ) {

			echo '<div class="col">';
			echo 'A: ';
			echo '<ul class="commaList">';

			foreach( \$result[ 'mangas' ] as \$r ) {

				echo "<li><a href=\"" . \$mal->externalLink( 'anime', \$r[ 'id' ] ) . "\" target=\"_blank\"><span class=\"badge badge-secondary\">{\$r[ 'title' ]}</span></li>";
			}

			echo '</ul>';
			echo '</div>';
		}

		if ( isset( \$result[ 'animes' ] ) ) {

			echo '<div class="col">';
			echo 'M: ';
			echo '<ul class="commaList">';

			foreach( \$result[ 'animes' ] as \$r ) {

				echo "<li><a href=\"" . \$mal->externalLink( 'manga', \$r[ 'id' ] ) . "\" target=\"_blank\"><span class=\"badge badge-secondary\">{\$r[ 'title' ]}</span></a></li>";
			}

			echo '</ul>';
			echo '</div>';
		}

		echo '</div>';
		echo '</li>';
	}

	echo '</ul>';
}
else {

	echo '<span class="text-danger">Not found.</span>';
}
EX;

	eval( $output );
	echo '</td>';
	echo '<td class="align-middle text-center">';
	echo '<button type="button" class="btn btn-sm btn-outline-dark btn-block" data-html="true" data-toggle="popover" data-placement="left" data-content="' . outputFormatter( $output ) . '"><i class="fas fa-question-circle"></i></button>';
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">link()</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

	echo "<a href=\"{\$mal->link()}\" target=\"_blank\">{\$mal->link()}</a>";
EX;

	eval( $output );
	echo '</td>';
	echo '<td class="align-middle text-center">';
	echo '<button type="button" class="btn btn-sm btn-outline-dark btn-block" data-html="true" data-toggle="popover" data-placement="left" data-content="' . outputFormatter( $output ) . '"><i class="fas fa-question-circle"></i></button>';
	echo '</td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';

	jsonContent();
	statisticDashboard( $query, FALSE );
}
else {

	echo 'There is a problem retrieving data.';
}