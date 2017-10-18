<?php

namespace Aegis\Runtime\Node;

use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

/**
 * Class PhpNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class PhpNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_IDENT, 'php')) {
            $parser->advance();
            $parser->insert(new static());
            $parser->traverseUp();

            $parser->expect(Token::T_CLOSING_TAG);
	        $parser->advance();

            $parser->parseText();

            $parser->skip(Token::T_OPENING_TAG);
            $parser->expect(Token::T_IDENT, '/php');
            $parser->advance();
            $parser->expect(Token::T_CLOSING_TAG);
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
