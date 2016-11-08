<?php

namespace Aegis;

class Container
{
	private $store = [];

	public function set( $id, $callable )
	{
		$this->store[$id] = $callable;
	}

	public function get( $id )
	{
		if (!isset($this->store[$id])) {
			throw new \Exception( 'No dependency with id ' . $id . ' found' );
		}

		$instance = call_user_func( $this->store[$id], $this );

		// @TODO
	}
}