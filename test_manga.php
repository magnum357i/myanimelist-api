<?php

include( 'autoload.php' );

$mal = new myanimelist\Types\Manga( 2 );

$mal->_titleoriginal();
$mal->_titleenglish();
$mal->_titlejapanese();
$mal->_poster();
$mal->_description();
$mal->_type();
$mal->_rank();
$mal->_vote();
$mal->_point();
$mal->_genres();
$mal->_popularity();
$mal->_members();
$mal->_favorites();
$mal->_status();
$mal->_published();
$mal->_authors();
$mal->_volume();
$mal->_chapter();
$mal->_serialization();
$mal->_firstchapter();
$mal->_lastchapter();
$mal->_year();
$mal->_characters( 7 );
$mal->_related( 'adaptation',         5 );
$mal->_related( 'sequel',             5 );
$mal->_related( 'prequel',            5 );
$mal->_related( 'parentstory',        5 );
$mal->_related( 'sidestory',          5 );
$mal->_related( 'other',              5 );
$mal->_related( 'spinoff',            5 );
$mal->_related( 'alternativeversion', 5 );
$mal->_link();

$data = $mal->output();

if ( count( $data ) <= 3 )
{
	die( 'There is a problem retrieving data.' );
}

var_dump( $data );