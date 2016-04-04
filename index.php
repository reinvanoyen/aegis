<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Lexer/Lexer.php';

$lexer = new Lexer();

var_dump( $lexer->tokenize( file_get_contents( 'input.txt' ) ) );