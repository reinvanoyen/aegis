<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

/**
 * Class RawNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class RawNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if (
            $parser->accept(Token::T_IDENT, 'raw') ||
            $parser->accept(Token::T_IDENT, 'r')
        ) {
            $parser->insert(new static());
            $parser->advance();
            $parser->traverseUp();

            if (! ExpressionNode::parse($parser)) {
                $parser->syntaxError('Unexpected token' . $parser->getCurrentToken() . ', expected expression');
            }
            $parser->setAttribute();

            $parser->expect(Token::T_CLOSING_TAG);
            $parser->advance();
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
