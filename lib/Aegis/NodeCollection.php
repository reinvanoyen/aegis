<?php

namespace Aegis;

class NodeCollection implements NodeCollectionInterface
{
	private $nodes = [];

	public function add($mixed)
	{
		if (is_array($mixed)) {
			foreach ($mixed as $classname) {
				$this->add($classname);
			}
		} else if (is_string($mixed)) {
			$this->nodes[] = $mixed;
		} else {
			throw new \InvalidArgumentException('Argument should be of type array or string');
		}
	}

	public function parse( ParserInterface $parser )
	{
		foreach ($this->nodes as $node) {
			$node::parse($parser);
		}
	}
}
