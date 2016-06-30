<?php

namespace Aegis;

class Template extends Renderer
{
	private $variables = [];
	private $blocks = [];
	
	public function __get( $k )
	{
		return $this->variables[ $k ];
	}

	public function __set( $k, $v )
	{
		$this->variables[ $k ] = $v;
	}

	public function setBlock( $id, $callable )
	{
		$this->blocks[ $id ] = [ $callable ];
	}

	public function appendBlock( $id, $callable )
	{
		$this->blocks[ $id ][] = $callable;
	}

	public function prependBlock( $id, $callable )
	{
		array_unshift( $this->blocks[ $id ], $callable );
	}

	public function getBlock( $id )
	{
		foreach( $this->blocks[ $id ] as $callable ) {

			$callable();
		}
	}
}