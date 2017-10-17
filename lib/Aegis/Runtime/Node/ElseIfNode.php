<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

/**
 * Class ElseIfNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class ElseIfNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_IDENT, 'elseif')) {
            $parser->insert(new static());
            $parser->advance();
            $parser->traverseUp();

            if (! ConditionNode::parse($parser)) {
                $parser->syntaxError('Unexpected token ' . $parser->getCurrentToken());
            }
            $parser->setAttribute();

            $parser->traverseDown();

            $parser->expect(Token::T_CLOSING_TAG);
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
        $compiler->write('<?php elseif( ');
        foreach ($this->getAttributes() as $a) {
            $a->compile($compiler);
        }
        $compiler->write(' ): ?>');
    }
}
