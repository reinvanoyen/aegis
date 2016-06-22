<?php

require_once 'Node.php';

class LoopNode extends Node
{
	public function compile()
	{
		$output = '<?php call_user_func( function() { ?>';
		$output .= '<?php for( $i = 0; $i < ' . $this->getCompiledAttributes() . '; $i++ ): ?>';
		$output .= $this->getCompiledChildren();
		$output .= '<?php endfor; ?>';
		$output .= '<?php } ); ?>';
		return $output;
	}
}