<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\Node;
use Aegis\ParserInterface;

class ExpressionNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if (
            StringNode::parse($parser) ||
            VariableNode::parse($parser) ||
            NumberNode::parse($parser) ||
            ListNode::parse($parser) ||
            FunctionCallNode::parse($parser) ||
            ConstantNode::parse($parser)
        ) {
            if (!$parser->getScope() instanceof self) {

                // Insert the expression and move inside
                $parser->wrap(new static());
            }

            if (OperatorNode::parse($parser)) {
                self::parse($parser);
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
