<?php

namespace Aegis\Node;

use Aegis\CompilerInterface;
use Aegis\Node;

class TextNode extends Node
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write($this->value);
    }
}
