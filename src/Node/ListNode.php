<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class ListNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class ListNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_SYMBOL, '[')) {
            $parser->insert(new static());
            $parser->traverseUp();
            $parser->advance();

            ArgumentListNode::parse($parser);

            $parser->expect(TokenType::T_SYMBOL, ']');
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
