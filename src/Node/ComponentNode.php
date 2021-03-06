<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class ComponentNode
 * @package Aegis\Node
 */
class ComponentNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_SYMBOL, '#')) {
            $parser->insert(new static());
            $parser->advance();

            $parser->traverseUp();
            if (! ExpressionNode::parse($parser)) {
                $parser->syntaxError('Unexpected token ' . $parser->getCurrentToken() . ', expected expression');
            }
            $parser->setAttribute('tpl');
            $parser->skip(TokenType::T_CLOSING_TAG);

            $parser->parseOutsideTag();

            $parser->expect(TokenType::T_IDENT, '/');
            $parser->advance();
            $parser->expect(TokenType::T_SYMBOL, '#');
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
        // Render the head of the extended template

        $compiler->write('<?=$tpl->render(');
        $compiler->write($compiler->clone()->compile($this->getAttribute('tpl')));
        $compiler->write(', \'head\')?>');

        // Write the head of the current template

        foreach ($this->getChildren() as $c) {
            $subcompiler = $compiler->clone();
            $subcompiler->compile($c);
            $compiler->write($subcompiler->getHead());
        }

        // Render the body of the extended template

        $compiler->write('<?=$tpl->render(');
        $compiler->write($compiler->clone()->compile($this->getAttribute('tpl')));
        $compiler->write(', \'body\')?>');
    }
}
