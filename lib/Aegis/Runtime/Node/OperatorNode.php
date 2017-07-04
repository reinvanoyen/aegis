<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

class OperatorNode extends Node
{
    private $type;
    private $numberMath;

    public function __construct($type, $numberMath=false)
    {
        $this->type = $type;
        $this->numberMath = $numberMath;
    }

    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_OP)) {
            if ((
                $parser->getCurrentToken()->getValue() === '+' &&
                $parser->getScope()->getLastChild() instanceof VariableNode ||
                $parser->getScope()->getLastChild() instanceof NumberNode) && (
                $parser->getNextToken()->getType() === Token::T_VAR ||
                $parser->getNextToken()->getType() === Token::T_NUMBER)
            ) {
                $parser->insert(new static($parser->getCurrentToken()->getValue(), true));
                $parser->advance();
            } else {
                $parser->insert(new static($parser->getCurrentToken()->getValue()));
                $parser->advance();
            }

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        if ($this->type === '+') {
            if ($this->numberMath) {
                $compiler->write(' + ');
            } else {
                $compiler->write(' . ');
            }
        } elseif ($this->type === '?') {
            $compiler->write(' ?: ');
        } else {
            $compiler->write(' '.$this->type.' ');
        }
    }
}
