<?php

namespace Aegis\Runtime\Node;

class ExpressionNode extends \Aegis\Node
{
    public static function parse($parser)
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

    public function compile($compiler)
    {
        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);
        }
    }
}
