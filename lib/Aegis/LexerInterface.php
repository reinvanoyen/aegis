<?php

namespace Aegis;

interface LexerInterface
{
	public function tokenize( $string );
}