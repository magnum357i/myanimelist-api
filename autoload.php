<?php


require_once( __DIR__ . '/src/myanimelist/Helper/Builder.php' );
require_once( __DIR__ . '/src/myanimelist/Helper/Cache.php' );
require_once( __DIR__ . '/src/myanimelist/Helper/Config.php' );
require_once( __DIR__ . '/src/myanimelist/Helper/Request.php' );

require_once( __DIR__ . '/src/myanimelist//Types/Anime.php' );
require_once( __DIR__ . '/src/myanimelist/Types/Character.php' );
require_once( __DIR__ . '/src/myanimelist/Types/Manga.php' );
require_once( __DIR__ . '/src/myanimelist/Types/People.php' );


/*

spl_autoload_register(
	function ( $class )
	{
		var_dump( $class ); die;
		$ds    = DIRECTORY_SEPARATOR;
		$dir   = __DIR__;
		$class = str_replace( '\\', $ds, $class );
		$file  = "{$dir}{$ds}{$class}.php";
		if ( is_readable( $file ) ) require_once $file;
	}
);

*/