<?php

namespace Aegis\Node;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\ParserInterface;
use Aegis\Token\TokenType;

/**
 * Class SlotNode
 * @package Aegis\Runtime\Node
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class SlotNode extends Node
{
    private $insideComponent;

    public function __construct($insideComponent)
    {
        $this->insideComponent = $insideComponent;
    }

    /**
     * @param ParserInterface $parser
     * @return bool
     */
    public static function parse(ParserInterface $parser, $insideComponent = false)
    {
        if ($parser->accept(TokenType::T_IDENT, 'slot')) {
            $parser->insert(new static($insideComponent));
            $parser->advance();

            $parser->traverseUp();

            if (! ExpressionNode::parse($parser)) {
                $parser->syntaxError('Unexpected token ' . $parser->getCurrentToken() . ', expected expression');
            }
            $parser->setAttribute('name');

            $parser->expect(TokenType::T_CLOSING_TAG);
            $parser->advance();

            $parser->parseOutsideTag();

            $parser->expect(TokenType::T_IDENT, '/slot');
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
        $subcompiler = $compiler->clone();
        $name = $subcompiler->compile($nameAttr);

        // Determine which function to use
        $blockHeadFunction = ($this->insideComponent ? 'setSlot' : 'yieldSlot');

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
        $compiler->write('<?php $env->getSlot( ');
        $compiler->write($name);
        $compiler->write('); ?>');
    }
}
