<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\Node;
use Aegis\ParserInterface;

/**
 * Class ExpressionNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class ExpressionNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if (
            ConstantNode::parse($parser) ||
            StringNode::parse($parser) ||
            VariableNode::parse($parser) ||
            NumberNode::parse($parser) ||
            ListNode::parse($parser) ||
            FunctionCallNode::parse($parser)
        ) {
            if (!$parser->getScope() instanceof self) {

                // Insert the expression and move inside
                $parser->wrap(new static());
            }

            if (OperatorNode::parse($parser)) {
                if (!self::parse($parser)) {
                    $parser->syntaxError('Unexpected token ' . $parser->getCurrentToken());
                }
            } else {
                $parser->traverseDown();
            }

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);
        }
    }
}
