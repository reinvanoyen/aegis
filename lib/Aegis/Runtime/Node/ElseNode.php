<?php

namespace Aegis\Runtime\Node;

use Aegis\Token;

class ElseNode extends \Aegis\Node
{
    public static function parse($parser)
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

    public function compile($compiler)
    {
        $compiler->write('<?php else: ?>');
    }
}
