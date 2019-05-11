<?php

$id    = $_POST[ 'malId' ];
$type  = $_POST[ 'malType' ];
$query = $_POST[ 'malQuery' ];

const ROOT_PATH = __DIR__;

if ( $type == 'p' ) {

	$category = $_POST[ 'malCategory1' ];

	switch( $category ) {

		case 'a':  include( 'page/anime.php' );     break;
		case 'm':  include( 'page/manga.php' );     break;
		case 'c':  include( 'page/character.php' ); break;
		case 'p':  include( 'page/people.php' );    break;
	}
}
else if ( $type == 'q' ) {

	$category = $_POST[ 'malCategory2' ];

	switch( $category ) {

		case 'a':  include( 'search/anime.php' );     break;
		case 'm':  include( 'search/manga.php' );     break;
		case 'c':  include( 'search/character.php' ); break;
		case 'p':  include( 'search/people.php' );    break;
	}
}
else if ( $type == 'w' ) {

	$category = $_POST[ 'malCategory3' ];

	switch( $category ) {

		case 'n':  include( 'widget/newanime.php' );      break;
		case 'u':  include( 'widget/upcominganime.php' ); break;
		case 'c':  include( 'widget/animecalendar.php' ); break;
	}
}