<?php

namespace Aegis\Runtime;

use Aegis\NodeCollection;

class DefaultNodeCollection extends NodeCollection
{
	public function __construct()
	{
		$this->add( [
			'Aegis\\Runtime\\Node\\IfNode',
			'Aegis\\Runtime\\Node\\ForNode',
			'Aegis\\Runtime\\Node\\BlockNode',
			'Aegis\\Runtime\\Node\\ExtendNode',
			'Aegis\\Runtime\\Node\\IncludeNode',
			'Aegis\\Runtime\\Node\\PrintNode',
			'Aegis\\Runtime\\Node\\RawNode',
		] );
	}
}