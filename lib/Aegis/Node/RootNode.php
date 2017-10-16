<?php

namespace Aegis\Node;

use Aegis\CompilerInterface;
use Aegis\Node;

/**
 * Class RootNode
 * @package Aegis\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class RootNode extends Node
{
	/**
	 * Compiles the node
	 *
	 * @param CompilerInterface $compiler
	 * @return void
	 */
    public function compile(CompilerInterface $compiler) : void
    {
        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);
        }
    }
}
