<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

class OperatorNode extends Node
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_OP)) {
            $parser->insert(new static($parser->getCurrentToken()->getValue()));
            $parser->advance();

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        if ($this->type === '+') {
            $compiler->write(' . ');
        } else {
            $compiler->write(' '.$this->type.' ');
        }
    }
}
