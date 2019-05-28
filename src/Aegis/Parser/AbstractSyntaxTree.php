<?php

namespace Aegis\Parser;

use Aegis\Contracts\CompilerInterface;
use Aegis\Node\Node;

/**
 * Class AbstractSyntaxTree
 * @package Aegis\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class AbstractSyntaxTree extends Node
{
    /**
     * Compiles the AST
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
