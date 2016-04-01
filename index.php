<?php

require_once 'Lexer/SimpleLexer.php';

$lexer = new SimpleLexer();

echo $lexer->tokenize( file_get_contents( 'input.txt' ) );