<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

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
            $parser->accept(TokenType::T_IDENT, 'raw') ||
            $parser->accept(TokenType::T_IDENT, 'r')
        ) {
            $parser->insert(new static());
            $parser->advance();
            $parser->traverseUp();

            if (!ExpressionNode::parse($parser)) {
                $parser->syntaxError('Unexpected token' . $parser->getCurrentToken() . ', expected expression');
            }
            $parser->setAttribute('expression');

            $parser->expect(TokenType::T_CLOSING_TAG);
            $parser->advance();
            $parser->traverseDown();
            $parser->parseOutsideTag();
        }
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php echo ');
        $this->getAttribute('expression')->compile($compiler);
        $compiler->write('; ?>');
    }
}
