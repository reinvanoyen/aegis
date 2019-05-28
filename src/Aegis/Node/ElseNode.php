<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

class ElseNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_IDENT, 'else')) {
            $parser->insert(new static());
            $parser->advance();

            $parser->expect(TokenType::T_CLOSING_TAG);
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
