<?php

namespace Aegis\Runtime\Node;

use Aegis\Token;

class ArgumentListNode extends \Aegis\Node
{
    public static function parse($parser)
    {
        $parser->insert(new static());
        $parser->traverseUp();

        if (ExpressionNode::parse($parser)) {
            if ($parser->skip(Token::T_SYMBOL, ',')) {
                self::parse($parser);
            }
        }

        $parser->traverseDown();

        return true;
    }

    public function compile($compiler)
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
