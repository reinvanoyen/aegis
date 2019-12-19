<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class IfNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class IfNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_IDENT, 'if')) {
            $parser->insert(new static());
            $parser->advance();
            $parser->traverseUp();

            if (! ConditionNode::parse($parser)) {
                $parser->syntaxError('Unexpected token '.$parser->getCurrentToken().', expected condition');
            }
            $parser->setAttribute('condition');

            $parser->expect(TokenType::T_CLOSING_TAG);
            $parser->advance();

            $parser->parseOutsideTag();

            if (ElseNode::parse($parser)) {
                $parser->parseOutsideTag();
            }

            if (ElseIfNode::parse($parser)) {
                $parser->parseOutsideTag();
            }

            $parser->expect(TokenType::T_IDENT, '/if');
            $parser->advance();
            $parser->skip(TokenType::T_CLOSING_TAG);

            $parser->traverseDown();
            $parser->parseOutsideTag();

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php if(');
        $this->getAttribute('condition')->compile($compiler);
        $compiler->write('): ?>');

        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);
        }

        $compiler->write('<?php endif; ?>');
    }
}
