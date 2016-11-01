<?php

namespace Aegis\Runtime\Node;

use Aegis\Token;

class StringNode extends \Aegis\Node
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public static function parse($parser)
    {
        if ($parser->accept(Token::T_STRING)) {
            $parser->insert(new static($parser->getCurrentToken()->getValue()));
            $parser->advance();

            return true;
        }

        return false;
    }

    public function compile($compiler)
    {
        $compiler->write('\''.$this->value.'\'');
    }
}
