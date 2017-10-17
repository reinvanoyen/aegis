<?php

namespace Aegis\Node;

use Aegis\CompilerInterface;
use Aegis\Node;

/**
 * Class TextNode
 * @package Aegis\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class TextNode extends Node
{
    /**
     * @var string
     */
    private $value;

    /**
     * TextNode constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Compiles the node
     *
     * @param CompilerInterface $compiler
     * @return void
     */
    public function compile(CompilerInterface $compiler) : void
    {
        $compiler->write($this->value);
    }
}
