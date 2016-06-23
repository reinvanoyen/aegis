<?php

require_once 'Node.php';
require_once 'Template/Renderer.php';

class ExtendNode extends Node
{
	public $renderer;

	public function run()
	{
		// First we must parse and run the nodes in the requested template

		$expr = $this->getCompiledAttributes();
		
		$runtime = Renderer::$runtime;
		$filename = $runtime->evaluateExpression( $expr );

		$this->renderer = new Renderer( $runtime );
		echo $filename;
		$this->renderer->parse( $filename );
		$this->renderer->run();

		// Now we must run the childs of the extends node itself
		
		foreach( $this->getChildren() as $c )
		{
			$c->run();
		}
	}

	public function compile()
	{
		// Render the extended template
		return $this->renderer->compile();
	}
}