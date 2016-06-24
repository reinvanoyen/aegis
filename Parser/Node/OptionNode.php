<?php

require_once 'Node.php';
require_once 'Compiler/Compiler.php';

class OptionNode extends Node
{
	public $value;

	public function __construct( $value )
	{
		$this->value = $value;
	}

	public function compile( $compiler ) {}
}