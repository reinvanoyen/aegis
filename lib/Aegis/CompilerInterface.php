<?php

namespace Aegis;

interface CompilerInterface
{
	public function __construct( \Aegis\Node\Node $input );
	public function compile();
}