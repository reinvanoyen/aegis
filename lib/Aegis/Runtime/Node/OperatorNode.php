<?php

namespace Aegis\Runtime\Node;

use Aegis\Token;

class OperatorNode extends \Aegis\Node
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public static function parse($parser)
    {
        if ($parser->accept(Token::T_OP)) {
            $parser->insert(new static($parser->getCurrentToken()->getValue()));
            $parser->advance();

            return true;
        }

        return false;
    }

    public function compile($compiler)
    {
        if ($this->type === '+') {
            $compiler->write(' . ');
        } else {
            $compiler->write(' '.$this->type.' ');
        }
    }
}
