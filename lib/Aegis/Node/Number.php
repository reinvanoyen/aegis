<?php

namespace Aegis\Node;

class Number extends Node
{
	private $value;

	public function __construct( $value )
	{
		$this->value = $value;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function compile( $compiler )
	{
		$compiler->write( $this->getValue() );
	}
}
