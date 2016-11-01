<?php

namespace Aegis\Runtime\Node;

use Aegis\Token;

class FunctionCallNode extends \Aegis\Node
{
    private $funcName;

    public function __construct($funcName)
    {
        $this->funcName = $funcName;
    }

    public function getFuncName()
    {
        return $this->funcName;
    }

    public static function parse($parser)
    {
        if ($parser->accept(Token::T_IDENT) && $parser->acceptNext(Token::T_SYMBOL, '(')) {
            $parser->insert(new static($parser->getCurrentToken()->getValue()));
            $parser->advance();
            $parser->skip(Token::T_SYMBOL, '(');

            $parser->traverseUp();

            ArgumentListNode::parse($parser);

            $parser->skip(Token::T_SYMBOL, ')');
            $parser->traverseDown();

            return true;
        }

        return false;
    }

    public function compile($compiler)
    {
        $compiler->write('$env->functions[');
        $compiler->write('\'');
        $compiler->write($this->funcName);
        $compiler->write('\'');
        $compiler->write(']');
        $compiler->write('(');

        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);
        }

        $compiler->write(')');
    }
}
