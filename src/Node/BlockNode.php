<?php

namespace Aegis\Node;

use Aegis\Compiler\Compiler;
use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class BlockNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class BlockNode extends Node
{
    /**
     * @param ParserInterface $parser
     * @return bool
     */
    public static function parse(ParserInterface $parser)
    {
        if ($parser->accept(TokenType::T_IDENT, 'block')) {
            $parser->insert(new static());
            $parser->advance();

            $parser->traverseUp();

            if (! ExpressionNode::parse($parser)) {
                $parser->syntaxError('Unexpected token ' . $parser->getCurrentToken() . ', expected expression');
            }
            $parser->setAttribute('name');

            if ($parser->accept(TokenType::T_IDENT, 'prepend') || $parser->accept(TokenType::T_IDENT, 'append')) {
                $parser->insert(new OptionNode($parser->getCurrentToken()->getValue()));
                $parser->setAttribute('method');
                $parser->advance();
            }

            $parser->expect(TokenType::T_CLOSING_TAG);
            $parser->advance();

            $parser->parseOutsideTag();

            $parser->expect(TokenType::T_IDENT, '/block');
            $parser->advance();
            $parser->expect(TokenType::T_CLOSING_TAG);
            $parser->advance();

            $parser->traverseDown();
            $parser->parseOutsideTag();

            return true;
        }

        return false;
    }

    /**
     * @param CompilerInterface $compiler
     */
    public function compile(CompilerInterface $compiler)
    {
        $nameAttr = $this->getAttribute('name');
        $methodAttr = $this->getAttribute('method');

        $subcompiler = $compiler->clone();
        $name = $subcompiler->compile($nameAttr);

        // Determine which function to use
        $blockHeadFunction = 'setBlock';

        if ($methodAttr) {
            $blockHeadFunction = $methodAttr->getValue().'Block';
        }

        // Write the heads of the children first
        foreach ($this->getChildren() as $c) {
            $subcompiler = $compiler->clone();
            $subcompiler->compile($c);
            $compiler->head($subcompiler->getHead());
        }

        // Write head of itself
        $compiler->head('<?php $env->'.$blockHeadFunction.'( ');
        $compiler->head($name);
        $compiler->head(', function() use ( $env, $tpl ) { ?>');

        foreach ($this->getChildren() as $c) {
            $subcompiler = $compiler->clone();
            $subcompiler->compile($c);
            $compiler->head($subcompiler->getBody());
        }

        $compiler->head('<?php } ); ?>');

        // Render itself
        $compiler->write('<?php $env->getBlock( ');
        $compiler->write($name);
        $compiler->write('); ?>');
    }
}
