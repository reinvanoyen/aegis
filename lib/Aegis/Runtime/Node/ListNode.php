<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

class ListNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_SYMBOL, '[')) {
            $parser->insert(new static());
            $parser->traverseUp();
            $parser->advance();

            ArgumentListNode::parse($parser);

            $parser->expect(Token::T_SYMBOL, ']');
            $parser->advance();
            $parser->traverseDown();

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('[');

        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);
        }

        $compiler->write(']');
    }
}
