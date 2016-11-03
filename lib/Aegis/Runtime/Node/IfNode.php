<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

class IfNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_IDENT, 'if')) {
            $parser->insert(new static());
            $parser->advance();
            $parser->traverseUp();

            if (ConditionNode::parse($parser)) {
                $parser->setAttribute();
            }

            $parser->skip(Token::T_CLOSING_TAG);

            $parser->parseOutsideTag();

            if (ElseNode::parse($parser)) {
                $parser->parseOutsideTag();
            }

            $parser->skip(Token::T_OPENING_TAG);
            $parser->skip(Token::T_IDENT, '/if');
            $parser->skip(Token::T_CLOSING_TAG);

            $parser->traverseDown();
            $parser->parseOutsideTag();

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php if( ');

        foreach ($this->getAttributes() as $a) {
            $a->compile($compiler);
        }

        $compiler->write(' ): ?>');

        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);
        }

        $compiler->write('<?php endif; ?>');
    }
}
