<?php

namespace Aegis\Node;

use Aegis\Compiler\Compiler;
use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class ComponentNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class ComponentNode extends Node
{
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_IDENT, 'component')) {
            $parser->insert(new static());
            $parser->advance();

            $parser->traverseUp();

            if (!ExpressionNode::parse($parser)) {
                $parser->syntaxError('Unexpected token ' . $parser->getCurrentToken() . ', expected expression');
            }

            $parser->setAttribute('name');
            $parser->skip(TokenType::T_CLOSING_TAG);
            $parser->skip(TokenType::T_TEXT);

            if ($parser->skip(TokenType::T_OPENING_TAG)) {
                SlotNode::parse($parser, true);
            }

            $parser->skip(TokenType::T_OPENING_TAG);
            $parser->expect(TokenType::T_IDENT, '/component');
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
        $compiler->write('<?php echo $env->createComponentContext(); ?>');

        // Write the head of the child components to the body
        $subcompiler = $compiler->clone();

        foreach ($this->getChildren() as $child) {
            $subcompiler->compile($child);
            $compiler->write($subcompiler->getHead());
        }

        $compiler->write('<?php echo $tpl->render(');
        $this->getAttribute('name')->compile($compiler);
        $compiler->write('); ?>');

        $compiler->write('<?php echo $env->rewindComponentContext(); ?>');
    }
}
