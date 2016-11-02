<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

class IncludeNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_IDENT, 'include')) {
            $parser->insert(new static());
            $parser->traverseUp();
            $parser->advance();

            ExpressionNode::parse($parser);
            $parser->setAttribute();

            $parser->skip(Token::T_CLOSING_TAG);
            $parser->traverseDown();
            $parser->parseOutsideTag();
        }
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php $tpl->render(');

        foreach ($this->getAttributes() as $a) {
            $a->compile($compiler);
        }

        $compiler->write('); ?>');
    }
}
