<?php


$id = $_POST[ 'malId' ];

switch( $_POST[ 'malType' ] ) {

	case 'a': include( 'infoAnime.php' ); break;
	case 'm': include( 'infoManga.php' ); break;
	case 'c': include( 'infoCharacter.php' ); break;
	case 'p': include( 'infoPeople.php' ); break;
}