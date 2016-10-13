<?php

namespace Aegis\Node;

class TextNode extends \Aegis\Node
{
	private $value;

	public function __construct( $value )
	{
		$this->value = $value;
	}

	public function compile( $compiler )
	{
		$compiler->write( $this->value );
	}
}