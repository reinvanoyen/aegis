<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

class LogicalOperatorNode extends Node
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public static function parse(ParserInterface $parser)
    {
        if (
            $parser->accept(Token::T_IDENT, 'or') ||
            $parser->accept(Token::T_IDENT, 'and') ||
            $parser->accept(Token::T_IDENT, 'equals')
        ) {
            $parser->insert(new static($parser->getCurrentToken()->getValue()));
            $parser->advance();

            return true;
        }

        if (
            $parser->accept(Token::T_IDENT, 'not')
        ) {
            if ($parser->acceptNext(Token::T_IDENT, 'equals')) {
	            $parser->insert(new static('neq'));
	            $parser->advance();
	            $parser->advance();

	            return true;
            }
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        if ($this->type === 'neq') {
            $compiler->write(' !== ');
        } elseif ($this->type === 'or') {
            $compiler->write(' || ');
        } elseif ($this->type === 'and') {
            $compiler->write(' && ');
        } elseif ($this->type === 'equals') {
            $compiler->write(' === ');
        }
    }
}