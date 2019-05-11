<?php

include( '../autoload.php' );

$mal = new \MyAnimeList\Search\People( $query );

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
	echo '<div class="mb-3">' . highlight_string( "<?php \$mal = new \\MyAnimeList\\Search\\People( \"{$query}\" ); ?>", TRUE ) . '</div>';
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
	echo '<td class="align-middle">';

	$output = <<<EX

\$mal->setLimit( 10 );

if ( isset( \$mal->results ) ) {

	echo '<ul class="list-group list-group-flush">';

	foreach ( \$mal->results as \$result ) {

		echo '<li class="list-group-item" style="background: none;">';

		if ( isset( \$result[ 'poster' ] ) ) {

			echo "<img src=\"{\$result[ 'poster' ]}\" class=\"pr-2\">";
		}
		echo "<a href=\"" . \$mal->externalLink( 'people', \$result[ 'id' ] ) . "\" target=\"_blank\">{\$result[ 'name' ]}</a>";
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