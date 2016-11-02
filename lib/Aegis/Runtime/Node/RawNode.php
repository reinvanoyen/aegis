<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\Node;
use Aegis\ParserInterface;
use Aegis\Token;

class RawNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_IDENT, 'raw') || $parser->accept(Token::T_IDENT, 'r')) {
            $parser->insert(new static());
            $parser->advance();

            $parser->traverseUp();

            ExpressionNode::parse($parser);
            $parser->setAttribute();

            $parser->skip(Token::T_CLOSING_TAG);
            $parser->traverseDown();
            $parser->parseOutsideTag();
        }
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php echo ');

        foreach ($this->getAttributes() as $a) {
            $a->compile($compiler);
        }

        $compiler->write('; ?>');
    }
}
