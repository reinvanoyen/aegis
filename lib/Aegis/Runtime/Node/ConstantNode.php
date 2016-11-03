<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

class ConstantNode extends Node
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

    public static function parse(ParserInterface $parser)
    {
        if (
            $parser->accept(Token::T_IDENT, 'false') ||
            $parser->accept(Token::T_IDENT, 'true') ||
            $parser->accept(Token::T_IDENT, 'null')
        ) {
            $parser->insert(new static($parser->getCurrentToken()->getValue()));
            $parser->advance();

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write($this->getValue());
    }
}
