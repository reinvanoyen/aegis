<?php

namespace Aegis;

class ParseError extends \Exception
{
	protected $srcLine;

	public function __construct( $message, $srcLine )
	{
		parent::__construct( $message . ' on line ' . $srcLine );

		$this->srcLine = $srcLine;
	}

	public function getSrcLine()
	{
		return $this->srcLine;
	}

	public function getSourceFragment()
	{
		// @TODO return a snippet from the template source
	}
}