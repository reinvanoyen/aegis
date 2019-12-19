<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class PhpNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class PhpNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_IDENT, 'php')) {
            $parser->advance();
            $parser->insert(new static());
            $parser->traverseUp();

            $parser->expect(TokenType::T_CLOSING_TAG);
            $parser->advance();

            $parser->parseText();

            $parser->skip(TokenType::T_OPENING_TAG);
            $parser->expect(TokenType::T_IDENT, '/php');
            $parser->advance();
            $parser->expect(TokenType::T_CLOSING_TAG);
            $parser->advance();

            $parser->traverseDown();
            $parser->parseOutsideTag();

            return true;
        }

        return false;
    }

    public function compile(CompilerInterface $compiler)
    {
        $compiler->write('<?php ');
        foreach ($this->getChildren() as $c) {
            $c->compile($compiler);
        }
        $compiler->write(' ?>');
    }
}
