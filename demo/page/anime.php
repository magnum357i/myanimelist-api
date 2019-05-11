<?php

include( '../autoload.php' );

$mal = new \MyAnimeList\Page\Anime( $id );

$mal->config()->enableCache();
$mal->config()->convertName();
$mal->config()->setExpiredTime( 2 );

// If required
// $mal->config()->setCurlOption( 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0', 'USERAGENT' );

$mal->cache()->setPath( ROOT_PATH . '/upload' );

function outputFormatter( $o ) {

	$o = htmlspecialchars( $o );
	$o = htmlentities( $o );
	$o = "<pre>{$o}</pre>";

	return $o;
}

function timeAgo( $unix ) {

	$timeAgo = '';
    $diff    = time() - $unix;
    $min     = 60;
    $hour    = 60 * $min;
    $day     = $hour * 24;
    $month   = $day * 30;

    if( $diff < 60 )          $timeAgo = $diff                   . ' seconds';
    elseif ( $diff < $hour )  $timeAgo = round( $diff / $min )   . ' minutes';
    elseif ( $diff < $day )   $timeago = round( $diff / $hour )  . ' hours';
    elseif ( $diff < $month ) $timeago = round( $diff / $day )   . ' days';
    else                      $timeAgo = round( $diff / $month ) . ' months';

    return $timeAgo;
}

$mal->sendRequestOrGetData();

