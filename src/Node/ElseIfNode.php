<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class ElseIfNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class ElseIfNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_IDENT, 'elseif')) {
            $parser->insert(new static());
            $parser->advance();
            $parser->traverseUp();

            if (! ConditionNode::parse($parser)) {
                $parser->syntaxError('Unexpected token ' . $parser->getCurrentToken());
            }
            $parser->setAttribute('condition');

            $parser->traverseDown();

            $parser->expect(TokenType::T_CLOSING_TAG);
            $parser->advance();

            $parser->parseOutsideTag();

            if (ElseNode::parse($parser)) {
                $parser->parseOutsideTag();
            }

            if (self::parse($parser)) {
                $parser->parseOutsideTag();
            }

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php elseif(');
        $this->getAttribute('condition')->compile($compiler);
        $compiler->write('): ?>');
    }
}
