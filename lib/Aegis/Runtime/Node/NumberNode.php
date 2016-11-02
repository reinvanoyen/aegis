<?php

namespace Aegis\Runtime\Node;

use Aegis\Token;
use Aegis\Node;

class NumberNode extends Node
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public static function parse($parser)
    {
        if ($parser->accept(Token::T_NUMBER)) {
            $parser->insert(new static($parser->getCurrentToken()->getValue()));
            $parser->advance();

            return true;
        }

        return false;
    }

    public function compile($compiler)
    {
        $compiler->write($this->getValue());
    }
}