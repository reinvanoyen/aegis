<?php

namespace Aegis\Runtime\Node;

use Aegis\Compiler;
use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

class PhpNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_IDENT, 'php')) {
            $parser->insert(new static());
            $parser->advance();

            $parser->traverseUp();
            $parser->skip(Token::T_CLOSING_TAG);

            $parser->parseText();

            $parser->skip(Token::T_OPENING_TAG);
            $parser->skip(Token::T_IDENT, '/php');
            $parser->skip(Token::T_CLOSING_TAG);

            $parser->traverseDown();
            $parser->parseOutsideTag();

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php ');
        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);
        }
        $compiler->write(' ?>');
    }
}
