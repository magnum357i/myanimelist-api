<?php

include( '../autoload.php' );

$mal = new \MyAnimeList\Page\People( $id );

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
	echo '<div class="mb-3">' . highlight_string( "<?php \$mal = new \\MyAnimeList\\Page\\People( {$id} ); ?>", TRUE ) . '</div>';
	echo '<table class="table table-striped table-sm table-bordered m-0 mb-5">';
	echo '<thead>';
	echo '<th scope="col" class="align-middle">Variable</th>';
	echo '<th scope="col" class="align-middle">Type</th>';
	echo '<th scope="col" class="align-middle" style="width: 100%">Value</th>';
	echo '<th scope="col" class="align-middle">View Code</th>';
	echo '</thead>';
	echo '<tbody>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">name</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->name ) ) {

	echo \$mal->name;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">birth</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->birth ) ) {

	echo \$mal->birth[ 'month' ] . '-' . \$mal->birth[ 'day' ] . '-' . \$mal->birth[ 'year' ];
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">death</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->death ) ) {

	echo \$mal->death[ 'month' ] . '-' . \$mal->death[ 'day' ] . '-' . \$mal->death[ 'year' ];
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">age</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->age ) ) {

	echo \$mal->age;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">height</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->height ) ) {

	echo \$mal->height;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">weight</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->weight ) ) {

	echo \$mal->weight;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">socialFacebook</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->socialFacebook ) ) {

	echo "<a href=\"https://facebook.com/{\$mal->socialFacebook}\" target=\"_blank\">@{\$mal->socialFacebook}</a>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">socialTwitter</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->socialTwitter ) ) {

	echo "<a href=\"https://twitter.com/{\$mal->socialTwitter}\" target=\"_blank\">@{\$mal->socialTwitter}</a>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">socialWebsite</span></td>';
	echo '<td class="align-middle"><span class="badge badge-info">string</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

if ( isset( \$mal->socialWebsite ) ) {

	echo "<a href=\"{\$mal->socialWebsite}\" target=\"_blank\">{\$mal->socialWebsite}</a>";
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

if ( isset( \$mal->statisticFavoriteraw ) ) {

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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">recentWork</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 10 );

if ( isset( \$mal->recentWork ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->recentWork as \$w ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'anime', \$w[ 'id' ] ) . "\" target=\"_blank\">{\$w[ 'title' ]}</a> ({\$w[ 'work' ]})</li>";
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">recentVoice</span></td>';
	echo '<td class="align-middle"><span class="badge badge-warning">array</span></td>';
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 10 );

if ( isset( \$mal->recentVoice ) ) {

	echo '<ul class="commaList">';

	foreach ( \$mal->recentVoice as \$v ) {

		echo "<li><a href=\"" . \$mal->externalLink( 'anime', \$v[ 'anime_id' ] ) . "\" target=\"_blank\">{\$v[ 'anime_title' ]}</a> / <a href=\"" . \$mal->externalLink( 'character', \$v[ 'character_id' ] ) . "\" target=\"_blank\">{\$v[ 'character_name' ]}</a></li>";
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