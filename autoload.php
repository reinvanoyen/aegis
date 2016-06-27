<?php

function autoloader( $classname )
{
	$classname = ltrim( $classname, '\\' );
	include_once 'lib/' . str_replace( '\\', '/', $classname ) . '.php';
}

spl_autoload_register( 'autoloader' );