<?php

namespace Aegis\Runtime\Node;

use Aegis\Compiler;
use Aegis\Token;

class ExtendNode extends \Aegis\Node
{
    /*
     *
     * {{ extends "templatename" }}{{ /extends }}
     *
     * */

    public static function parse($parser)
    {
        if ($parser->accept(Token::T_IDENT, 'extends')) {
            $parser->insert(new static());
            $parser->advance();

            $parser->traverseUp();
            ExpressionNode::parse($parser);
            $parser->setAttribute();
            $parser->skip(Token::T_CLOSING_TAG);

            $parser->parseOutsideTag();

            $parser->skip(Token::T_OPENING_TAG);
            $parser->skip(Token::T_IDENT, '/extends');
            $parser->skip(Token::T_CLOSING_TAG);

            $parser->traverseDown();
            $parser->parseOutsideTag();

            return true;
        }

        return false;
    }

    public function compile($compiler)
    {
        // Render the head of the extended template

        $compiler->head('<?=$tpl->renderHead( ');

        foreach ($this->getAttributes() as $a) {
            $subcompiler = new Compiler($a);
            $compiler->head($subcompiler->compile());
        }

        $compiler->head(')?>');

        // Write the head of the current template

        foreach ($this->getChildren() as $c) {
            $subcompiler = new Compiler($c);
            $subcompiler->compile();
            $compiler->head($subcompiler->getHead());
        }

        // Render the body of the extended template

        $compiler->write('<?=$tpl->renderBody( ');

        foreach ($this->getAttributes() as $a) {
            $subcompiler = new Compiler($a);
            $compiler->write($subcompiler->compile());
        }

        $compiler->write(')?>');
    }
}
