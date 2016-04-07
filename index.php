<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Lexer/Lexer.php';
require_once 'Parser/Parser.php';
require_once 'Compiler/Compiler.php';

$lexer = new Lexer();
$stream = $lexer->tokenize( file_get_contents( 'input.txt' ) );

$parser = new Parser();
$parsed = $parser->parse( $stream );

$compiler = new Compiler();

function renderTree( $p, $c = '' )
{
	foreach( $p->getChildren() as $n )
	{
		echo $c . get_class( $n ) . '<br />';

		renderTree( $n, $c . '&nbsp;&nbsp;&nbsp;&nbsp;|__&nbsp;' );
	}
}

echo '<table width="100%" style="font-family: monospace;">';
echo '<tr>';
echo '<td width="33.33%" valign="top">';
echo $stream;
echo '</td>';
echo '<td width="33.33%" valign="top">';
echo renderTree( $parsed );
echo '</td>';
echo '<td width="33.33%" valign="top">';
echo $compiler->compile( $parsed );
echo '</td>';
echo '</tr>';
echo '</table>';