<?php

class Compiler
{
	public $parsedTree;

	public function __construct( Node $parsedTree )
	{
		$this->parsedTree = $parsedTree;
	}

	public function run()
	{
		$this->parsedTree->run();
	}

	public function compile()
	{
		return $this->parsedTree->compile();
	}
}