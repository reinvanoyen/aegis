<?php

namespace Aegis\Runtime\Node;

use Aegis\Token;

class RawNode extends \Aegis\Node
{
    public static function parse($parser)
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

    public function compile($compiler)
    {
        $compiler->write('<?php echo ');

        foreach ($this->getAttributes() as $a) {
            $a->compile($compiler);
        }

        $compiler->write('; ?>');
    }
}