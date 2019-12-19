<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class ArgumentListNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class ArgumentListNode extends Node
{
    /**
     * @param ParserInterface $parser
     * @return bool
     */
    public static function parse(ParserInterface $parser)
    {
        $parser->insert(new static());
        $parser->traverseUp();

        if (ExpressionNode::parse($parser)) {
            if ($parser->skip(TokenType::T_SYMBOL, ',')) {
                self::parse($parser);
            }
        }

        $parser->traverseDown();

        return true;
    }

    /**
     * Compiles the node
     *
     * @param CompilerInterface $compiler
     */
    public function compile(CompilerInterface $compiler)
    {
        $i = 0;

        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);

            ++$i;

            if ($i < count($this->getChildren())) {
                $compiler->write(', ');
            }
        }
    }
}
