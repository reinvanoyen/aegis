<?php

require_once 'ParserInterface.php';

interface ParserInterface
{
	public function parse( TokenStream $tokens );
}