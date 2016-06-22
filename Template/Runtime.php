<?php

class Runtime
{
	public $variables = [];
	public $blocks = [];

	public function setVariable( $k, $v )
	{
		$this->variables[ $k ] = $v;
		return $this->variables[ $k ];
	}

	public function getVariable( $k )
	{
		return $this->variables[ $k ];
	}

	public function setBlock( $k, $v )
	{
		$this->blocks[ $k ] = $v;
		return $this->blocks[ $k ];
	}

	public function appendBlock( $k, $v )
	{
		$this->blocks[ $k ] = $this->getBlock( $k ) . $v;
	}

	public function prependBlock( $k, $v )
	{
		$this->blocks[ $k ] = $v . $this->getBlock( $k );
	}

	public function getBlock( $k )
	{
		return $this->blocks[ $k ];
	}

	public function evaluateExpression( $expr )
	{
		eval( '$output=' . $expr . ';' );
		return $output;
	}

	public function __set( $k, $v )
	{
		$this->setVariable( $k, $v );
	}

	public function __get( $k )
	{
		return $this->getVariable( $k );
	}
}