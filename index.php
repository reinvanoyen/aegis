<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require 'autoload.php';

// Your playground

require 'TestNode.php';

\Aegis\NodeRegistry::register( 'TestNode' );

$tpl = new \Aegis\Template();

$tpl->setFunction( 'slugify', function( $string ) {

	return trim( preg_replace( '/[^\w.]+/', '-', strtolower( $string ) ), '-' );
} );

$tpl->setFunction( 'reverse', function( $string ) {

	return strrev( $string );
} );

$tpl->setFunction( 'sum', function( ...$numbers ) {

	$sum = 0;

	foreach( $numbers as $n ) {

		$sum += $n;
	}

	return $sum;
} );

$tpl->title = 'test';
$tpl->pages = [ 'test 1', 'test 2', 'test 3' ];
echo $tpl->render( 'index' );