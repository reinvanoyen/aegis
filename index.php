<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Lexer/SimpleLexer.php';

$lexer = new SimpleLexer();

echo $lexer->tokenize( file_get_contents( 'input.txt' ) );
