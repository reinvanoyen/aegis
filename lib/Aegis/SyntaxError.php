<?php

namespace Aegis;

class SyntaxError extends \Exception
{
	//private $line;

	public function __construct( $message, $line )
	{
		parent::__construct( $message );

		//$this->line = $line;
	}

	public function __toString()
	{
		return $this->getMessage();
	}

	public function source_fragment()
	{
		// @TODO
	}
}