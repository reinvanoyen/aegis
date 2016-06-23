<?php

require_once 'Node.php';
require_once 'Template/Renderer.php';

class BlockNode extends Node
{
	public $id;

	public function run()
	{
		$runtime = Renderer::$runtime;

		$expr = $this->getCompiledAttributes();
		$this->id = $runtime->evaluateExpression( $expr );

		$filename = Renderer::$cache_dir . count( $runtime->blocks ) . '-' . urlencode( 'block-' . Renderer::$filename ) . '.php';
		$runtime->setBlock( $this->id, $filename );

		$content = $this->getCompiledChildren();
		file_put_contents( $filename, $content );
	}

	public function compile()
	{
		return '<?php require $this->getBlock( \'' . $this->id . '\' ) ?>';
	}
}