if ( $mal->isSuccess() ) {

	echo '<h3>Usage</h3>';
	echo '<div class="mb-3">' . highlight_string( "<?php \$mal = new \\MyAnimeList\\Page\\Anime( {$id} ); ?>", TRUE ) . '</div>';
	echo '<table class="table table-striped table-sm table-bordered m-0 mb-5">';
	echo '<thead>';
	echo '<th scope="col" class="align-middle">Variable</th>';
	echo '<th scope="col" class="align-middle">Type</th>';
	echo '<th scope="col" class="align-middle" style="width: 100%">Value</th>';
	echo '<th scope="col" class="align-middle">View Code</th>';
	echo '</thead>';
	echo '<tbody>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">titleOriginal</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( \$mal->titleOriginal != FALSE ) {

	echo \$mal->titleOriginal;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">titleEnglish</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->titleEnglish ) ) {

	echo \$mal->titleEnglish;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">titleJapanese</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->titleJapanese ) ) {

	echo \$mal->titleJapanese;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">titleOthers</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->titleOthers ) ) {

	echo \$mal->titleOthers;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">poster</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->poster ) ) {

	echo '<img src="' . \$mal->poster . '">';
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">description</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->description ) ) {

	echo \$mal->description;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">background</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->background ) ) {

	echo \$mal->background;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">category</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->category ) ) {

	echo \$mal->category;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">status</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->status ) ) {

	echo \$mal->status;
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

	if ( $mal->category == 'TV' ) {

		echo '<tr>';
		echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">broadcast</span></td>';
		echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
		echo '<td class="align-middle">';

		$output = <<<EX

if ( isset( \$mal->broadcast ) ) {

	echo \$mal->broadcast[ 'dayTitle' ] . '(=' . \$mal->broadcast[ 'dayIndex' ] . ') at ' . \$mal->broadcast[ 'hour' ] . ':' . \$mal->broadcast[ 'minute' ];
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
	}

	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">statisticRank</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->statisticRank ) ) {

	echo \$mal->statisticRank;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">statisticMember</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->statisticMember ) ) {

	echo \$mal->statisticMember;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">statisticMemberraw</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->statisticMemberraw ) ) {

	echo \$mal->statisticMemberraw;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">statisticPopularity</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->statisticPopularity ) ) {

	echo \$mal->statisticPopularity;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">statisticFavorite</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->statisticFavorite ) ) {

	echo \$mal->statisticFavorite;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">statisticFavoriteraw</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset(  \$mal->statisticFavoriteraw ) ) {

	echo \$mal->statisticFavoriteraw;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">rating</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->rating ) ) {

	echo \$mal->rating;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">scoreVote</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->scoreVote ) ) {

	echo \$mal->scoreVote;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">scoreVoteraw</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->scoreVoteraw ) ) {

	echo \$mal->scoreVoteraw;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">scorePoint</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->scorePoint ) ) {

	echo \$mal->scorePoint;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">genres</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->genres ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->genres as \$genre ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'genre', \$genre[ 'id' ] ) . "\" target=\"_blank\">{\$genre[ 'title' ]}</a></li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">source</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->source ) ) {

	echo \$mal->source;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">airedFirst</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->airedFirst ) ) {

	echo \$mal->airedFirst[ 'month' ] . '-' . \$mal->airedFirst[ 'day' ] . '-' . \$mal->airedFirst[ 'year' ];
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

	if ( $mal->category != 'Movie' ) {

		echo '<tr>';
		echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">airedLast</span></td>';
		echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
		echo '<td class="align-middle">';

		$output = <<<EX

if ( isset( \$mal->airedLast ) ) {

	if ( \$mal->airedLast == 'no' ) {

		echo '?';
	}
	else {

		echo \$mal->airedLast[ 'month' ] . '-' . \$mal->airedLast[ 'day' ] . '-' . \$mal->airedLast[ 'year' ];
	}
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
	}

	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">episode</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->episode ) ) {

	echo \$mal->episode;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">studios</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset(  \$mal->studios ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->studios as \$studio ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'producer', \$studio[ 'id' ] ) . "\" target=\"_blank\">{\$studio[ 'title' ]}</a></li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">producers</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->producers ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->producers as \$producer ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'producer', \$producer[ 'id' ] ) . "\" target=\"_blank\">{\$producer[ 'title' ]}</a></li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">licensors</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->licensors ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->licensors as \$licensor ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'producer', \$licensor[ 'id' ] ) . "\" target=\"_blank\">{\$licensor[ 'title' ]}</a></li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">duration</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->duration ) ) {

	echo \$mal->duration[ 'hour' ] . ' hour(s) ' . \$mal->duration[ 'minute' ] . ' minute(s)';
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

	if ( $mal->category == 'TV' ) {

		echo '<tr>';
		echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">premiered</span></td>';
		echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
		echo '<td class="align-middle">';

		$output = <<<EX

if ( isset( \$mal->premiered ) ) {

	echo \$mal->premiered[ 'seasonTitle' ] . '(=' . \$mal->premiered[ 'seasonIndex' ] . ') ' . \$mal->premiered[ 'year' ];
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
	}

	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">year</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->year ) ) {

	echo \$mal->year;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">voice</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 5 );

if ( isset( \$mal->voice ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->voice as \$v ) {

		echo "<li>";
		echo "<a href=\"" . \$mal->externalLink( 'character', \$v[ 'character_id' ] ) . "\" target=\"_blank\">{\$v[ 'character_name' ]}</a>";

		// Sometimes this is not on MAL

		if ( isset( \$v[ 'people_id' ] ) ) {

			echo " / <a href=\"" . \$mal->externalLink( 'people', \$v[ 'people_id' ] ) . "\" target=\"_blank\">{\$v[ 'people_name' ]}</a>";
			echo " ({\$v[ 'people_lang' ]})";
		}

		echo "</li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">staff</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 3 );

if ( isset( \$mal->staff ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->staff as \$s ) {

		echo "<li>";
		echo "<a href=\"" . \$mal->externalLink( 'people', \$s[ 'people_id' ] ) . "\" target=\"_blank\">{\$s[ 'people_name' ]}</a>";
		echo '<ul class="commaList">';
		echo " (";

			foreach ( \$s[ 'people_positions_list' ] as \$p ) {

				echo "<li>{\$p}</li>";
			}

		echo ")";
		echo "</ul>";
		echo "</li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">songOpening</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 10 );

if ( isset( \$mal->songOpening ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->songOpening as \$o ) {

		echo "<li>";
		echo "<i>{\$o[ 'title' ]}</i> by <b>{\$o[ 'singer' ]}</b>";

		if ( isset( \$o[ 'start' ] ) ) {

			echo " - {\$o[ 'start' ]}";
		}

		if ( isset( \$o[ 'end' ] ) ) {

			echo " to {\$o[ 'end' ]}";
		}

		echo "</li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">songEnding</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 10 );

if ( isset( \$mal->songEnding ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->songEnding as \$e ) {

		echo "<li>";
		echo "<i>{\$e[ 'title' ]}</i> by <b>{\$e[ 'singer' ]}</b>";

		if ( isset( \$e[ 'start' ] ) ) {

			echo " - {\$e[ 'start' ]}";
		}

		if ( isset( \$e[ 'end' ] ) ) {

			echo " to {\$e[ 'end' ]}";
		}

		echo "</li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">relatedAdaptation</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 2 );

if ( isset( \$mal->relatedAdaptation ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->relatedAdaptation as \$r ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'manga', \$r[ 'id' ] ) . "\" target=\"_blank\">{\$r[ 'title' ]}</a></li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">relatedPrequel</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 2 );

if ( isset( \$mal->relatedPrequel ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->relatedPrequel as \$r ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'anime', \$r[ 'id' ] ) . "\" target=\"_blank\">{\$r[ 'title' ]}</a></li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">relatedSequel</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 2 );

if ( isset( \$mal->relatedSequel ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->relatedSequel as \$r ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'anime', \$r[ 'id' ] ) . "\" target=\"_blank\">{\$r[ 'title' ]}</a></li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">relatedParentstory</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 2 );

if ( isset( \$mal->relatedParentstory ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->relatedParentstory as \$r ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'anime', \$r[ 'id' ] ) . "\" target=\"_blank\">{\$r[ 'title' ]}</a></li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">relatedSidestory</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 2 );

if ( isset( \$mal->relatedSidestory ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->relatedSidestory as \$r ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'anime', \$r[ 'id' ] ) . "\" target=\"_blank\">{\$r[ 'title' ]}</a></li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">relatedSpinoff</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 2 );

if ( isset( \$mal->relatedSpinoff ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->relatedSpinoff as \$r ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'anime', \$r[ 'id' ] ) . "\" target=\"_blank\">{\$r[ 'title' ]}</a></li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">relatedAlternativeversion</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 2 );

if ( isset( \$mal->relatedAlternativeversion ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->relatedAlternativeversion as \$r ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'anime', \$r[ 'id' ] ) . "\" target=\"_blank\">{\$r[ 'title' ]}</a></li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">relatedOther</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 2 );

if ( isset( \$mal->relatedOther ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->relatedOther as \$r ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'anime', \$r[ 'id' ] ) . "\" target=\"_blank\">{\$r[ 'title' ]}</a></li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">trailer</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->trailer ) ) {

	echo "<a href=\"https://www.youtube.com/watch?v={\$mal->trailer}\" target=\"_blank\">{\$mal->trailer}</a>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">tabBase</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->tabBase ) ) {

	echo \$mal->tabBase;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">tabItems</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 0 );

if ( isset( \$mal->tabItems ) ) {

	echo '<ul class="commaList">';

	\$tabLink = ( \$mal->tabBase != FALSE ) ? \$mal->tabBase : '';

	foreach ( \$mal->tabItems as \$tab ) {

		echo "<li><a href=\"{\$tabLink}{\$tab[ 'href' ]}\" target=\"_blank\">{\$tab[ 'title' ]}</a></li>";
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

	if ( $mal->config()::isOnCache() ) {

		echo '<h3>JSON Content</h3>';
		echo '<div class="p-3 bg-dark mb-5 json">';
		echo '<small>';
		echo '<pre class="text-white">';
		echo json_encode( json_decode( $mal, TRUE ), JSON_PRETTY_PRINT );
		echo '</pre>';
		echo '</small>';
		echo '</div>';
	}

	echo '<h3>Time</h3>';
	echo '<div class="p-3 bg-warning mb-5">';
		echo '<div class="row text-center">';

			if ( $mal->config()::isOnCache() ) {

				echo '<div class="col-sm">';
					echo '<div class="h6">Edited Time</div>';
					echo '<span class="display-4">' . timeAgo( $mal->editedTime() ) . '</span>';
				echo '</div>';
			}

			echo '<div class="col-sm">';
			echo '<div class="h6">Elapsed Time</div>';
				echo '<span class="display-4">' . timeAgo( $mal->elapsedTime() ) . '</span>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}
else {

	echo 'There is a problem retrieving data.';
}