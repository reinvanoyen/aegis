<?php

namespace Aegis\Runtime\Node;

use Aegis\Compiler;
use Aegis\CompilerInterface;
use Aegis\ParserInterface;
use Aegis\Token;
use Aegis\Node;

/**
 * Class ExtendNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class ExtendNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(Token::T_IDENT, 'extends')) {
            $parser->insert(new static());
            $parser->advance();

            $parser->traverseUp();
            if( ! ExpressionNode::parse($parser) ) {
            	$parser->syntaxError('Unexpected token ' . $parser->getCurrentToken() . ', expected expression');
            }
            $parser->setAttribute();
            $parser->skip(Token::T_CLOSING_TAG);

            $parser->parseOutsideTag();

            $parser->expect(Token::T_IDENT, '/extends');
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
        // Render the head of the extended template

        $compiler->head('<?=$tpl->renderHead(');

        foreach ($this->getAttributes() as $a) {
            $subcompiler = new Compiler();
            $compiler->head($subcompiler->compile($a));
        }

        $compiler->head(')?>');

        // Write the head of the current template

        foreach ($this->getChildren() as $c) {
            $subcompiler = new Compiler();
            $subcompiler->compile($c);
            $compiler->head($subcompiler->getHead());
        }

        // Render the body of the extended template

        $compiler->write('<?=$tpl->renderBody(');

        foreach ($this->getAttributes() as $a) {
            $subcompiler = new Compiler();
            $compiler->write($subcompiler->compile($a));
        }

        $compiler->write(')?>');
    }
}
