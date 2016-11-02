<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

class PrintNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if (ExpressionNode::parse($parser)) {
            $parser->wrap(new static());
            $parser->traverseDown();
        }

        if ($parser->skip(Token::T_CLOSING_TAG)) {
            $parser->parseOutsideTag();
        }
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php echo htmlspecialchars(');

        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);
        }

        $compiler->write('); ?>');
    }
}
