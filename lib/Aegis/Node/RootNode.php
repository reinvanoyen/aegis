<?php

namespace Aegis\Node;

use Aegis\CompilerInterface;
use Aegis\Node;

class RootNode extends Node
{
    public function compile(CompilerInterface $compiler)
    {
        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);
        }
    }
}
