<?php

require_once 'Node.php';
require_once 'Template/Renderer.php';

class Block extends Node
{
	public function compile()
	{
		$expr = $this->getCompiledAttributes();
		$content = $this->getCompiledChildren();

		$runtime = Renderer::$runtime;
		$blockname = $runtime->evaluateExpression( $expr );
		$runtime->setBlock( $blockname, $content );

		return $runtime->getBlock( $blockname );
	}
}