<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class AssignmentNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class AssignmentNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_IDENT, 'let')) {
            $parser->insert(new static());
            $parser->advance();
            $parser->traverseUp();

            if (! VariableNode::parse($parser)) {
                $parser->syntaxError('Unexpected token' . $parser->getCurrentToken() . ', expected variable');
            }
            $parser->setAttribute('variable');

            $parser->expect(TokenType::T_IDENT, 'be');
            $parser->advance();

            if (! ExpressionNode::parse($parser)) {
                $parser->syntaxError('Unexpected token' . $parser->getCurrentToken());
            }

            $parser->expect(TokenType::T_CLOSING_TAG);
            $parser->advance();
            $parser->traverseDown();
            $parser->parseOutsideTag();
        }
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php ');
        $this->getAttribute('variable')->compile($compiler);
        $compiler->write(' = ');

        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);
        }

        $compiler->write('; ?>');
    }
}
