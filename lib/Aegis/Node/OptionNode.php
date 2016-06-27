<?php

namespace Aegis\Node;

class OptionNode extends Node
{
	public $value;

	public function __construct( $value )
	{
		$this->value = $value;
	}

	public function compile( $compiler ) {}
}