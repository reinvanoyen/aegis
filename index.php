<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require 'autoload.php';

// Your playground

require 'TestNode.php';

\Aegis\NodeRegistry::register( 'TestNode' );

$tpl = new \Aegis\Template();

$tpl->setFunction( 'slugify', function( $string )
{
	return trim( preg_replace( '/[^\w.]+/', '-', strtolower( $string ) ), '-' );
} );

$tpl->setFunction( 'reverse', function( $string )
{
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
$tpl->render( 'test2' );

/*
require 'Wireframer/PageNode.php';
require 'Wireframer/BoxNode.php';
require 'Wireframer/HeaderNode.php';
require 'Wireframer/NavNode.php';
require 'Wireframer/ButtonNode.php';
require 'Wireframer/ParagraphNode.php';
require 'Wireframer/GridNode.php';
require 'Wireframer/CardNode.php';
require 'Wireframer/ImgNode.php';

\Aegis\NodeRegistry::register( [
	'Wireframer\PageNode',
	'Wireframer\BoxNode',
	'Wireframer\HeaderNode',
	'Wireframer\NavNode',
	'Wireframer\ButtonNode',
	'Wireframer\ParagraphNode',
	'Wireframer\GridNode',
	'Wireframer\CardNode',
	'Wireframer\ImgNode',
] );

$tpl = new \Aegis\Template();
$tpl->render( 'wireframer/draft' );
*/