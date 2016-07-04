<?php

namespace Aegis;

interface CompilerInterface
{
	public function __construct( Node $input );
	public function compile();
}