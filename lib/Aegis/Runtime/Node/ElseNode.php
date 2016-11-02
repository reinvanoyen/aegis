<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

class ElseNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_IDENT, 'else')) {
            $parser->insert(new static());
            $parser->advance();

            $parser->expect(Token::T_CLOSING_TAG);
            $parser->advance();

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php else: ?>');
    }
}
